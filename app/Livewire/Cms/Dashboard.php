<?php

namespace App\Livewire\Cms;

// use App\Models\RoleApplication;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\Modul;
use App\Models\User;
use App\Models\Nilai;
use App\Models\Absensi;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use App\Traits\CheckAccess;


use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Siswa as TraitsSiswa;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    use CheckAccess;

    public Session $session;
    public $title = [];    
    public $total_guru = 0;
    public $total_siswa = 0;
    public $total_siswa_diampu = 0;
    public $total_mata_pelajaran = 0;
    public $total_kelas = 0;
    public $total_kelas_diampu = 0;
    public $total_modul = 0;
    public $average = 0;
    public $kelas_sekarang;

    public $data_diri;

    public $labels = [];
    public $data = [];
    public $data2 = [];

    public function render(Request $request)
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

        $this->title[] = "Dashboard";
        $this->title[] = $img;

        // dd(session()->get('user_id'));
        if(session()->get('role_id') == 1){
            $datasets = [];
            $transactions = Absensi::selectRaw('MONTH(tanggal) as month, count(id) as total')
                    ->where('keterangan', 'Alpa')
                    ->groupBy('month')
                    ->get();

            $labels = [];
            $data = [];
            
            foreach ($transactions as $transaction) {
                $monthName = date('F', mktime(0, 0, 0, $transaction->month, 1));
                $labels[] = $monthName;
                $data[] = $transaction->total;
            }
    
            $this->data = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Alpa',
                        'backgroundColor' => '#f87979',
                        'data' => $data,
                    ]
                ]
            ];

            $this->total_guru = Guru::count();
            $this->total_siswa = Siswa::count();
            $this->total_kelas = Kelas::count();
            $this->total_mata_pelajaran = MataPelajaran::count();
            $this->total_modul = Modul::count();
            $this->data_diri = User::where('username', session()->get('store_username'))->first();
            
            return view('livewire.cms.dashboard')->title($this->title);
        }else if(session()->get('role_id') == 2){

            $datasets = [];
            $transactions = Absensi::join('jadwal_pelajaran as jp', 'jp.id', '=', 'absensi.id_jadwal')->selectRaw('MONTH(absensi.tanggal) as month, count(absensi.id) as total')
                    ->where('absensi.keterangan', 'Alpa')
                    ->where('jp.nip', session()->get('store_username'))
                    ->groupBy('month')
                    ->get();

            $labels = [];
            $data = [];
            
            foreach ($transactions as $transaction) {
                $monthName = date('F', mktime(0, 0, 0, $transaction->month, 1));
                $labels[] = $monthName;
                $data[] = $transaction->total;
            }
    
            $this->data = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Alpa',
                        'backgroundColor' => '#f87979',
                        'data' => $data,
                    ]
                ]
            ];

            $this->total_guru = Guru::count();
            $this->total_siswa = Siswa::count();

            $this->value_kelas = Jadwal::join('kelas as k', 'k.kode_kelas', '=', 'jadwal_pelajaran.kode_kelas')
                                ->where('jadwal_pelajaran.nip', session()->get('store_username'))
                                ->pluck('jadwal_pelajaran.kode_kelas') // Use pluck to get an array of kode_kelas values
                                ->toArray();

            $this->total_siswa_diampu = Siswa::join('kelas as k', 'k.kode_kelas', '=', 'siswa.kode_kelas')->whereIn('siswa.kode_kelas', $this->value_kelas)->count();

            $results = Jadwal::where('jadwal_pelajaran.nip', session()->get('store_username'))
                        ->groupBy('jadwal_pelajaran.kode_kelas')
                        ->selectRaw('jadwal_pelajaran.kode_kelas')
                        ->get();
                        
            $this->total_kelas_diampu = count($results);

            return view('livewire.cms.dashboard-guru')->title($this->title);
        
        }else if(session()->get('role_id') == 3){
            $this->total_guru = Guru::count();
            $this->total_siswa = Siswa::count();

            $getSiswa = Siswa::where('nisn', session()->get('store_username'))->first();
            $valueKodeKelas = $getSiswa->kode_kelas;
            $nisn = $getSiswa->nisn;

            $this->kelas_sekarang = Kelas::where('kode_kelas', $valueKodeKelas)->first();

            $nilai = Nilai::where('nisn', $nisn)->get();
            $average = 0;
            if ($nilai->count() > 0) {
                $totalValues = 0;
                $totalCount = 0;

                // Loop through each record
                foreach ($nilai as $record) {
                    // var_dump($record->ph1);
                    // Sum up the values for each record
                    $totalValues += ($record->ph1 * 0.3) + ($record->ph2 * 0.3) + ($record->uts * 0.2) + ($record->uas * 0.2);
                    $totalCount += 1;
                }

                // Calculate the average
                $average = $totalValues / $totalCount;
            }
            $this->average = $average;

            $datasets = [];

            // Adjust the query to select the semester and calculate the final grade.
            $grades = DB::table('nilai')
                ->selectRaw('semester_id,
                            (SUM((ph1 * 0.3) + (ph2 * 0.3) + (uts * 0.2) + (uas * 0.2)) / COUNT(*)) as total_grade')
                ->where('nisn', $nisn)
                ->groupBy('semester_id')
                ->get();

            $labels = [];
            $data = [];

            foreach ($grades as $grade) {
                $get_semester = Semester::where('id', $grade->semester_id)->first();
                
                // Check if the semester exists before using its property
                if ($get_semester) {
                    $labels[] = $get_semester->nama;
                    $data[] = $grade->total_grade;
                } else {
                    // Handle the case where the semester is not found, if needed
                    // For example, you might log an error or continue
                    continue;
                }
            }

            $this->data = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Nilai Akhir',
                        'backgroundColor' => '#f87979',
                        'data' => $data,
                    ]
                ]
            ];
            return view('livewire.cms.dashboard-siswa')->title($this->title);
        }
        
    }

    
}
