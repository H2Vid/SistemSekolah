<?php

namespace App\Livewire\Forms\Cms;

use App\Models\Kelas;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormKelas extends Form
{    
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $nip = '';

    #[Validate('required')]
    public $kode_kelas = '';

    #[Validate('required')]
    public $tingkat_kelas = '';

    #[Validate('required')]
    public $nama = '';

    // Get the data
    public function getDetail($id) {
        $data = Kelas::find($id);

        $this->id = $id;
        $this->nip = $data->nip;
        $this->kode_kelas = $data->kode_kelas;
        $this->tingkat_kelas = $data->tingkat_kelas;
        $this->nama = $data->nama;
    }

    // Save the data
    public function save() {
        // Jika sedang mengedit, ambil data lama
        $existingKelas = $this->id ? Kelas::find($this->id) : null;

        // Aturan validasi untuk kode_kelas
        $rules = [
            'kode_kelas' => 'required|unique:kelas,kode_kelas' . ($this->id && $existingKelas && $existingKelas->kode_kelas === $this->kode_kelas ? ','.$this->id : ''),
        ];

        $messages = [
            'kode_kelas.unique' => 'Kode Kelas Telah Digunakan',
        ];

        $this->validate($rules, $messages);

        $this->validate($rules);

        if ($this->id) {
            $this->update();
        } else {
            $this->store();
        }

        $this->reset();
    }

    // Store data
    public function store() {
        Kelas::create($this->only([
            'nip',
            'kode_kelas',
            'tingkat_kelas',
            'nama',
        ]));
    }

    // Update data
    public function update() {
        $kelas = Kelas::find($this->id);
        if ($kelas) {
            $kelas->update($this->only([
                'nip',
                'kode_kelas',
                'tingkat_kelas',
                'nama',
            ]));
        }
    }

    // Delete data
    public function delete($id) {
        Kelas::find($id)->delete();
    }
}
