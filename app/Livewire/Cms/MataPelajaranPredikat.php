<?php

namespace App\Livewire\Cms;

use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Cms\FormMataPelajaranPredikat;
use Illuminate\Support\Facades\Session;
use App\Models\MataPelajaranPredikat as MataPelajaranPredikatModel;
use App\Models\MataPelajaran;
use App\Traits\CheckAccess;

use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use BaseComponent;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Siswa as TraitsSiswa;

class MataPelajaranPredikat extends BaseComponent
{
    use CheckAccess;

    public FormMataPelajaranPredikat $form;
    public Session $session;

    public $title = [];

    public $searchBy = [
            [
                'name' => 'Predikat',
                'field' => 'predikat',
            ],
            [
                'name' => 'Keterangan',
                'field' => 'keterangan',
            ],
            [
                'name' => 'Min. Nilai',
                'field' => 'nilai_min',
            ],
            [
                'name' => 'Max. Nilai',
                'field' => 'nilai_max',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'predikat',
        $order = 'asc';
    
    public function mount()
    {
        $id_mapel = request()->query('id');
        $this->id_mata_pelajaran = request()->query('id');
        if (is_null($id_mapel) || $id_mapel == '') {
            // Redirect if 'id' is not present in the query string
            return redirect()->route('cms.mata-pelajaran');
        }
    }
    
    public function render()
    {
        $id_mapel = request()->query('id');

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

        $this->title[] = "Daftar Predikat";
        $this->title[] = $img;
        // validasi fitur
        $akses = $this->crud($this->title);
        
        $model = MataPelajaranPredikatModel::where('id_mata_pelajaran', $this->id_mata_pelajaran);
        $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.mata-pelajaran-predikat', compact('get', 'id_mapel'))->title($this->title);
    }

}
