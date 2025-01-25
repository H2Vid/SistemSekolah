<?php

namespace App\Livewire\Forms\Cms;

use Illuminate\Support\Str;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Siswa;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormAbsensi extends Form
{    
    #[Validate('nullable|numeric')]
    public $id = '';

    public $kode_kelas = '';

    #[Validate('required')]
    public $kode_mapel = '';

    public $pertemuan_ke = '';

    public $semester_id = '';

    public $tanggal = '';

    public $nisn = [];
    public $keterangan = [];

    // Get the data
    // public function getDetail($id) {
    //     $data = Jadwal::find($id);

    //     $this->id = $id;
    //     $this->kode_kelas = $data->kode_kelas;
    //     $this->kode_mapel = $data->kode_mapel;
    //     $this->nisn = $data->nisn   ;
    //     $this->jam_mulai = $data->jam_mulai;
    //     $this->jam_selesai = $data->jam_selesai;
    //     $this->hari = $data->hari;
    //     $this->aktif = $data->aktif;
    // }

    // Save the data
    public function save() {
        $this->validate();

        if ($this->id) {
            $this->update();
        } else {
            $this->store();
        }

        $this->reset();
    }

    // Store data
    public function store() {

        $siswa = Siswa::where('kode_kelas', $this->kode_kelas)->select('nisn', 'id')->get();
        $getJadwal = Jadwal::where('kode_kelas', $this->kode_kelas)->where('kode_mapel', $this->kode_mapel)->select('id')->first();

        foreach ($siswa as $l) {
            Absensi::create([
                'id_jadwal' => $getJadwal->id,
                'nisn' => $l['nisn'],
                'keterangan' => $this->keterangan[$l['id']],
                'tanggal' => date('Y-m-d'),
                'pertemuan_ke' => $this->pertemuan_ke,
                'kode_mapel' => $this->kode_mapel,
                'semester_id' => $this->semester_id,
            ]);
        }
    }

    // Update data
    public function update() {
        Jadwal::find($this->id)->update($this->all());
    }

    // Delete data
    public function delete($id) {
        Jadwal::find($id)->delete();
    }

}