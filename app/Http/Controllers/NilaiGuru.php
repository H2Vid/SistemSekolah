<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Absensi;
use App\Models\MataPelajaranPredikat;
use Illuminate\Support\Facades\DB;

class NilaiGuru extends Controller
{
    public function __invoke(Request $request)
    {
        // Mendapatkan nip dari query string
        $nip = $request->query('nip');

        // Ambil data guru berdasarkan nip
        $guru = Guru::where('nip', $nip)->first();

        if (!$guru) {
            return abort(404, 'Guru tidak ditemukan');
        }

        // Ambil data nilai yang terkait dengan guru tersebut
        // Kita asumsikan guru mengajar beberapa mata pelajaran yang terhubung dengan nilai
        $nilai = Nilai::join('jadwal_pelajaran as jp', 'jp.id', '=', 'nilai.id_jadwal')
                    ->join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'jp.kode_mapel')
                    ->where('jp.nip', $nip)  // Filter berdasarkan NIP guru
                    ->select('nilai.*', 'mp.nama as mata_pelajaran', 'jp.kode_kelas')
                    ->get();

        // Mengirimkan data guru dan nilai ke view
        return view('pdf.nilaigurupdf', compact('guru', 'nilai'));
    }

    // Fungsi untuk menentukan predikat berdasarkan nilai rata-rata
}
