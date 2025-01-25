<?php

namespace App\Livewire\Cms;

use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Cms\FormKelas;
use Illuminate\Support\Facades\Session;
use App\Models\Kelas as KelasModel;
use App\Traits\CheckAccess;

use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use BaseComponent;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Siswa as TraitsSiswa;

class Kelas extends BaseComponent
{
    use CheckAccess;

    public FormKelas $form;
    public Session $session;

    public $title = [];
    public $guru = [];

    public $searchBy = [
            [
                'name' => 'Wali Kelas',
                'field' => 'nip',
            ],
            [
                'name' => 'Kode Kelas',
                'field' => 'kode_kelas',
            ],
            [
                'name' => 'Tingkat Kelas',
                'field' => 'tingkat_kelas',
            ],
            [
                'name' => 'Nama',
                'field' => 'nama',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'nama',
        $order = 'asc';


    public $kelas = [];

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

        $this->title[] = "Daftar Kelas";
        $this->title[] = $img;

        // validasi fitur
        $akses = $this->crud($this->title);
        $this->guru = TraitsGuru::select('nip', 'nama')->get();
        $get = $this->getDataWithFilter(new KelasModel, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.kelas', compact('get'))->title($this->title);
    }

    public function export($ext) 
    {
        abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);

        $headings = ['Wali Kelas', 'Kode Kelas', 'Tingkat', 'Nama'];
        $mapping = [
            '$table->nip',
            '$table->kode_kelas',
            '$table->tingkat_kelas',
            '$table->nama',
        ];
        $collection = KelasModel::all();
        return Excel::download(new ExcelExport($headings, $mapping, $collection), fileName: 'daftar-kelas.' . $ext);
    }
}
