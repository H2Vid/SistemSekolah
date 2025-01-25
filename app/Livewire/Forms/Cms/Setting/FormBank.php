<?php

namespace App\Livewire\Forms\Cms\Setting;

use App\Models\SettingBank;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormBank extends Form
{
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $name = '';

    #[Validate('required')]
    public $code = '';

    #[Validate('required')]
    public $status = '';

    // Get the data
    public function getDetail($id) {
        $data = SettingBank::find($id);

        $this->id = $id;
        $this->name = $data->name;
        $this->code = $data->code;
        $this->status = $data->status;
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
        SettingBank::create($this->only([
            'name',
            'code',
            'status',
        ]));
    }

    // Update data
    public function update() {
        SettingBank::find($this->id)->update($this->all());
    }

    // Delete data
    public function delete($id) {
        SettingBank::find($id)->delete();
    }

}
