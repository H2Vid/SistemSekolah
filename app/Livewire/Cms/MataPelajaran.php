<?php

namespace App\Livewire\Cms;

use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Cms\FormMataPelajaran;
use Illuminate\Support\Facades\Session;
use App\Models\MataPelajaran as MataPelajaranModel;
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

class MataPelajaran extends BaseComponent
{
    use CheckAccess;

    public FormMataPelajaran $form;
    public Session $session;

    public $title = [];

    public $searchBy = [
            [
                'name' => 'Kode Mata Pelajaran',
                'field' => 'kode_mapel',
            ],
            [
                'name' => 'Tingkat Kelas',
                'field' => 'tingkat_kelas',
            ],
            [
                'name' => 'Nama',
                'field' => 'nama',
            ],
            [
                'name' => 'Nilai KKM',
                'field' => 'nilai_kkm',
            ],
            [
                'name' => 'Kategori',
                'field' => 'kategori',
            ],

        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'nama',
        $order = 'asc';


    public $kelas = [];
    public $kategori;
    public $kategoriOptions = ['Umum', 'Dasar Program Keahlian', 'Kompetensi Kejuruan'];
    
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

        $this->title[] = "Daftar Mata Pelajaran";
        $this->title[] = $img;
        // validasi fitur
        $akses = $this->crud($this->title);
        $this->kelas = Kelas::select('tingkat_kelas', DB::raw('COUNT(*) as count'))->groupBy('tingkat_kelas')->get();

        $get = $this->getDataWithFilter(new MataPelajaranModel, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.mata-pelajaran', compact('get'))->title($this->title);
    }

    public function export($ext) 
    {
        abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);

        $headings = ['Kode Mapel', 'Tingkat Kelas', 'Mata Pelajaran','kategori'];
        $mapping = [
            '$table->kode_mapel',
            '$table->tingkat_kelas',
            '$table->nama',
            '$table->kategori',
        ];
        $collection = MataPelajaranModel::all();
        return Excel::download(new ExcelExport($headings, $mapping, $collection), fileName: 'daftar-mata-pelajaran.' . $ext);
    }

}
