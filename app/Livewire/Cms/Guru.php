<?php

namespace App\Livewire\Cms;

use App\Livewire\Forms\Cms\FormGuru;
use Illuminate\Support\Facades\Session;
use App\Models\Guru as GuruModel;
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

class Guru extends BaseComponent
{
    use WithFileUploads;
    use CheckAccess;

    public FormGuru $form;
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
                'name' => 'NIP',
                'field' => 'nip',
            ],
            [
                'name' => 'Jenis Kelamin',
                'field' => 'jenis_kelamin',
            ],
            [
                'name' => 'Tanggal Lahir',
                'field' => 'tanggal_lahir',
            ],
            [
                'name' => 'No. HP',
                'field' => 'no_hp',
            ],
            [
                'name' => 'Email',
                'field' => 'email',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'nama',
        $order = 'asc';

    public $detail_nip = "";
    public $detail_nama = "";
    public $detail_jenis_kelamin = "";
    public $detail_tanggal_lahir = "";
    public $detail_tempat_lahir = "";
    public $detail_agama = "";
    public $detail_no_hp = "";
    public $detail_email = "";
    public $detail_alamat = "";
    public $detail_image = "";

    public function render()
    {
        // validasi fitur
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

        $this->title[] = "Daftar Guru";
        $this->title[] = $img;

        $akses = $this->crud($this->title);

        $get = $this->getDataWithFilter(new GuruModel, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        if ($this->paginate != null) {
            $this->resetPage();
        }


        return view('livewire.cms.guru', compact('get'))->title($this->title);
    }

    public function changePassword($value_nip=null){
        $check = User::where('username', $value_nip)->first();
        if($check == null){
            $get = GuruModel::where('nip', $value_nip)->first();

            $user = User::create([
                'name' => $get->nama,
                'username' => $get->nip,
                'email' => $get->email,
                'role_name' => "guru",
                'password' => Hash::make(Carbon::createFromFormat('Y-m-d', $get->tanggal_lahir)->format('dmY')),
            ]);
    
            $user->assignRole(2);
    
            $getUser = User::where('username', $value_nip)->where('email', $get->email)->first();
            $store = StoreUser::create(
                [
                    'store_id' => 1,
                    'user_id' => $getUser->id,
                    'status' => 1
                ]
            );
        }else{
            $get = GuruModel::where('nip', $value_nip)->first();
            $user = User::where('username', $value_nip)->update([
                'password' => Hash::make(Carbon::createFromFormat('Y-m-d', $get->tanggal_lahir)->format('dmY')),
            ]);
        }
        $this->dispatch('update-password', ['nip' => $value_nip]);   
    }

    public function detail($nip=null){
        $get = GuruModel::where('nip', $nip)->first();
        $this->detail_nip = $get->nip;
        $this->detail_nama = $get->nama;
        $this->detail_jenis_kelamin = $get->jenis_kelamin;
        $this->detail_tanggal_lahir = $get->tanggal_lahir;
        $this->detail_tempat_lahir = $get->tempat_lahir;
        $this->detail_agama = $get->agama;
        $this->detail_no_hp = $get->no_hp;
        $this->detail_email = $get->email;
        $this->detail_alamat = $get->alamat;
        $this->detail_image = $get->image;
    }
    
    public function saveWithUpload() {
        $this->form->image = $this->image;
        $this->image = null;
        $this->save();
    }

    public function export($ext) 
    {
        abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);

        $headings = ['NIP', 'Nama', 'Tanggal Lahir', 'Tempat Lahir', 'Jenis Kelamin', 'Agama', 'No. Telepon', 'Email', 'Alamat'];
        $mapping = [
            '$table->nip',
            '$table->nama',
            '$table->tanggal_lahir',
            '$table->tempat_lahir',
            '$table->jenis_kelamin',
            '$table->agama',
            '$table->no_hp',
            '$table->email',
            '$table->alamat',
        ];
        $collection = GuruModel::select('*')->get();
        $collection = $collection->map(function($item) {
            $item->nip = "'" . $item->nip;
            return $item;
        });
        return Excel::download(new ExcelExport($headings, $mapping, $collection), fileName: 'daftar-guru.' . $ext);
    }
}
