<?php

namespace App\Livewire\Cms;

use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Cms\FormModul;
use Illuminate\Support\Facades\Session;
use App\Models\Modul as ModulModel;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jadwal;

use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use Livewire\WithFileUploads;
use App\Traits\CheckAccess;
use BaseComponent;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Siswa as TraitsSiswa;

class Modul extends BaseComponent
{
    use WithFileUploads;
    use CheckAccess;

    public FormModul $form;
    public Session $session;

    #[Validate('nullable|mimes:pdf|max:2048')]
    public $file_upload;

    public $title = [];

    public $searchBy = [
            [
                'name' => 'Mata Pelajaran',
                'field' => 'mp.nama',
            ],
            [
                'name' => 'Kelas',
                'field' => 'k.nama',
            ],
            [
                'name' => 'Nama Modul',
                'field' => 'modul.nama_file',
            ],
            [
                'name' => 'Tanggal Upload',
                'field' => 'modul.tanggal_upload',
            ],
            [
                'name' => 'File',
                'field' => 'modul.file_upload',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'mp.nama',
        $order = 'asc';
    
    public $kode_mapel = "";
    public $kode_kelas = "";

    public $mata_pelajaran = [];
    public $value_kelas = [];
    public $kelas = [];
    public $list_jadwal = [];

        
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

        $this->title[] = "Data Modul";
        $this->title[] = $img;

        // validasi fitur
        $akses = $this->crud($this->title);

        $model = ModulModel::join('jadwal_pelajaran as jp', 'jp.id', '=', 'modul.id_jadwal')
                            ->join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jp.kode_mapel')
                            ->join('kelas as k', 'k.kode_kelas', '=', 'jp.kode_kelas');

        if(session()->get('role_id') == 1){
            $this->kelas = Kelas::select('kode_kelas', DB::raw('COUNT(*) as count'))->groupBy('kode_kelas')->get();

            $this->mata_pelajaran = Jadwal::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                        ->select('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                        ->groupBy('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                        ->get();
            $this->list_jadwal = Jadwal::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                        ->join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                        ->select('jadwal_pelajaran.id', 'jadwal_pelajaran.kode_mapel', 'mp.nama', 'k.nama as kelas')
                                        ->groupBy('jadwal_pelajaran.id', 'jadwal_pelajaran.kode_mapel', 'mp.nama', 'k.nama')
                                        ->get();
        }else if(session()->get('role_id') == 2){
            $this->kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                            ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                            ->select('jadwal_pelajaran.kode_kelas')
                            ->groupBy('jadwal_pelajaran.kode_kelas')
                            ->get();
            $this->value_kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                        ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                        ->pluck('jadwal_pelajaran.kode_kelas') // Use pluck to get an array of kode_kelas values
                        ->toArray();
                        
            $this->mata_pelajaran = Jadwal::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                        ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                        ->select('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                        ->groupBy('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                        ->get();

            $this->list_jadwal = Jadwal::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                        ->join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                        ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                        ->select('jadwal_pelajaran.id', 'jadwal_pelajaran.kode_mapel', 'mp.nama', 'k.nama as kelas')
                                        ->groupBy('jadwal_pelajaran.id', 'jadwal_pelajaran.kode_mapel', 'mp.nama', 'k.nama')
                                        ->get();
            $model = $model->whereIn('k.kode_kelas', $this->value_kelas);
        }else if(session()->get('role_id') == 3){
            $getSiswa = Siswa::where('nisn', session()->get('store_username'))->first();
            $valueKodeKelas = $getSiswa->kode_kelas;

            $this->mata_pelajaran = Jadwal::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                        ->join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                        ->where('k.kode_kelas', $valueKodeKelas)
                                        ->select('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                        ->groupBy('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                        ->get();

            $this->list_jadwal = Jadwal::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                        ->join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                        ->where('k.kode_kelas', $valueKodeKelas)
                                        ->select('jadwal_pelajaran.id', 'jadwal_pelajaran.kode_mapel', 'mp.nama', 'k.nama as kelas')
                                        ->groupBy('jadwal_pelajaran.id', 'jadwal_pelajaran.kode_mapel', 'mp.nama', 'k.nama')
                                        ->get();

            $model = $model->where('k.kode_kelas', $valueKodeKelas);
        }
        
        
       
        
        if($this->kode_mapel != ""){
            $model = $model->where('mp.kode_mapel', $this->kode_mapel);
        }

        if($this->kode_kelas != ""){
            $model = $model->where('k.kode_kelas', $this->kode_kelas);
        }

        $model = $model->select('mp.nama as mapel', 'k.nama as kelas', 'modul.id', 'modul.kode_mapel', 'modul.nama_file', 'modul.tanggal_upload', 'modul.file_upload', 'modul.created_at', 'modul.updated_at');

        $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.modul', compact('get'))->title($this->title);
    }

    public function changeMataPelajaran($value_kode_mapel=null){
        if(session()->get('role_id') == 1){
            $this->kelas = Jadwal::join('kelas as kl', 'kl.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')->where('jadwal_pelajaran.kode_mapel', $value_kode_mapel)->select('kl.kode_kelas', 'kl.nama')->groupBy('kl.kode_kelas', 'kl.nama')->get();
        }else if(session()->get('role_id') == 2){
            $this->kelas = Jadwal::join('kelas as kl', 'kl.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')->where('jadwal_pelajaran.kode_mapel', $value_kode_mapel)->where('nip', session()->get('store_username'))->select('kl.kode_kelas', 'kl.nama')->groupBy('kl.kode_kelas', 'kl.nama')->get();
        }
    }

    public function saveWithUpload() {
        $this->form->file_upload = $this->file_upload;
        $this->file_upload = null;
        $this->save();
    }

    public function export($ext) 
    {
        abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);

        $headings = ['Kode Mapel', 'Nama File', 'File Upload', 'Tanggal Upload'];
        $mapping = [
            '$table->kode_mapel',
            '$table->nama_file',
            '$table->file_upload',
            '$table->tanggal_upload',
        ];

        if(session()->get('role_id') == 2){
            if($this->kode_mapel != ""){
                $collection = ModulModel::join('jadwal_pelajaran as jp', 'jp.id', '=', 'modul.id_jadwal')->where('modul.kode_mapel', $this->kode_mapel)->where('jp.nip', session()->get('store_username'))->select('modul.*', 'jp.kode_mapel as kode_mapel')->get();
            }else{
                $collection = ModulModel::join('jadwal_pelajaran as jp', 'jp.id', '=', 'modul.id_jadwal')->where('jp.nip', session()->get('store_username'))->select('modul.*', 'jp.kode_mapel as kode_mapel')->get();
            }
        }else if(session()->get('role_id') == 3){
            $getSiswa = Siswa::where('nisn', session()->get('store_username'))->first();
            $valueKodeKelas = $getSiswa->kode_kelas;
            if($this->kode_mapel != ""){
                $collection = ModulModel::join('jadwal_pelajaran as jp', 'jp.id', '=', 'modul.id_jadwal')->where('jp.kode_kelas', $valueKodeKelas)->where('modul.kode_mapel', $this->kode_mapel)->select('modul.*', 'jp.kode_mapel as kode_mapel')->get();
            }else{
                $collection = ModulModel::join('jadwal_pelajaran as jp', 'jp.id', '=', 'modul.id_jadwal')->where('jp.kode_kelas', $valueKodeKelas)->select('modul.*', 'jp.kode_mapel as kode_mapel')->get();
            }
        }else{
            
            if($this->kode_mapel != ""){
                $collection = ModulModel::join('jadwal_pelajaran as jp', 'jp.id', '=', 'modul.id_jadwal')->where('modul.kode_mapel', $this->kode_mapel)->select('modul.*', 'jp.kode_mapel as kode_mapel')->get();
            }else{
                $collection = ModulModel::join('jadwal_pelajaran as jp', 'jp.id', '=', 'modul.id_jadwal')->select('modul.*', 'jp.kode_mapel as kode_mapel')->get();
            }
        }

        // dd($collection);
        return Excel::download(new ExcelExport($headings, $mapping, $collection), fileName: 'daftar-modul.' . $ext);
    }
}
