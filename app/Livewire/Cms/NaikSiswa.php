<?php

namespace App\Livewire\Cms;

use App\Livewire\Forms\Cms\FormNaikSiswa;
use Illuminate\Support\Facades\Session;
use App\Models\Siswa as SiswaModel;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\Jadwal;
use Livewire\WithFileUploads;
use App\Traits\CheckAccess;

use Carbon\Carbon;

use App\Models\User;
use App\Models\StoreUser;
use Illuminate\Support\Facades\Hash;

use App\Exports\ExcelExport;
use App\Models\Absensi;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use BaseComponent;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Nilai;
use App\Models\Siswa as TraitsSiswa;

class NaikSiswa extends BaseComponent
{
    use WithFileUploads;
    use CheckAccess;

    public FormSiswa $form;
    public Session $session;

    #[Validate('nullable|image:jpeg,png,jpg,svg|max:2048')]
    public $image;

    public $title = [];

    public $searchBy = [
            [
                'name' => 'Nama',
                'field' => 'nama',
            ],
            [
                'name' => 'NISN',
                'field' => 'nisn',
            ],
            [
                'name' => 'Jenis Kelamin',
                'field' => 'jenis_kelamin',
            ],
            [
                'name' => 'Kelas',
                'field' => 'kode_kelas',
            ],
            [
                'name' => 'No. HP',
                'field' => 'no_hp',
            ],
            [
                'name' => 'Tanggal Lahir',
                'field' => 'tanggal_lahir',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'nama',
        $order = 'asc';

    public $kode_kelas = "";
    public $semester_id = "";
    public $semester_naik = "";
    public $kelas = [];
    public $semester = [];
    public $form_kelas = [];
    public $value_kelas = [];


    public function render()
    {
        if (session()->get('role_id') == 1) {
            $get = TraitsUser::where('username', session()->get('store_username'))->select('image')->first();
            $img = "user/" . $get->image;
        } else if (session()->get('role_id') == 2) {
            $get = TraitsGuru::where('nip', session()->get('store_username'))->select('image')->first();
            $img = "guru/" . $get->image;
        } else if (session()->get('role_id') == 3) {
            $get = TraitsSiswa::where('nisn', session()->get('store_username'))->select('image')->first();
            $img = "siswa/" . $get->image;
        }

        $this->title[] = "Kenaikan";
        $this->title[] = $img;


        $akses = $this->crud($this->title);
        $this->semester = Semester::get();
        $this->form_kelas = Kelas::all();
        if ($this->semester_id != "") {
            $model = SiswaModel::where('siswa.semester_id', $this->semester_id);
        } else {
            $model = new SiswaModel;
        }

        $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.naik-siswa', compact('get'),)->title($this->title);
    }

    public function naik()
    {
        $semester_id = $this->semester_id;
        $semester_naik = $this->semester_naik;

        $update['semester_id'] = $semester_naik;
        SiswaModel::where('semester_id', $semester_id)->update($update);
        $this->dispatch('notification-success');
    }


    public function loadStudents($kelasId)
    {
        // Memastikan kelas dengan $kelasId ada dalam database
        $kelas = Kelas::find($kelasId);

        if (!$kelas) {
            // Jika kelas tidak ditemukan, bisa memberikan error atau fallback
            return 'Kelas tidak ditemukan';
        }

        $this->value_kelas = [
            'id' => $kelasId,
            'nama' => $kelas->nama,  // Menggunakan $kelas yang sudah diverifikasi
            'siswa' => SiswaModel::where('kode_kelas', $kelas->kode_kelas)->get(),  // Menyaring siswa berdasarkan kode_kelas
        ];
    }

    private function calculateAttendance($siswa)
    {
        $totalPertemuan = \DB::table('absensi')
            ->where('nisn', $siswa->nisn)
            ->where('semester_id', $siswa->semester_id)
            ->count();

        $jumlahHadir = \DB::table('absensi')
            ->where('nisn', $siswa->nisn)
            ->where('semester_id', $siswa->semester_id)
            ->where('keterangan', 'Hadir')
            ->count();

        // dd(($jumlahHadir / $totalPertemuan) * 100);

        if ($totalPertemuan === 0) {
            return 0;
        }

        return ($jumlahHadir / $totalPertemuan) * 100;
    }

    // Fungsi untuk menghitung mata pelajaran yang tidak lulus
    private function countFailedSubjects($siswa)
    {
        $kkm = 70;  // Nilai KKM
        $dataNilai = \DB::table('nilai')
            ->where('nisn', $siswa->nisn)
            ->where('semester_id', $siswa->semester_id)
            ->get();
        $jumlahTidakLulus = 0;

        foreach ($dataNilai as $nilai) {
            $rataRata = ($nilai->ph1 + $nilai->ph2 + $nilai->uts + $nilai->uas) / 4;
            if ($rataRata < $kkm) {
                $jumlahTidakLulus++;
            }
        }
        // dd($jumlahTidakLulus);

        return $jumlahTidakLulus;
    }

    // Proses untuk meng-upgrade siswa
    public function processKenaikan($kode_kelas)
    {
        // Ambil semua siswa yang ada di kelas ini
        $siswas = SiswaModel::where('kode_kelas', $kode_kelas)->get();

        if ($siswas->isEmpty()) {
            session()->flash('error', 'Tidak ada siswa di kelas ini.');
            return;
        }

        // Inisialisasi array untuk siswa naik dan tidak naik
        $siswaNaik = [];
        $siswaTidakNaik = [];

        // Loop untuk memproses setiap siswa
        foreach ($siswas as $siswa) {
            // Cek apakah semester masih ganjil (semester 1, 3, atau 5)
            if (in_array($siswa->semester_id, [1, 3, 5])) {
                session()->flash('error', 'Proses Kenaikan Kelas Saat Ini Tidak Dapat Dilakukan Karena Siswa Masih Berada Di Senester Ganjil. Harap Lakukan Kenaikan Kelas Pada Akhir Semester Genap.');
                $siswaTidakNaik[] = $siswa->nama;
                continue; // Lanjutkan ke siswa berikutnya
            }

            // Cek kehadiran siswa
            $kehadiran = $this->calculateAttendance($siswa);
            if ($kehadiran < 70) {
                $siswaTidakNaik[] = $siswa->nama;
                continue; // Lanjutkan ke siswa berikutnya
            }

            // Cek apakah siswa memiliki lebih dari 3 mata pelajaran yang tidak lulus
            $mataPelajaranTidakLulus = $this->countFailedSubjects($siswa);
            if ($mataPelajaranTidakLulus >= 3) {
                $siswaTidakNaik[] = $siswa->nama;
                continue; // Lanjutkan ke siswa berikutnya
            }

            // Menentukan kode kelas baru
            $newKelas = $this->getNextClass($siswa->kode_kelas);

            // Update semester dan kode kelas siswa
            $siswa->update([
                'kode_kelas' => $newKelas,
                'semester_id' => 1,  // Semester awal ajaran baru
            ]);

            // Tambahkan siswa yang berhasil naik ke array
            $siswaNaik[] = $siswa->nama;
        }

        // Reset semester untuk siswa yang tidak naik kelas
        foreach ($siswas as $siswa) {
            if (in_array($siswa->nama, $siswaTidakNaik) && !in_array($siswa->semester_id, [1, 3, 5])) {
                $siswa->update([
                    'semester_id' => 1,  // Reset semester ke awal ajaran baru
                ]);

                // Hapus data di tabel absensi
                Absensi::where('nisn', $siswa->nisn)->delete();

                // Hapus data di tabel nilai
                Nilai::where('nisn', $siswa->nisn)->delete();
            }
        }

        // Menyusun pesan notifikasi
        $totalSiswa = $siswas->count();
        $jumlahNaik = count($siswaNaik);
        $jumlahTidakNaik = count($siswaTidakNaik);

        $messages = [];

        // Pesan untuk siswa yang naik kelas
        if ($jumlahNaik > 0) {
            $messages[] = "<div class='text-success fw-bold'>Jumlah siswa yang naik ke kelas {$this->getNextClass($kode_kelas)} : {$jumlahNaik} dari total {$totalSiswa} siswa.</div>";
        }

        // Pesan untuk siswa yang tidak naik kelas
        if ($jumlahTidakNaik > 0) {
            $namaSiswaTidakNaik = implode(', ', $siswaTidakNaik);
            $messages[] = "<div class='text-danger fw-bold'>Jumlah siswa yang tidak naik kelas: {$jumlahTidakNaik} siswa.</div>";
            $messages[] = "<div class='text-danger'>Berikut siswa yang tidak naik kelas: {$namaSiswaTidakNaik}.</div>";
        }

        // Jika ada siswa yang naik kelas, tambahkan data ke tabel kelas
        if (!empty($siswaNaik)) {
            // Cek apakah kelas dengan kode_kelas tersebut sudah ada
            $existingClass = Kelas::where('kode_kelas', $newKelas)->first();

            if ($existingClass) {
                // Tambahkan notifikasi bahwa kelas sudah ada
                $messages[] = "<div class='text-success fw-bold'>Kelas dengan kode {$newKelas} sudah ada dan tidak perlu dibuat lagi.</div>";
            } else {
                // Cek guru yang belum memiliki kelas
                $guruAvailable = TraitsGuru::whereNotIn('nip', Kelas::pluck('nip'))->inRandomOrder()->first();

                if ($guruAvailable) {
                    // Tentukan tingkat kelas baru
                    $kelas = Kelas::select('tingkat_kelas')->where('kode_kelas', $kode_kelas)->first();
                    $tingkatKelasBaru = $kelas ? $kelas->tingkat_kelas + 1 : 10;

                    // Insert ke tabel kelas
                    Kelas::create([
                        'nip' => $guruAvailable->nip,
                        'kode_kelas' => $newKelas,
                        'tingkat_kelas' => (string) $tingkatKelasBaru,
                        'nama' => $newKelas,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Tambahkan notifikasi bahwa kelas berhasil dibuat
                    $messages[] = "<div class='text-success fw-bold'>Kelas baru dengan kode {$newKelas} berhasil dibuat dan diampu oleh guru dengan NIP {$guruAvailable->nip}.</div>";
                } else {
                    // Jika tidak ada guru yang tersedia
                    $messages[] = "<div class='text-danger fw-bold'>Kelas gagal ditambahkan karena semua guru sudah memiliki kelas.</div>";
                }
            }
        }

        // Menggabungkan pesan dan mengirim ke session
        session()->flash('message', implode('<br>', $messages));
    }



    private function getNextClass($currentClass)
    {
        // Memisahkan bagian depan kelas (X, XI, XII) dan bagian belakang (apapun setelah tanda '-')
        preg_match('/(X|XI|XII)(-.+)/', $currentClass, $matches);

        // Jika tidak ditemukan, berarti format kelas tidak sesuai
        if (empty($matches)) {
            return $currentClass; // Kembalikan kelas asli jika tidak cocok dengan format
        }

        // Mendapatkan angka kelas (X, XI, XII)
        $kelasAngka = $matches[1];

        // Mendapatkan bagian belakang kelas (apapun setelah '-')
        $kodeJurusan = $matches[2];

        // Menentukan kelas selanjutnya berdasarkan angka kelas
        $kelasSelanjutnya = $this->getNextClassPrefix($kelasAngka);

        // Menyusun kode kelas baru dengan bagian belakang yang tetap sama
        return $kelasSelanjutnya . $kodeJurusan;
    }

    // Fungsi untuk menentukan kelas selanjutnya berdasarkan prefix (X, XI, XII)
    private function getNextClassPrefix($kelasAngka)
    {
        switch ($kelasAngka) {
            case 'X':
                return 'XI';  // Kelas X menjadi XI
            case 'XI':
                return 'XII'; // Kelas XI menjadi XII
            case 'XII':
                return 'XIII'; // Kelas XII menjadi XIII (atau sesuai kebutuhan)
            default:
                return $kelasAngka; // Jika tidak ditemukan, kembalikan kelas aslinya
        }
    }


    // Proses untuk meng-upgrade siswa
    public function processStudentUpgrade($siswaId)
    {
        $siswa = SiswaModel::find($siswaId);

        if (!$siswa) {
            session()->flash('error', 'Siswa tidak ditemukan.');
            return;
        }

        // Cek apakah semester masih ganjil (semester 1, 3, atau 5)
        if (in_array($siswa->semester_id, [1, 3, 5])) {
            session()->flash('error', 'Maaf, belum waktunya kenaikan kelas.');
            return;
        }

        // Cek kehadiran siswa
        $kehadiran = $this->calculateAttendance($siswa);
        if ($kehadiran < 70) {
            session()->flash('error', 'Siswa tidak dapat naik kelas, kehadiran kurang dari 70%.');
            return;
        }

        // Cek apakah siswa memiliki lebih dari 3 mata pelajaran yang tidak lulus
        $mataPelajaranTidakLulus = $this->countFailedSubjects($siswa);
        if ($mataPelajaranTidakLulus >= 3) {
            session()->flash('error', 'Siswa tidak dapat naik kelas, memiliki lebih dari 3 mata pelajaran di bawah KKM.');
            return;
        }

        // Menentukan kode kelas baru
        // dd($siswa->kode_kelas);
        $newKelas = $this->getNextClass($siswa->kode_kelas);
        // dd($newKelas);
        // Update semester dan kode kelas siswa
        $siswa->update([
            'kode_kelas' => $newKelas,
            'semester_id' => 1,
        ]);

        // Kirimkan notifikasi sukses
        session()->flash('message', 'Siswa berhasil naik kelas.');
    }
    // Menentukan kelas selanjutnya berdasarkan kode kelas
    // private function getNextClass($currentClass)
    // {

    //     // Mengambil bagian angka kelas (misalnya X, X1, X11)
    //     $kelasAngka = (int)substr($currentClass, 1, 2);  // Mengambil angka dari kode kelas (misalnya X, X1, X11)

    //     // Meningkatkan angka kelas untuk kenaikan
    //     $kelasAngka++;

    //     // Mendapatkan kode jurusan (misalnya BDP untuk pemasaran)
    //     $kodeJurusan = substr($currentClass, 2);  // Menyaring bagian jurusan, misalnya BDP

    //     // Menyusun kode kelas baru (misal dari X10BDP menjadi X11BDP)
    //     return 'X' . $kelasAngka . $kodeJurusan;
    // }

    public function resetView()
    {
        $this->value_kelas = null;
    }
}
