<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Jadwal as JadwalModel;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Semester;
use App\Models\Siswa;

use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Support\Facades\DB;

class Jadwal extends Controller
{
    public function __invoke(Request $request)
    {
        $ext = 'xlsx';
        abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);

        $headings = ['Kode Kelas', 'Kode Mata Pelajaran', 'NIP', 'Jam Mulai', 'Jam Selesai', 'Hari', 'Status'];
        $mapping = [
            '$table->kode_kelas',
            '$table->kode_mapel',
            '$table->nip',
            '$table->jam_mulai',
            '$table->jam_selesai',
            '$table->hari',
            '$table->aktif',
        ];

        if(session()->get('role_id') == 3){
            $getSiswa = Siswa::where('nisn', session()->get('store_username'))->first();
            $valueKodeKelas = $getSiswa->kode_kelas;
            $collection = JadwalModel::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                ->where('jadwal_pelajaran.kode_kelas', $valueKodeKelas)
                ->select('jadwal_pelajaran.*', 'mp.nama')
                ->orderBy('jadwal_pelajaran.hari','asc')
                ->orderBy('jadwal_pelajaran.jam_mulai','asc')
                ->get();
        }else if(session()->get('role_id') == 2){
            $collection = JadwalModel::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jadwal_pelajaran.kode_mapel')
                ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                ->select('jadwal_pelajaran.*', 'mp.nama')
                ->orderBy('jadwal_pelajaran.hari','asc')
                ->orderBy('jadwal_pelajaran.jam_mulai','asc')
                ->get();
        }else{
            $collection = JadwalModel::all();
        }

        return Excel::download(new ExcelExport($headings, $mapping, $collection), fileName: 'daftar-jadwal.' . $ext);
    }
}
