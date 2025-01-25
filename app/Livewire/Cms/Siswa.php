<?php

namespace App\Livewire\Cms;

use App\Livewire\Forms\Cms\FormSiswa;
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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use BaseComponent;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Siswa as TraitsSiswa;

class Siswa extends BaseComponent
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
                'field' => 'siswa.nama',
            ],
            [
                'name' => 'NISN',
                'field' => 'siswa.nisn',
            ],
            [
                'name' => 'Semester',
                'field' => 'semester.nama',
            ],
            [
                'name' => 'Jenis Kelamin',
                'field' => 'siswa.jenis_kelamin',
            ],
            [
                'name' => 'Kelas',
                'field' => 'siswa.kode_kelas',
            ],
            [
                'name' => 'No. HP',
                'field' => 'siswa.no_hp',
            ],
            [
                'name' => 'Tanggal Lahir',
                'field' => 'siswa.tanggal_lahir',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'siswa.nama',
        $orderByDua = '=',
        $order = 'asc';

    public $kode_kelas = "";
    public $kelas = [];
    public $semester = [];
    public $form_semester = [];
    public $form_kelas = [];
    public $value_kelas = [];

    public $detail_nisn = "";
    public $detail_nama = "";
    public $detail_jenis_kelamin = "";
    public $detail_tanggal_lahir = "";
    public $detail_tempat_lahir = "";
    public $detail_agama = "";
    public $detail_no_hp = "";
    public $detail_email = "";
    public $detail_alamat = "";
    public $detail_nama_wali = "";
    public $detail_no_telepon_wali = "";
    public $detail_angkatan = "";
    public $detail_kode_kelas = "";
    public $detail_image = "";


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

        $this->title[] = "Daftar Siswa";
        $this->title[] = $img;


        $akses = $this->crud($this->title);
        if(session()->get('role_id') == 2){
            $this->kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                ->select('jadwal_pelajaran.kode_kelas')
                                ->groupBy('jadwal_pelajaran.kode_kelas')
                                ->get();
            $this->value_kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                ->pluck('jadwal_pelajaran.kode_kelas')
                                ->toArray();
            if($this->kode_kelas != ""){
                $model = SiswaModel::leftJoin('semester', 'semester.id', '=', 'siswa.semester_id')->where('siswa.kode_kelas', $this->kode_kelas)->select('siswa.*', 'semester.nama as nama_semester');
            }else{
                $model = SiswaModel::leftJoin('semester', 'semester.id', '=', 'siswa.semester_id')->whereIn('siswa.kode_kelas', $this->value_kelas)->select('siswa.*', 'semester.nama as nama_semester');
            }
        }else{
            $this->kelas = Kelas::get();
            $this->form_kelas = Kelas::all();
            $this->form_semester = Semester::all();
            if($this->kode_kelas != ""){
                $model = SiswaModel::leftJoin('semester', 'semester.id', '=', 'siswa.semester_id')->where('siswa.kode_kelas', $this->kode_kelas)->select('siswa.*', 'semester.nama as nama_semester');
            }else{
                $model = SiswaModel::leftJoin('semester', 'semester.id', '=', 'siswa.semester_id')->select('siswa.*', 'semester.nama as nama_semester');
            }
        }



        $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'orderByDua' => $this->orderByDua,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.siswa', compact('get'))->title($this->title);
    }

    public function detail($nisn=null){
        $get = SiswaModel::where('nisn', $nisn)->first();
        $this->detail_nisn = $get->nisn;
        $this->detail_nama = $get->nama;
        $this->detail_jenis_kelamin = $get->jenis_kelamin;
        $this->detail_tanggal_lahir = $get->tanggal_lahir;
        $this->detail_tempat_lahir = $get->tempat_lahir;
        $this->detail_agama = $get->agama;
        $this->detail_no_hp = $get->no_hp;
        $this->detail_email = $get->email;
        $this->detail_alamat = $get->alamat;
        $this->detail_nama_wali = $get->nama_wali;
        $this->detail_no_telepon_wali = $get->no_telepon_wali;
        $this->detail_angkatan = $get->angkatan;
        $this->detail_kode_kelas = $get->kode_kelas;
        $this->detail_image = $get->image;
    }

    public function saveWithUpload() {
        $this->validate([
            'form.nisn' => 'required|numeric', // Pastikan NISN unik di tabel siswa
        ]);
        $this->form->image = $this->image;
        $this->image = null;
        $this->save();
    }

    public function export($ext)
    {
        abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);

        $headings = ['NISN', 'Nama', 'Tanggal Lahir', 'Tempat Lahir', 'Jenis Kelamin', 'Agama', 'No. Telepon', 'Email', 'Alamat', 'Nama Wali', 'No. Telepon Wali', 'Angkatan', 'Kode Kelas'];
        $mapping = [
            '$table->nisn',
            '$table->nama',
            '$table->tanggal_lahir',
            '$table->tempat_lahir',
            '$table->jenis_kelamin',
            '$table->agama',
            '$table->no_hp',
            '$table->email',
            '$table->alamat',
            '$table->nama_wali',
            '$table->no_telepon_wali',
            '$table->angkatan',
            '$table->kode_kelas',
        ];

        if(session()->get('role_id') == 2 || session()->get('role_id') == 1){
            if($this->kode_kelas != ""){
                $collection = SiswaModel::where('kode_kelas', $this->kode_kelas)->get();
            }else{
                if(count($this->value_kelas) > 0){
                    $collection = SiswaModel::whereIn('kode_kelas', $this->value_kelas)->get();
                }else{
                 $collection = SiswaModel::get();
                }
            }
        }else{
            $collection = SiswaModel::get();
        }

        return Excel::download(new ExcelExport($headings, $mapping, $collection), fileName: 'daftar-siswa.' . $ext);
    }

    public function changePassword($value_nisn=null){
        $check = User::where('username', $value_nisn)->first();
        if($check == null){
            $get = SiswaModel::where('nisn', $value_nisn)->first();

            $user = User::create([
                'name' => $get->nama,
                'username' => $get->nisn,
                'email' => $get->email,
                'role_name' => "siswa",
                'password' => Hash::make(Carbon::createFromFormat('Y-m-d', $get->tanggal_lahir)->format('dmY')),
            ]);

            $user->assignRole(3);

            $getUser = User::where('username', $value_nisn)->where('email', $get->email)->first();
            $store = StoreUser::create(
                [
                    'store_id' => 1,
                    'user_id' => $getUser->id,
                    'status' => 1
                ]
            );
        }else{
            $get = SiswaModel::where('nisn', $value_nisn)->first();
            $user = User::where('username', $value_nisn)->update([
                'password' => Hash::make(Carbon::createFromFormat('Y-m-d', $get->tanggal_lahir)->format('dmY')),
            ]);
        }
        $this->dispatch('update-password', ['nisn' => $value_nisn]);
    }
}
