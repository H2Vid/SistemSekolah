<?php

namespace App\Livewire\Cms;

use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Cms\FormKelas;
use Illuminate\Support\Facades\Session;
use App\Models\Kelas as KelasModel;
use App\Traits\CheckAccess;

use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Collection;

use BaseComponent;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Semester;
use App\Models\Siswa as TraitsSiswa;

class NaikSemester extends BaseComponent
{
    use CheckAccess;

    public FormKelas $form;
    public Session $session;

    public $title = [];
    public $guru = [];
    public $semester = [];
    public $value_kelas = [];

    public $searchBy = [
            [
                'name' => 'Wali Kelas',
                'field' => 'nip',
            ],
            [
                'name' => 'Kode Kelas',
                'field' => 'kode_kelas',
            ],
            [
                'name' => 'Tingkat Kelas',
                'field' => 'tingkat_kelas',
            ],
            [
                'name' => 'Nama',
                'field' => 'nama',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'nama',
        $order = 'asc';


    public $kelas = [];

    public function render()
    {

        if (session()->get('role_id') == 1) {
            $get = TraitsUser::where('username', session()->get('store_username'))->select('image')->first();
            $img = "user/" . $get->image;
        } else if (session()->get('role_id') == 2) {
            $get = TraitsGuru::where('nip', session()->get('store_username'))->select('image')->first();
            $img = "guru/" . $get->image;
        } else if (session()->get('role_id') == 3) {
            $get = TraitsSiswa::where('nisn', session()->get('store_username'))->select('image')->first();
            $img = "siswa/" . $get->image;
        }

        $this->title[] = "Kenaikan Semester";
        $this->title[] = $img;

        // validasi fitur
        $akses = $this->crud($this->title);
        $this->guru = TraitsGuru::select('nip', 'nama')->get();
        $this->semester = Semester::select('id', 'nama')->get();
        $get = $this->getDataWithFilter(new KelasModel, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.naik-semester', compact('get'))->title($this->title);
    }
    public function save()
    {
        // Retrieve the selected semester ID
        $semesterId = $this->form->id;

        // Update the semester_id for all students in the selected class
        $updated = TraitsSiswa::where('kode_kelas', $this->value_kelas['kode_kelas'])
            ->update(['semester_id' => $semesterId]);

        // Optional: Provide feedback to the user (success or failure)
        if ($updated) {
            $this->dispatch('notification-success');
            $this->dispatch('closeModal', modal: 'mazer-modal');
        } else {
            $this->dispatch('notification-failed');
            $this->dispatch('closeModal', modal: 'mazer-modal');
        }

    }

    public function loadStudents($kode_kelas)
    {
        // Memastikan kelas dengan $kode_kelas ada dalam database
        $kelas = KelasModel::where('kode_kelas', $kode_kelas)->first();

        if (!$kelas) {
            // Jika kelas tidak ditemukan, bisa memberikan error atau fallback
            return 'Kelas tidak ditemukan';
        }

        $this->value_kelas = [
            'id' => $kelas->id,
            'nama' => $kelas->nama, 
            'kode_kelas' => $kelas->kode_kelas,  // Menggunakan $kelas yang sudah diverifikasi
            'siswa' => TraitsSiswa::where('kode_kelas', $kelas->kode_kelas)->get(),  // Menyaring siswa berdasarkan kode_kelas
        ];
    }
    public function export($ext)
    {
        abort_if(!in_array($ext, ['csv', 'xlsx', 'pdf']), code: Response::HTTP_NOT_FOUND);

        $headings = ['Wali Kelas', 'Kode Kelas', 'Tingkat', 'Nama'];
        $mapping = [
            '$table->nip',
            '$table->kode_kelas',
            '$table->tingkat_kelas',
            '$table->nama',
        ];
        $collection = KelasModel::all();
        return Excel::download(new ExcelExport($headings, $mapping, $collection), fileName: 'daftar-kelas.' . $ext);
    }
}
