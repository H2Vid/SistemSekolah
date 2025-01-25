<?php

namespace App\Livewire\Forms\Cms;

use Illuminate\Support\Str;
use App\Models\MataPelajaranPredikat;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Request;

class FormMataPelajaranPredikat extends Form
{    
    #[Validate('nullable|numeric')]
    public $id = '';

    public $id_mata_pelajaran = 0;

    #[Validate('required')]
    public $predikat = '';

    #[Validate('required')]
    public $keterangan = '';

    #[Validate('required')]
    public $nilai_min = '';

    #[Validate('required')]
    public $nilai_max = '';

    public function mount()
    {
        $this->id_mata_pelajaran = request()->query('id');
    }

    // Get the data
    public function getDetail($id) {
        $data = MataPelajaranPredikat::find($id);

        $this->id = $id;
        // $this->id_mata_pelajaran = $data->id_mata_pelajaran;
        $this->predikat = $data->predikat;
        $this->keterangan = $data->keterangan;
        $this->nilai_min = $data->nilai_min;
        $this->nilai_max = $data->nilai_max;
    }

    // Save the data
    public function save($id_mata_pelajaran) {
        $this->id_mata_pelajaran = $id_mata_pelajaran;
        $this->validate();
        if ($this->id) {
            $this->update($id_mata_pelajaran);
        } else {
            $this->store($id_mata_pelajaran);
        }

        $this->reset();
    }

    // Store data
    public function store() {
        MataPelajaranPredikat::create($this->only([
            'id_mata_pelajaran',
            'predikat',
            'keterangan',
            'nilai_min',
            'nilai_max',
        ]));
    }

    // Update data
    public function update() {
        MataPelajaranPredikat::find($this->id)->update($this->all());
    }

    // Delete data
    public function delete($id) {
        MataPelajaranPredikat::find($id)->delete();
    }

}