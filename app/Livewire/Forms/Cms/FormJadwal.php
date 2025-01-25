<?php

namespace App\Livewire\Forms\Cms;

use Illuminate\Support\Str;
use App\Models\Jadwal;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormJadwal extends Form
{    
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $kode_kelas = '';

    #[Validate('required')]
    public $kode_mapel = '';

    #[Validate('required')]
    public $nip = '';

    #[Validate('required')]
    public $jam_mulai = '';

    #[Validate('required')]
    public $jam_selesai = '';

    #[Validate('required')]
    public $hari = '';

    #[Validate('required')]
    public $aktif = '';

    #[Validate('required')]
    public $semester_id = '';

    #[Validate('required')]
    public $tahun_ajaran = '';
    // Get the data

    public function getDetail($id) {
        $data = Jadwal::find($id);

        $this->id = $id;
        $this->kode_kelas = $data->kode_kelas;
        $this->kode_mapel = $data->kode_mapel;
        $this->nip = $data->nip;
        $this->jam_mulai = $data->jam_mulai;
        $this->jam_selesai = $data->jam_selesai;
        $this->hari = $data->hari;
        $this->aktif = $data->aktif;
        $this->semester_id = $data->semester_id;
        $this->tahun_ajaran = $data->tahun_ajaran;

    }

    // Save the data
    public function save() {
        $this->validate();

        // Cek bentrok jadwal sebelum menyimpan
        $this->checkForScheduleConflict();

        if ($this->id) {
            $this->update();
        } else {
            $this->store();
        }

        $this->reset();
    }

    // Store data
    public function store() {
        Jadwal::create($this->only([
            'kode_kelas',
            'kode_mapel',
            'nip',
            'jam_mulai',
            'jam_selesai',
            'hari',
            'aktif',
            'semester_id',
            'tahun_ajaran',
        ]));
    }

    // Update data
    public function update() {
        Jadwal::find($this->id)->update($this->all());
    }

    // Delete data
    public function delete($id) {
        Jadwal::find($id)->delete();
    }

    // Method untuk memeriksa bentrok jadwal
    private function checkForScheduleConflict() {
        // Cek apakah perubahan jam_mulai atau jam_selesai bentrok
        $query = Jadwal::where('hari', $this->hari)
            ->where('kode_kelas', $this->kode_kelas)
            ->where('id', '!=', $this->id);  // Jangan hitung data yang sedang diupdate

        // Jika yang diubah adalah jam_mulai atau jam_selesai
        if ($this->jam_mulai !== $this->getOriginalJamMulai() || $this->jam_selesai !== $this->getOriginalJamSelesai()) {
            $query->where(function($query) {
                // Cek apakah jam mulai atau selesai bentrok
                $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                      ->orWhere(function($query) {
                          // Cek apakah jam yang baru mencakup jam yang lama
                          $query->where('jam_mulai', '<=', $this->jam_selesai)
                                ->where('jam_selesai', '>=', $this->jam_mulai);
                      });
            });
        }

        // Mengeksekusi query bentrok
        $existingJadwal = $query->exists();

        if ($existingJadwal) {
            // Jika ada bentrok, lemparkan error
            session()->flash('error', 'Maaf, jadwal tersebut sudah ada di waktu yang sama untuk kelas yang sama.');
            throw new \Exception('Jadwal sudah ada yang bentrok.');
        }
    }

    // Get the original jam_mulai and jam_selesai before update
    private function getOriginalJamMulai() {
        $original = Jadwal::find($this->id);
        return $original ? $original->jam_mulai : null;
    }

    private function getOriginalJamSelesai() {
        $original = Jadwal::find($this->id);
        return $original ? $original->jam_selesai : null;
    }
}
