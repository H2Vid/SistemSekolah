<?php

namespace App\Livewire\Cms;

use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Cms\FormAbsensi;
use Illuminate\Support\Facades\Session;
use App\Models\Absensi as AbsensiModel;
use App\Models\Jadwal;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Traits\CheckAccess;

use BaseComponent;

use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Siswa as TraitsSiswa;

class Absensi extends BaseComponent
{
    use CheckAccess;

    public FormAbsensi $form;
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
                'name' => 'Pertemuan Ke',
                'field' => 'absensi.pertemuan_ke',
            ],
            [
                'name' => 'Nama Siswa',
                'field' => 'siswa.nama',
            ],
            [
                'name' => 'Keterangan',
                'field' => 'absensi.keterangan',
            ],
            [
                'name' => 'Waktu Absensi',
                'field' => 'absensi.created_at',
            ],
            [
                'name' => 'Semester',
                'field' => 'semester.nama',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'siswa.nama',
        $orderByDua = 'kelas.nama',
        $orderByTiga = 'absensi.pertemuan_ke',
        $order = 'asc';


    public $kelas = [];
    public $semester = [];
    public $value_kelas = [];
    public $input_kelas = [];
    public $mata_pelajaran = [];
    public $kode_kelas = "";
    public $kode_mapel = "";
    public $pertemuan_ke = "";
    public $semester_id = "";

    public $value_kode_mapel = "";
    public $value_kode_kelas = "";
    public $value_semester_id = "";
    public $value_pertemuan_ke = "";
public $value_nisn='';
    public $list_siswa = [];
    public $keterangan = [];

    public $change_kode_mapel = "";
    public $change_kode_kelas = "";
    public $change_nisn = "";
    public $change_nama = "";
    public $change_pertemuan_ke = "";
    public $change_keterangan = "";
    public $change_semester = "";
    public $change_id = "";

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

        $this->title[] = "Absensi";
        $this->title[] = $img;

        // validasi fitur
        $akses = $this->crud($this->title);
        $this->semester = Semester::get();
        if(session()->get('role_id') == 3){
            $getSiswa = Siswa::where('nisn', session()->get('store_username'))->first();
            $valueKodeKelas = $getSiswa->kode_kelas;
            $valueSemester = $getSiswa->semester_id;


            $this->kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->where('jadwal_pelajaran.kode_kelas', $valueKodeKelas)
                                ->select('jadwal_pelajaran.kode_kelas')
                                ->groupBy('jadwal_pelajaran.kode_kelas')
                                ->get();
            $this->mata_pelajaran = Jadwal::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                            ->where('jadwal_pelajaran.kode_kelas', $valueKodeKelas)
                                            ->select('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                            ->groupBy('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                            ->get();
            $model = AbsensiModel::join('jadwal_pelajaran', 'jadwal_pelajaran.id', '=', 'absensi.id_jadwal')
                              ->join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                              ->join('kelas', 'kelas.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                              ->join('siswa', 'siswa.nisn', '=', 'absensi.nisn')
                              ->leftJoin('semester', 'semester.id', '=', 'absensi.semester_id')
                              ->where('siswa.nisn', session()->get('store_username'));

            if($this->semester_id != ""){
                $model = $model->where('absensi.semester_id', $this->semester_id);
            }else{
                $model = $model->where('absensi.semester_id', $valueSemester);
            }
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
            $model = AbsensiModel::join('jadwal_pelajaran', 'jadwal_pelajaran.id', '=', 'absensi.id_jadwal')
                              ->join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                              ->join('kelas', 'kelas.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                              ->join('siswa', 'siswa.nisn', '=', 'absensi.nisn')
                              ->leftJoin('semester', 'semester.id', '=', 'absensi.semester_id')
                              ->whereIn('siswa.kode_kelas', $this->value_kelas)
                              ->where('jadwal_pelajaran.nip', session()->get('store_username'));
            if($this->semester_id != ""){
                $model = $model->where('absensi.semester_id', $this->semester_id);
            }
        }else{
            $this->kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->select('jadwal_pelajaran.kode_kelas')
                                ->groupBy('jadwal_pelajaran.kode_kelas')
                                ->get();
            $this->value_kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->pluck('jadwal_pelajaran.kode_kelas') // Use pluck to get an array of kode_kelas values
                                ->toArray();
            $this->mata_pelajaran = Jadwal::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                                        ->select('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                        ->groupBy('jadwal_pelajaran.kode_mapel', 'mp.nama')
                                        ->get();
            $model = AbsensiModel::join('jadwal_pelajaran', 'jadwal_pelajaran.id', '=', 'absensi.id_jadwal')
                              ->join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                              ->join('kelas', 'kelas.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                              ->join('siswa', 'siswa.nisn', '=', 'absensi.nisn')
                              ->leftJoin('semester', 'semester.id', '=', 'absensi.semester_id');
            if($this->semester_id != ""){
                $model = $model->where('absensi.semester_id', $this->semester_id);
            }
        }


        if($this->kode_mapel != ""){
            $model = $model->where('jadwal_pelajaran.kode_mapel', $this->kode_mapel);
        }

        if($this->kode_kelas != ""){
            $model = $model->where('jadwal_pelajaran.kode_kelas', $this->kode_kelas);
        }

        if($this->pertemuan_ke != ""){
            $model = $model->where('absensi.pertemuan_ke', $this->pertemuan_ke);
        }

        $model = $model->select('semester.nama as nama_semester','mata_pelajaran.nama as mapel','kelas.nama as kelas','siswa.nama', 'absensi.id', 'absensi.pertemuan_ke', 'absensi.keterangan','absensi.created_at','absensi.updated_at');

        $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'orderByDua' => $this->orderByDua,
            'orderByTiga' => $this->orderByTiga,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.absensi', compact('get'))->title($this->title);
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

        // $this->list_siswa = Siswa::where('kode_kelas', $value_kode_kelas)->select('nama')->get();
    }

    public function changeSemester($value_semester_id=null){
        $this->value_kode_kelas = $this->value_kode_kelas;
        if(session()->get('role_id') == 1){
            $this->input_kelas = Jadwal::where('kode_mapel', $this->value_kode_mapel)->select('kode_kelas', DB::raw('COUNT(*) as count'))->groupBy('kode_kelas')->get();
        }else{
            $this->input_kelas = Jadwal::where('kode_mapel', $this->value_kode_mapel)->where('nip', session()->get('store_username'))->select('kode_kelas', DB::raw('COUNT(*) as count'))->groupBy('kode_kelas')->get();
        }
        $this->value_semester_id = $value_semester_id;
    }

    public function changePertemuan($value_pertemuan_ke = null)
    {
        // Mengatur semester_id yang dipilih
        $this->value_semester_id = $value_pertemuan_ke; // Pastikan value_pertemuan mengandung semester_id yang sesuai

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
            $this->dispatch('notification-failed');
            return;
        }

        // Mengecek apakah absensi sudah ada untuk pertemuan tertentu dan semester siswa
        $cekAbsensi = AbsensiModel::join('jadwal_pelajaran as j', 'j.id', '=', 'absensi.id_jadwal')
                                  ->join('siswa as s', 's.nisn', '=', 'absensi.nisn')
                                  ->where('j.kode_mapel', $this->value_kode_mapel)
                                  ->where('absensi.nisn', $this->value_nisn) // Pastikan nisn sesuai dengan yang dipilih
                                  ->where('absensi.pertemuan_ke', $value_pertemuan_ke)
                                  ->where('absensi.semester_id', $this->value_semester_id)
                                  ->get();

        // Mendapatkan daftar siswa yang sudah diabsensi pada pertemuan dan semester ini
        $siswaDiabsensi = AbsensiModel::join('siswa', 'siswa.nisn', '=', 'absensi.nisn')
                                      ->where('absensi.pertemuan_ke', $value_pertemuan_ke)
                                      ->where('absensi.semester_id', $this->value_semester_id)
                                      ->where('absensi.kode_mapel', $this->value_kode_mapel)
                                      ->select('siswa.nisn', 'siswa.id', 'siswa.nama')
                                      ->get()
                                      ->pluck('nisn'); // Mengambil nisn siswa yang sudah diabsensi

        // Menampilkan daftar siswa yang belum diabsensi pada semester yang benar
        $listSiswa = Siswa::where('kode_kelas', $this->value_kode_kelas)
                         ->where('semester_id', $this->value_semester_id)
                         ->whereNotIn('nisn', $siswaDiabsensi) // Menghindari siswa yang sudah diabsensi
                         ->select('nama', 'nisn', 'id')
                         ->orderBy('nama', 'asc')
                         ->get();

        // Jika absensi sudah ada untuk pertemuan dan semester yang relevan, tampilkan daftar siswa
        if ($cekAbsensi->isNotEmpty()) {
            $this->list_siswa = $listSiswa;
            $this->siswa_absensi = $cekAbsensi; // Menyimpan siswa yang sudah diabsensi untuk memungkinkan perubahan keterangan
        } else {
            // Jika belum ada absensi, tampilkan semua siswa yang sesuai dengan semester
            $this->list_siswa = $listSiswa;
        }
    }

    public function simpan(){
        date_default_timezone_set('Asia/Jakarta');
        $siswa = Siswa::where('kode_kelas', $this->value_kode_kelas)->where('semester_id', $this->value_semester_id)->select('nisn', 'id')->get();
        $getJadwal = Jadwal::where('kode_kelas', $this->value_kode_kelas)->where('semester_id', $this->value_semester_id)->where('kode_mapel', $this->value_kode_mapel)->select('id')->first();
        // dd($siswa, $getJadwal);
        foreach ($siswa as $l) {
            $keterangan = isset($this->keterangan[$l->id]) ? $this->keterangan[$l->id] : 'Hadir';

            if ($keterangan == null || $keterangan == '') {
                $keterangan = 'Hadir';
            }
            AbsensiModel::create([
                'id_jadwal' => $getJadwal->id,
                'pertemuan_ke' => $this->value_pertemuan_ke,
                'kode_mapel' => $this->value_kode_mapel,
                'semester_id' => $this->value_semester_id,
                'nisn' => $l->nisn,
                'keterangan' => $keterangan,
                'tanggal' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        $this->value_kode_kelas = "";
        $this->value_kode_mapel = "";
        $this->value_pertemuan_ke = "";
        $this->value_semester_id = "";
        $this->list_siswa = [];
        $this->dispatch('notification-success');
        $this->dispatch('closeModal', modal: 'mazer-modal');
    }

    public function change($id){
        $get = AbsensiModel::leftJoin('semester', 'semester.id', '=', 'absensi.semester_id')->join('jadwal_pelajaran as j', 'j.id', '=', 'absensi.id_jadwal')->join('siswa as s', 's.nisn', '=', 'absensi.nisn')->where('absensi.id', $id)->select('absensi.*', 's.nisn', 's.nama', 'j.kode_kelas', 'semester.nama as nama_semester')->first();
        $this->change_id = $get->id;
        $this->change_kode_mapel = $get->kode_mapel;
        $this->change_kode_kelas = $get->kode_kelas;
        $this->change_nisn = $get->nisn;
        $this->change_nama = $get->nama;
        $this->change_pertemuan_ke = $get->pertemuan_ke;
        $this->change_keterangan = $get->keterangan;
        $this->change_semester = $get->nama_semester;
    }

    public function changeSubmit(){
        date_default_timezone_set('Asia/Jakarta');
        AbsensiModel::where('id', $this->change_id)->update([
            'keterangan' => $this->change_keterangan,
        ]);
        $this->dispatch('notification-success');
        $this->dispatch('closeModal', modal: 'change');
    }

    public function export($ext)
    {
        if(session()->get('role_id') == 3){
            $ext = "pdf";
            abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);
        }else{
            abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);
        }

        $headings = ['Mata Pelajaran', 'Kelas', 'Pertemuan Ke', 'Nama Siswa', 'Keterangan', 'Waktu Absensi'];
        $mapping = [
            '$table->mapel',
            '$table->kelas',
            '$table->pertemuan_ke',
            '$table->nama',
            '$table->keterangan',
            '$table->created_at',
        ];

        if(session()->get('role_id') == 3){
            $model = AbsensiModel::join('jadwal_pelajaran', 'jadwal_pelajaran.id', '=', 'absensi.id_jadwal')
                              ->join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                              ->join('kelas', 'kelas.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                              ->join('siswa', 'siswa.nisn', '=', 'absensi.nisn')
                              ->where('siswa.nisn', session()->get('store_username'));
        }else if(session()->get('role_id') == 2){
            $this->value_kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                ->pluck('jadwal_pelajaran.kode_kelas') // Use pluck to get an array of kode_kelas values
                                ->toArray();
            $model = AbsensiModel::join('jadwal_pelajaran', 'jadwal_pelajaran.id', '=', 'absensi.id_jadwal')
                              ->join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                              ->join('kelas', 'kelas.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                              ->join('siswa', 'siswa.nisn', '=', 'absensi.nisn')
                              ->whereIn('siswa.kode_kelas', $this->value_kelas)
                              ->where('jadwal_pelajaran.nip', session()->get('store_username'));
        }else if(session()->get('role_id') == 1){
            $this->value_kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                ->pluck('jadwal_pelajaran.kode_kelas') // Use pluck to get an array of kode_kelas values
                                ->toArray();
            $model = AbsensiModel::join('jadwal_pelajaran', 'jadwal_pelajaran.id', '=', 'absensi.id_jadwal')
                              ->join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                              ->join('kelas', 'kelas.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                              ->join('siswa', 'siswa.nisn', '=', 'absensi.nisn');
        }
        else{
            $this->value_kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                ->pluck('jadwal_pelajaran.kode_kelas') // Use pluck to get an array of kode_kelas values
                                ->toArray();
            $model = AbsensiModel::join('jadwal_pelajaran', 'jadwal_pelajaran.id', '=', 'absensi.id_jadwal')
                              ->join('mata_pelajaran', 'mata_pelajaran.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                              ->join('kelas', 'kelas.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                              ->join('siswa', 'siswa.nisn', '=', 'absensi.nisn')
                              ->whereIn('siswa.kode_kelas', $this->value_kelas);
        }


        if($this->kode_mapel != ""){
            $model = $model->where('jadwal_pelajaran.kode_mapel', $this->kode_mapel);
        }

        if($this->kode_kelas != ""){
            $model = $model->where('jadwal_pelajaran.kode_kelas', $this->kode_kelas);
        }

        if($this->pertemuan_ke != ""){
            $model = $model->where('absensi.pertemuan_ke', $this->pertemuan_ke);
        }

        $collection = $model->select('mata_pelajaran.nama as mapel','kelas.nama as kelas','siswa.nama', 'absensi.id', 'absensi.pertemuan_ke', 'absensi.keterangan','absensi.created_at','absensi.updated_at')->get();

        return Excel::download(new ExcelExport($headings, $mapping, $collection), fileName: 'daftar-absensi.' . $ext);
    }
}
