<?php

namespace App\Livewire\Cms;

use Illuminate\Support\Facades\DB;
// use App\Livewire\Forms\Cms\FormNilai;
use Illuminate\Support\Facades\Session;
use App\Models\Nilai as NilaiModel;
use App\Models\Siswa;
use App\Models\Semester;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Traits\CheckAccess;

use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Http\Request;

use BaseComponent;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Siswa as TraitsSiswa;

class Nilai extends BaseComponent
{
    use CheckAccess;

    // public FormNilai $form;
    public Session $session;

    public $title = [];

    public $searchBy = [
            [
                'name' => 'Kelas',
                'field' => 'kelas.nama',
            ],
            [
                'name' => 'Nama',
                'field' => 's.nama',
            ],
            [
                'name' => 'NISN',
                'field' => 's.nisn',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 's.nama',
        $order = 'asc';
    
    public $searchBySiswa = [
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
                'name' => 'Kedisiplinan',
                'field' => 'nilai.kedisiplinan',
            ],
            [
                'name' => 'Kebersihan',
                'field' => 'nilai.kebersihan',
            ],

        ],
        $isUpdateSiswa = false,
        $searchSiswa = '',
        $paginateSiswa = 10,
        $orderBySiswa = 'nilai.id',
        $orderSiswa = 'asc';

    public $searchByDetail = [
            [
                'name' => 'Mata Pelajaran',
                'field' => 'mata_pelajaran.nama',
            ],
            [
                'name' => 'PH1',
                'field' => 'nilai.ph1',
            ],
            [
                'name' => 'PH2',
                'field' => 'nilai.ph2',
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
                'name' => 'Kedisiplinan',
                'field' => 'nilai.kedisiplinan',
            ],

            [
                'name' => 'Kebersihan',
                'field' => 'nilai.kebersihan',
            ],

            [
                'name' => 'Nilai UTS',
                'field' => 'uts',
            ],
            [
                'name' => 'Nilai UAS',
                'field' => 'uas',
            ],
            
        ],
        $isUpdateDetail = false,
        $searchDetail = '',
        $paginateDetail = 14,
        $orderByDetail = 'mata_pelajaran.nama',
        $orderDetail = 'asc';

    public $kode_kelas = "";
    public $kode_mapel = "";
    public $semester_id = "";
    public $kelas = [];
    public $semester = [];
    public $mata_pelajaran = [];
    public $nilai_keterampilan = [];
    public $kebersihan = [];
    public $sikap = [];
    public $kedisiplinan = [];

    public $head_kode_kelas = "";
    public $head_nisn = "";
    public $head_nama = "";

    public $nisn = "";

    public $change_kode_mapel = "";
    public $change_kode_kelas = "";
    public $change_nisn = "";
    public $change_nama = "";
    // public $change_ph1 = "";
    // public $change_ph2 = "";
    // public $change_uts = "";
    // public $change_uas = "";
    public $change_id = "";

    public $change_ph1 = [];
    public $change_ph2 = [];
    public $change_uts = [];
    public $change_uas = [];
    public $change_sikap = [];
    public $change_kebersihan = [];
    public $change_kedisiplinan = [];
    public $change_nilai_keterampilan = [];

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
        if(session()->get('role_id') == 3){
            $getSiswa = Siswa::where('nisn', session()->get('store_username'))->first();
            $valueKodeKelas = $getSiswa->kode_kelas;
            $valueSemester = $getSiswa->semester_id;
            $this->mata_pelajaran = Jadwal::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                ->where('jadwal_pelajaran.kode_kelas', $valueKodeKelas)
                                ->select('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                ->groupBy('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                ->get();

            $model = NilaiModel::join('jadwal_pelajaran', 'jadwal_pelajaran.id', '=', 'nilai.id_jadwal')
                              ->join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                              ->join('kelas', 'kelas.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                              ->join('siswa', 'siswa.nisn', '=', 'nilai.nisn')
                              ->where('siswa.nisn', session()->get('store_username'));

            if($this->kode_mapel != ""){
                $model = $model->where('jadwal_pelajaran.kode_mapel', $this->kode_mapel);
            }

            if($this->kode_kelas != ""){
                $model = $model->where('jadwal_pelajaran.kode_kelas', $this->kode_kelas);
            }

            if($this->semester_id != ""){
                $model = $model->where('nilai.semester_id', $this->semester_id);
            }else{
                $model = $model->where('nilai.semester_id', $valueSemester);
            }

            $model = $model->select('mata_pelajaran.nama as mapel','kelas.nama as kelas','siswa.nama', 'nilai.id', 'nilai.nisn', 'nilai.ph1', 'nilai.ph2', 'nilai.uts', 'nilai.uas','nilai.nilai_keterampilan','nilai.sikap','nilai.kebersihan','nilai.kedisiplinan', 'nilai.created_at', 'nilai.updated_at');

            $get = $this->getDataWithFilter($model, [
                'orderBy' => $this->orderBySiswa,
                'order' => $this->orderSiswa,
                'paginate' => $this->paginateSiswa,
                's' => $this->searchSiswa,
            ], $this->searchBySiswa);

            if ($this->searchSiswa != null) {
                $this->resetPage();
            }

            return view('livewire.cms.nilai-siswa', compact('get'))->title($this->title);
        }else{
            // $this->kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
            //                     ->select('jadwal_pelajaran.kode_kelas')
            //                     ->groupBy('jadwal_pelajaran.kode_kelas')
            //                     ->get();
            $this->kelas = Kelas::get();
                                
            if($this->kode_kelas != ""){
                $model = Kelas::join('siswa as s', 's.kode_kelas', '=', 'kelas.kode_kelas')->select('s.nisn', 's.nama', 'kelas.nama as kelas', 'kelas.kode_kelas', 'kelas.created_at', 'kelas.updated_at', 's.id')->where('kelas.kode_kelas', $this->kode_kelas);
            }else{
                $model = Kelas::join('siswa as s', 's.kode_kelas', '=', 'kelas.kode_kelas')->select('s.nisn', 's.nama', 'kelas.nama as kelas', 'kelas.kode_kelas', 'kelas.created_at', 'kelas.updated_at', 's.id');
            }

            if($this->semester_id != ""){
                $model = $model->where('s.semester_id', $this->semester_id);
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
    
            if($this->nisn == ""){
                $model_detail = NilaiModel::join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'nilai.kode_mapel')->where('nilai.nisn', 0);
            }else{
                $model_detail = NilaiModel::join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'nilai.kode_mapel')->where('nilai.nisn', $this->nisn);
            }
    
            $selectFieldsDetail = array_map(function($field) {
                return $field['field'];
            }, $this->searchByDetail);
    
            $selectFieldsDetail[] = 'nilai.id';
            $selectFieldsDetail[] = 'nilai.created_at';
            $selectFieldsDetail[] = 'nilai.updated_at';
            
            
            $model_detail = $model_detail->select($selectFieldsDetail);
    
            $get_detail = $this->getDataWithFilterDetail($model_detail, [
                'orderBy' => $this->orderByDetail,
                'order' => $this->orderDetail,
                'paginate' => $this->paginateDetail,
                's' => $this->searchDetail,
            ], $this->searchByDetail);
    
            if ($this->searchDetail != null) {
                $this->resetPage();
            }
    
            return view('livewire.cms.nilai', compact('get', 'get_detail'))->title($this->title);
        }

        
    }

    public function detail($nisn=null){
        $this->nisn = $nisn;
        $getDetail = Siswa::where('nisn', $nisn)->first();
        $this->head_nisn = $getDetail->nisn;
        $this->head_nama = $getDetail->nama;
        $this->head_kode_kelas = $getDetail->kode_kelas;
    }

    public function change($nisn){
        $this->nisn = $nisn;
        $getDetail = Siswa::where('nisn', $nisn)->first();
        $this->head_nisn = $getDetail->nisn;
        $this->head_nama = $getDetail->nama;
        $this->head_kode_kelas = $getDetail->kode_kelas;
    }

    public function savePh1($id, $nilai)
    {
        if($nilai >= 1){
            NilaiModel::where('id', $id)->update([
                'ph1' => $nilai,
            ]);
            $this->dispatch('notification-success');
        }else{
            
        }
    }

    public function savePh2($id, $nilai)
    {
        if($nilai >= 1){
            NilaiModel::where('id', $id)->update([
                'ph2' => $nilai,
            ]);
            $this->dispatch('notification-success');
        }else{
            
        }
    }

    public function saveUts($id, $nilai)
    {
        if($nilai >= 1){
            NilaiModel::where('id', $id)->update([
                'uts' => $nilai,
            ]);
            $this->dispatch('notification-success');
        }else{
            
        }
    }

    public function saveUas($id, $nilai)
    {
        if($nilai >= 1){
            NilaiModel::where('id', $id)->update([
                'uas' => $nilai,
            ]);
            $this->dispatch('notification-success');
        }else{
            
        }
    }
    public function saveKeterampilan($id, $nilai)
    {
        if($nilai >= 1){
            NilaiModel::where('id', $id)->update([
                'nilai_keterampilan' => $nilai,
            ]);
            $this->dispatch('notification-success');
        }
    }
    public function saveKebersihan($id, $nilai)
    {
        if($nilai >= 1){
            NilaiModel::where('id', $id)->update([
                'kebersihan' => $nilai,
            ]);
            $this->dispatch('notification-success');
        }
    }
    public function saveKedisiplinan($id, $nilai)
    {
        if($nilai >= 1){
            NilaiModel::where('id', $id)->update([
                'kedisiplinan' => $nilai,
            ]);
            $this->dispatch('notification-success');
        }
    }
    
    public function saveSikap($id, $nilai)
    {
        if($nilai >= 1){
            NilaiModel::where('id', $id)->update([
                'sikap' => $nilai,
            ]);
            $this->dispatch('notification-success');
        }
    }
    
    
    public function changeSubmit(Request $request){
        dd($request->input());
        NilaiModel::where('id', $this->change_id)->update([
            'ph1' => $this->change_ph1,
            'ph1' => $this->change_ph2,
            'uts' => $this->change_uts,
            'uas' => $this->change_uas,
            'uas' => $this->change_uas,
            'nilai_keterampilan' => $this->change_nilai_keterampilan,   // tambah ini
            'sikap' => $this->change_sikap,                         // tambah ini
            'kebersihan' => $this->change_kebersihan,               // tambah ini
            'kedisiplinan' => $this->change_kedisiplinan, 
        ]);
        $this->dispatch('notification-success');
        $this->dispatch('closeModal', modal: 'change');
    }

    public function export($ext) 
    {
        abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);

        $headings = ['Kode Kelas', 'Kode Mapel', 'NIP', 'NISN', 'PH1', 'PH2', 'UTS', 'UAS','Keterampilan','Sikap','Kebersihan','Kedisiplinan'];
        $mapping = [
            '$table->kode_kelas',
            '$table->kode_mapel',
            '$table->nip',
            '$table->nisn',
            '$table->ph1',
            '$table->ph2',
            '$table->uts',
            '$table->uas',
            '$table->nilai_keterampilan',
            '$table->sikap',
            '$table->kebersihan',
            '$table->kedisiplinan',

        ];

        if (session()->get('role_id') == 1) {
            if ($this->kode_kelas != "") {
                $collection = NilaiModel::leftJoin('jadwal_pelajaran as jp', 'jp.id', '=', 'nilai.id_jadwal')
                    ->where('jp.kode_kelas', $this->kode_kelas)
                    ->select('nilai.*', 'jp.kode_mapel as kode_mapel', 'jp.nip as nip', 'jp.kode_kelas as kode_kelas')
                    ->get();        
            } else {
                $collection = NilaiModel::leftJoin('jadwal_pelajaran as jp', 'jp.id', '=', 'nilai.id_jadwal')
                    ->select('nilai.*', 'jp.kode_mapel as kode_mapel', 'jp.nip as nip', 'jp.kode_kelas as kode_kelas')
                    ->get();
            }
        
        }else if(session()->get('role_id') == 2){
            if($this->kode_kelas != ""){
                $collection = NilaiModel::leftJoin('jadwal_pelajaran as jp', 'jp.id', '=', 'nilai.id_jadwal')->where('jp.kode_kelas', $this->kode_kelas)->select('nilai.*', 'jp.kode_mapel as kode_mapel', 'jp.nip as nip', 'jp.kode_kelas')->get();        
            }else{
                $collection = NilaiModel::leftJoin('jadwal_pelajaran as jp', 'jp.id', '=', 'nilai.id_jadwal')->select('nilai.*', 'jp.kode_mapel as kode_mapel', 'jp.nip as nip')->get();
            }
        }else if(session()->get('role_id') == 3){
            $getSiswa = Siswa::where('nisn', session()->get('store_username'))->first();
            $valueKodeKelas = $getSiswa->kode_kelas;
            $collection = NilaiModel::leftJoin('jadwal_pelajaran as jp', 'jp.id', '=', 'nilai.id_jadwal')->where('nilai.nisn', session()->get('store_username'))->where('jp.kode_kelas', $valueKodeKelas)->select('nilai.*', 'jp.kode_mapel as kode_mapel', 'jp.nip as nip', 'jp.kode_kelas as kode_kelas')->get();        
        }
        
        return Excel::download(new ExcelExport($headings, $mapping, $collection), fileName: 'daftar-nilai.' . $ext);
    }
}
