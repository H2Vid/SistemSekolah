<?php

namespace App\Livewire\Cms;

use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Cms\FormNilai;
use Illuminate\Support\Facades\Session;
use App\Models\Nilai as NilaiModel;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Semester;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Traits\CheckAccess;

use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use BaseComponent;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Siswa as TraitsSiswa;
use App\Models\Nilai;

class NilaiGuru extends BaseComponent
{
    use CheckAccess;

    public FormNilai $form;
    public Session $session;

    public $title = [];

    public $searchBy = [
            [
                'name' => 'Mata Pelajaran',
                'field' => 'mata_pelajaran.nama',
            ],
            [
                'name' => 'Kelas',
                'field' => 'kelas.nama',
            ],
            [
                'name' => 'Nama Siswa',
                'field' => 'siswa.nama',
            ],
            [
                'name' => 'Semester',
                'field' => 'semester.nama',
            ],


            [
                'name' => 'PH 1',
                'field' => 'nilai.ph1',
            ],
            [
                'name' => 'PH 2',
                'field' => 'nilai.ph2',
            ],
            [
                'name' => 'UTS',
                'field' => 'nilai.uts',
            ],
            [
                'name' => 'UAS',
                'field' => 'nilai.uas',
            ],
            [
                'name' => 'Keterampilan',
                'field' => 'nilai.nilai_keterampilan',
            ],
            [
                'name' => 'Sikap',
                'field' => 'nilai.sikap',
            ],
            [
                'name' => 'Kebersihan',
                'field' => 'nilai.kebersihan',
            ],
            [
                'name' => 'Kedisiplinan',
                'field' => 'nilai.kedisiplinan',
            ],

        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'siswa.nama',
        $order = 'asc';


    public $semester = [];
    public $kelas = [];
    public $value_kelas = [];
    public $input_kelas = [];
    public $mata_pelajaran = [];
    public $kode_kelas = "";
    public $kode_mapel = "";
    public $semester_id = "";
    public $value_kode_mapel = "";
    public $value_kode_kelas = "";
    public $value_semester_id = "";
    public $value_nisn = "";
    public $list_siswa = [];
    public $ph1 = [];
    public $ph2 = [];
    public $uts = [];
    public $uas = [];
    public $nilai_keterampilan = [];
    public $sikap = [];
    public $kebersihan = [];
    public $kedisiplinan = [];

    public $change_kode_mapel = "";
    public $change_kode_kelas = "";
    public $change_semester = "";
    public $change_nisn = "";
    public $change_nama = "";
    public $change_ph1 = "";
    public $change_ph2 = "";
    public $change_uts = "";
    public $change_uas = "";
    public $change_id = "";
    public $change_nilai_keterampilan = "";
    public $change_sikap = "";
    public $change_kebersihan = "";
    public $change_kedisiplinan = "";

    public function render()
    {
        if(session()->get('role_id') == 1){
            $get = TraitsUser::where('username', session()->get('store_username'))->select('image')->first();
            $img = "user/".$get->image;
        }else if(session()->get('role_id') == 2){
            $get = TraitsGuru::where('nip', session()->get('store_username'))->select('image')->first();
            $img = "guru/".$get->image;
        }else if(session()->get('role_id') == 3){
            $get = TraitsSiswa::where('nisn', session()->get('store_username'))->select('image')->first();
            $img = "siswa/".$get->image;
        }

        $this->title[] = "Nilai";
        $this->title[] = $img;

        // validasi fitur
        $akses = $this->crud($this->title);
        $this->semester = Semester::get();
        $this->kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                ->select('jadwal_pelajaran.kode_kelas')
                                ->groupBy('jadwal_pelajaran.kode_kelas')
                                ->get();
        $this->value_kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                ->pluck('jadwal_pelajaran.kode_kelas') // Use pluck to get an array of kode_kelas values
                                ->toArray();

        $value_mapel = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                ->pluck('jadwal_pelajaran.kode_mapel') // Use pluck to get an array of kode_kelas values
                                ->toArray();

        $this->mata_pelajaran = Jadwal::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                        ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                        ->select('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                        ->groupBy('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                        ->get();

                                        $model = NilaiModel::join('jadwal_pelajaran', 'jadwal_pelajaran.id', '=', 'nilai.id_jadwal')
                                        ->join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                        ->join('kelas', 'kelas.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                        ->join('siswa', 'siswa.nisn', '=', 'nilai.nisn')
                                        ->leftJoin('semester', 'semester.id', '=', 'nilai.semester_id')
                                        ->whereIn('siswa.kode_kelas', $this->value_kelas)
                                        ->where('jadwal_pelajaran.nip', session()->get('store_username'));

                     if ($this->kode_mapel != "") {
                         $model = $model->where('jadwal_pelajaran.kode_mapel', $this->kode_mapel);
                     }

                     if ($this->kode_kelas != "") {
                         $model = $model->where('jadwal_pelajaran.kode_kelas', $this->kode_kelas);
                     }



                     $model = $model->select(
                         'semester.nama as nama_semester',
                         'mata_pelajaran.nama as mapel',
                         'kelas.nama as kelas',
                         'siswa.nama',
                         'nilai.id',
                         'nilai.nisn',
                         'nilai.ph1',
                         'nilai.ph2',
                         'nilai.uts',
                         'nilai.uas',
                         'nilai.nilai_keterampilan',
                         'nilai.sikap',
                         'nilai.kebersihan',
                         'nilai.kedisiplinan',
                         'nilai.created_at',
                         'nilai.updated_at'
                     );

                     $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.nilai-guru', compact('get'))->title($this->title);
    }

    public function changeMataPelajaran($value_kode_mapel=null){
        $this->value_kode_kelas = $value_kode_mapel;


        if(session()->get('role_id') == 1){
            $this->input_kelas = Jadwal::where('kode_mapel', $value_kode_mapel)->select('kode_kelas', DB::raw('COUNT(*) as count'))->groupBy('kode_kelas')->get();
        }else{
            $this->input_kelas = Jadwal::where('kode_mapel', $value_kode_mapel)->where('nip', session()->get('store_username'))->select('kode_kelas', DB::raw('COUNT(*) as count'))->groupBy('kode_kelas')->get();
        }
    }

    public function changeKelas($value_kode_kelas=null){
        $this->value_kode_kelas = $value_kode_kelas;
        if(session()->get('role_id') == 1){
            $this->input_kelas = Jadwal::where('kode_mapel', $this->value_kode_mapel)->select('kode_kelas', DB::raw('COUNT(*) as count'))->groupBy('kode_kelas')->get();
        }else{
            $this->input_kelas = Jadwal::where('kode_mapel', $this->value_kode_mapel)->where('nip', session()->get('store_username'))->select('kode_kelas', DB::raw('COUNT(*) as count'))->groupBy('kode_kelas')->get();

        }
    }

    public function changeSemester($value_semester_id = null)
    {
        // Mengatur semester_id yang dipilih
        $this->value_semester_id = $value_semester_id;

        // Cek apakah role pengguna adalah admin
        if (session()->get('role_id') == 1) {
            // Mengambil data kelas berdasarkan kode mapel
            $this->input_kelas = Jadwal::where('kode_mapel', $this->value_kode_mapel)
                                        ->select('kode_kelas', DB::raw('COUNT(*) as count'))
                                        ->groupBy('kode_kelas')
                                        ->get();
        } else {
            // Mengambil data kelas berdasarkan kode mapel dan nip pengguna
            $this->input_kelas = Jadwal::where('kode_mapel', $this->value_kode_mapel)
                                        ->where('nip', session()->get('store_username'))
                                        ->select('kode_kelas', DB::raw('COUNT(*) as count'))
                                        ->groupBy('kode_kelas')
                                        ->get();
        }

        // Mendapatkan jadwal yang sesuai dengan kode kelas dan mapel
        $getJadwal = Jadwal::where('kode_kelas', $this->value_kode_kelas)
                           ->where('kode_mapel', $this->value_kode_mapel)
                           ->where('semester_id', $this->value_semester_id) // Pastikan semester_id sesuai dengan jadwal
                           ->select('id')
                           ->first();

        if (!$getJadwal) {
            // Tidak ada jadwal yang cocok, return atau tampilkan pesan error
            $this->dispatch('notification-error');
            return;
        }

        // Mengecek apakah nilai sudah ada untuk semester dan kode mapel tertentu
        $cekNilai = NilaiModel::join('jadwal_pelajaran as j', 'j.id', '=', 'nilai.id_jadwal')
                              ->join('siswa as s', 's.nisn', '=', 'nilai.nisn')
                              ->where('j.kode_mapel', $this->value_kode_mapel)
                              ->where('nilai.nisn', $this->value_nisn) // Pastikan nisn sesuai dengan yang dipilih
                              ->where('nilai.semester_id', $this->value_semester_id)
                              ->get();

        // Mendapatkan daftar siswa yang sudah diberi nilai pada semester ini
        $siswaDiberiNilai = NilaiModel::join('siswa', 'siswa.nisn', '=', 'nilai.nisn')
                                      ->where('nilai.semester_id', $this->value_semester_id)
                                      ->where('nilai.kode_mapel', $this->value_kode_mapel)
                                      ->select('siswa.nisn', 'siswa.id', 'siswa.nama')
                                      ->get()
                                      ->pluck('nisn'); // Mengambil nisn siswa yang sudah diberi nilai

        // Menampilkan daftar siswa yang belum diberi nilai pada semester yang benar
        $listSiswa = Siswa::where('kode_kelas', $this->value_kode_kelas)
                         ->where('semester_id', $this->value_semester_id)
                         ->whereNotIn('nisn', $siswaDiberiNilai) // Menghindari siswa yang sudah diberi nilai
                         ->select('nama', 'nisn', 'id')
                         ->orderBy('nama', 'asc')
                         ->get();

        // Jika nilai sudah ada untuk semester yang relevan, tampilkan daftar siswa
        if ($cekNilai->isNotEmpty()) {
            $this->list_siswa = $listSiswa;
            $this->siswa_nilai = $cekNilai; // Menyimpan siswa yang sudah diberi nilai untuk memungkinkan perubahan nilai
        } else {
            // Jika belum ada nilai, tampilkan semua siswa yang sesuai dengan semester
            $this->list_siswa = $listSiswa;
        }
    }

    public function simpan(){
        try {
            $siswa = Siswa::where('kode_kelas', $this->value_kode_kelas)->where('semester_id', $this->value_semester_id)->select('nisn', 'id')->get();
            $getJadwal = Jadwal::where('kode_kelas', $this->value_kode_kelas)->where('semester_id', $this->value_semester_id)->where('kode_mapel', $this->value_kode_mapel)->select('id')->first();
            foreach ($siswa as $l) {
                $nilai_ph1 = $this->ph1[$l->id] ?? 0;
                $nilai_ph2 = $this->ph2[$l->id] ?? 0;
                $nilai_uts = $this->uts[$l->id] ?? 0;
                $nilai_uas = $this->uas[$l->id] ?? 0;
                $nilai_keterampilan = $this->nilai_keterampilan[$l->id] ?? 0;
                $nilai_sikap = $this->sikap[$l->id] ?? 0;
                $nilai_kebersihan = $this->kebersihan[$l->id] ?? 0;
                $nilai_kedisiplinan = $this->kedisiplinan[$l->id] ?? 0;
                // Validasi rentang nilai
                if ($nilai_keterampilan < 0 || $nilai_keterampilan > 100 ||
                $nilai_sikap < 0 || $nilai_sikap > 100 ||
                $nilai_ph1 < 0 || $nilai_ph1 > 100 ||
                $nilai_ph2 < 0 || $nilai_ph2 > 100 ||
                $nilai_uts < 0 || $nilai_uts > 100 ||
                $nilai_uas < 0 || $nilai_uas > 100 ||
                $nilai_kebersihan < 0 || $nilai_kebersihan > 100 ||
                $nilai_kedisiplinan < 0 || $nilai_kedisiplinan > 100) {
                    $this->dispatch('notification-error');
                    return; // Batalkan proses jika ada nilai yang tidak valid
            }

            NilaiModel::create([
                'id_jadwal' => $getJadwal->id,
                'kode_mapel' => $this->value_kode_mapel,
                'semester_id' => $this->value_semester_id,
                'nisn' => $l->nisn,
                'ph1' => $nilai_ph1,
                'ph2' => $nilai_ph2,
                'uts' => $nilai_uts,
                'uas' => $nilai_uas,
                'nilai_keterampilan' => $nilai_keterampilan,
                'sikap' => $nilai_sikap,
                'kebersihan' => $nilai_kebersihan,
                'kedisiplinan' => $nilai_kedisiplinan,
            ]);
            }
            $this->value_kode_kelas = "";
            $this->value_kode_mapel = "";
            $this->value_semester_id = "";
            $this->list_siswa = [];
            $this->dispatch('notification-success');
            $this->dispatch('closeModal', modal: 'mazer-modal');
        } catch (\Exception $e) {
            // Jika ada error, kirim notifikasi error atau log error
            $this->dispatch('notification-error');
            $this->dispatch('closeModal', modal: 'mazer-modal');

        }

    }

    public function change($id){
        $get = NilaiModel::leftJoin('semester','semester.id', '=', 'nilai.semester_id')->join('jadwal_pelajaran as j', 'j.id', '=', 'nilai.id_jadwal')->join('siswa as s', 's.nisn', '=', 'nilai.nisn')->where('nilai.id', $id)->select('nilai.*', 's.nisn', 's.nama', 'j.kode_kelas', 'semester.nama as nama_semester')->first();
        $this->change_id = $get->id;
        $this->change_kode_mapel = $get->kode_mapel;
        $this->change_kode_kelas = $get->kode_kelas;
        $this->change_semester = $get->nama_semester;
        $this->change_nisn = $get->nisn;
        $this->change_nama = $get->nama;
        $this->change_ph1 = $get->ph1;
        $this->change_ph2 = $get->ph2;
        $this->change_uts = $get->uts;
        $this->change_uas = $get->uas;
        $this->change_nilai_keterampilan = $get->nilai_keterampilan;
        $this->change_sikap = $get->sikap;
        $this->change_kebersihan = $get->kebersihan;
        $this->change_kedisiplinan = $get->kedisiplinan;

    }

    public function changeSubmit(){ $this->validate([
        'change_ph1' => 'required|numeric',
        'change_ph2' => 'required|numeric',
        'change_uts' => 'required|numeric',
        'change_uas' => 'required|numeric',
        'change_nilai_keterampilan' => 'required|numeric',
        'change_sikap' => 'required|numeric',
        'change_kebersihan' => 'required|numeric',
        'change_kedisiplinan' => 'required|numeric',
    ]);

    // Lakukan update ke model
    $nilai = Nilai::findOrFail($this->change_id);
    $nilai->update([
        'ph1' => $this->change_ph1,
        'ph2' => $this->change_ph2,
        'uts' => $this->change_uts,
        'uas' => $this->change_uas,
        'nilai_keterampilan' => $this->change_nilai_keterampilan,
        'sikap' => $this->change_sikap,
        'kebersihan' => $this->change_kebersihan,
        'kedisiplinan' => $this->change_kedisiplinan,
    ]);

    // Setelah berhasil, beri notifikasi sukses dan reset form
    $this->dispatch('close-modal');
    $this->dispatch('notification-success');
    $this->reset();}

    public function export($ext)
    {
        abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);

        $headings = ['Kode Mapel', 'NIP', 'NISN', 'NAMA', 'PH1', 'PH2', 'UTS', 'UAS'];
        $mapping = [
            '$table->kode_mapel',
            '$table->nip',
            '$table->nisn',
            '$table->nama',
            '$table->ph1',
            '$table->ph2',
            '$table->uts',
            '$table->uas',
        ];

        // Mulai query dengan kondisi dasar
        $query = NilaiModel::join('jadwal_pelajaran as jp', 'jp.id', '=', 'nilai.id_jadwal')
                            ->join('siswa as s', 's.nisn', '=', 'nilai.nisn')
                            ->select('nilai.*', 'jp.kode_mapel as kode_mapel', 'jp.nip as nip', 's.nama');

        // Filter berdasarkan Mata Pelajaran jika ada
        if (!empty($this->kode_mapel)) {
            $query->where('jp.kode_mapel', $this->kode_mapel);
        }

        // Filter berdasarkan Kelas jika ada
        if (!empty($this->kode_kelas)) {
            $query->where('jp.kode_kelas', $this->kode_kelas);
        }

        // Filter berdasarkan Semester jika ada
        if (!empty($this->value_semester_id)) {
            $query->where('jp.semester_id', $this->value_semester_id);
        }

        // Ambil data yang sudah difilter
        $collection = $query->get();

        // Format nilai nip agar bisa dibaca Excel
        $collection = $collection->map(function ($item) {
            $item->nip = "'" . $item->nip;  // Menambahkan tanda petik agar format nip tetap benar di Excel
            return $item;
        });

        // Ekspor data dalam format yang dipilih
        return Excel::download(new ExcelExport($headings, $mapping, $collection), 'daftar-nilai.' . $ext);
    }

}
