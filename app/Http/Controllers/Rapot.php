<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\MataPelajaranPredikat;
use Illuminate\Support\Facades\DB;

class Rapot extends Controller
{
    public function __invoke(Request $request)
    {
        $nisn = $request->query('nisn');

        // Data siswa dan semester
        $head = Siswa::join('semester', 'semester.id', '=', 'siswa.semester_id')
            ->join('kelas', 'kelas.kode_kelas', '=', 'siswa.kode_kelas')
            ->join('guru', 'guru.nip', '=', 'kelas.nip')
            ->where('siswa.nisn', $nisn)
            ->select('siswa.*', 'semester.nama as semester', 'guru.nip', 'guru.nama as guru', 'kelas.kode_kelas')
            ->first();

        if (!$head) {
            return abort(404, 'Siswa tidak ditemukan');
        }

        // Data absensi
        $absensi = Absensi::where('nisn', $nisn)
        ->groupBy('semester_id')
        ->select(
            'semester_id',
            DB::raw('SUM(CASE WHEN keterangan = "Alpa" THEN 1 ELSE 0 END) as total_alpa'),
            DB::raw('SUM(CASE WHEN keterangan = "Izin" THEN 1 ELSE 0 END) as total_izin'),
            DB::raw('SUM(CASE WHEN keterangan = "Sakit" THEN 1 ELSE 0 END) as total_sakit'),
            DB::raw('COUNT(*) as total_hari') // Total hari (semua absensi)
        )
        ->get()
        ->keyBy('semester_id')
        ->map(function ($item) {
            // Hitung persentase sakit, izin, dan alpa dengan pembulatan
            $item->total_sakit = ($item->total_hari > 0)
                ? round(($item->total_sakit / $item->total_hari) * 100)
                : 0;
            $item->total_izin = ($item->total_hari > 0)
                ? round(($item->total_izin / $item->total_hari) * 100)
                : 0;
            $item->total_alpa = ($item->total_hari > 0)
                ? round(($item->total_alpa / $item->total_hari) * 100)
                : 0;
    
            return $item;
        });
    
    $absensiGrouped = $absensi; // Kirim data ke Blade

        // Data nilai mata pelajaran
$detail = Nilai::join('mata_pelajaran as mp', 'mp.kode_mapel', '=', 'nilai.kode_mapel')
    ->join('jadwal_pelajaran as jp', function ($join) {
        $join->on('nilai.id_jadwal', '=', 'jp.id')
            ->on('mp.kode_mapel', '=', 'jp.kode_mapel');
    })
    ->join('kelas as k', 'jp.kode_kelas', '=', 'k.kode_kelas') // Tambahkan join ke tabel kelas
    ->leftJoin('mata_pelajaran_predikat as mpp', function ($join) {
        $join->on('mp.id', '=', 'mpp.id_mata_pelajaran')
            ->on(DB::raw('(nilai.ph1 * 0.3) + (nilai.ph2 * 0.3) + (nilai.uts * 0.2) + (nilai.uas * 0.2)'), '>=', 'mpp.nilai_min')
            ->on(DB::raw('(nilai.ph1 * 0.3) + (nilai.ph2 * 0.3) + (nilai.uts * 0.2) + (nilai.uas * 0.2)'), '<=', 'mpp.nilai_max');
    })
    ->where('nilai.nisn', $nisn)
    ->select(
        'mp.nama as mata_pelajaran',
        'jp.kode_kelas',
        'jp.semester_id',
        'k.nama as kelas', // Ambil nama kelas dari tabel kelas
        'mp.kategori',
        'nilai.*',
        DB::raw('CEIL((nilai.ph1 * 0.25) + (nilai.ph2 * 0.25) + (nilai.uts * 0.15) + (nilai.uas * 0.15) + (nilai.nilai_keterampilan * 0.2)) as nilai_akhir'),
        'mp.nilai_kkm',
        'mpp.predikat',
        'mpp.keterangan'
    )
    ->orderBy('k.kode_kelas')
    ->orderBy('jp.semester_id')
        ->get();

        // Data predikat (opsional jika tidak sudah diambil di join)
        $mata_pelajaran_predikat = MataPelajaranPredikat::all();

        // Ambil semua nilai berdasarkan nisn
        $nilai = Nilai::where('nisn', $nisn)
            ->select('sikap', 'kedisiplinan', 'kebersihan')
            ->get();

        // Hitung rata-rata untuk setiap kolom
        $avg_sikap = $nilai->avg('sikap');
        $avg_kedisiplinan = $nilai->avg('kedisiplinan');
        $avg_kebersihan = $nilai->avg('kebersihan');

        // Fungsi untuk menentukan keterangan
        function getKeterangan($nilai)
        {
            if ($nilai >= 90) {
                return 'Sangat Baik';
            } elseif ($nilai >= 80) {
                return 'Baik';
            } elseif ($nilai >= 75) {
                return 'Cukup';
            } else {
                return 'Perlu Bimbingan';
            }
        }

        // Menambahkan keterangan berdasarkan rata-rata nilai
        $keterangan_sikap = getKeterangan($avg_sikap);
        $keterangan_kedisiplinan = getKeterangan($avg_kedisiplinan);
        $keterangan_kebersihan = getKeterangan($avg_kebersihan);


        // Return ke Blade
        return view('pdf.nilai', compact('head', 'detail', 'absensi', 'nilai', 'mata_pelajaran_predikat', 'keterangan_sikap', 'keterangan_kedisiplinan', 'keterangan_kebersihan'));
    }

    // Fungsi untuk menentukan predikat berdasarkan nilai rata-rata
}
