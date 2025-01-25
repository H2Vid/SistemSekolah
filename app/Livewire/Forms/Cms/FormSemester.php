<?php

namespace App\Livewire\Forms\Cms;

use Illuminate\Support\Str;
use App\Models\Semester;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormSemester extends Form
{    
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required|unique:semester,nama')]
    public $nama = '';

    // Get the data
    public function getDetail($id) {
        $data = Semester::find($id);

        $this->id = $id;
        $this->nama = $data->nama;
    }

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
        Semester::create($this->only([
            'nama',
        ]));
    }

    // Update data
    public function update() {
        Semester::find($this->id)->update($this->all());
    }

    // Delete data
    public function delete($id) {
        Semester::find($id)->delete();
    }

}