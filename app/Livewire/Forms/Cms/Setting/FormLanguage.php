<?php

namespace App\Livewire\Forms\Cms\Setting;

use App\Models\Language;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormLanguage extends Form
{
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $name = '';

    #[Validate('required')]
    public $status = '';

    public $store_id = '';

    public function setStore($store_id){
        $this->store_id = $store_id;
    }


    // Get the data
    public function getDetail($id) {
        $data = Language::find($id);

        $this->id = $id;
        $this->name = $data->name;
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
        Language::create($this->only([
            'store_id',
            'name',
            'status',
        ]));
    }

    // Update data
    public function update() {
        Language::find($this->id)->update($this->all());
    }

    // Delete data
    public function delete($id) {
        Language::find($id)->delete();
    }

}
