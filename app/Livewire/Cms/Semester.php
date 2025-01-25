<?php

namespace App\Livewire\Cms;

use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Cms\FormSemester;
use Illuminate\Support\Facades\Session;
use App\Models\Semester as SemesterModel;
use App\Traits\CheckAccess;

use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use BaseComponent;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Siswa as TraitsSiswa;

class Semester extends BaseComponent
{
    use CheckAccess;

    public FormSemester $form;
    public Session $session;

    public $title = [];

    public $searchBy = [
            [
                'name' => 'Nama',
                'field' => 'nama',
            ]
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'nama',
        $order = 'asc';


    public $Semester = [];

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

        $this->title[] = "Daftar Semester";
        $this->title[] = $img;

        // validasi fitur
        $akses = $this->crud($this->title);

        $get = $this->getDataWithFilter(new SemesterModel, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.semester', compact('get'))->title($this->title);
    }

    public function export($ext) 
    {
        abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);

        $headings = ['Nama'];
        $mapping = [
            '$table->nama'
        ];
        $collection = SemesterModel::all();
        return Excel::download(new ExcelExport($headings, $mapping, $collection), fileName: 'daftar-Semester.' . $ext);
    }
}
