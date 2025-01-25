<?php

namespace App\Livewire\Forms\Cms\Setting;

use App\Models\SettingWithdraw;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormWithdraw extends Form
{
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $min_nominal = '';

    #[Validate('required')]
    public $max_nominal = '';

    #[Validate('required')]
    public $percentase = '';

    #[Validate('required')]
    public $status = '';

    // Get the data
    public function getDetail($id) {
        $data = SettingWithdraw::find($id);

        $this->id = $id;
        $this->min_nominal = "Rp. ".number_format($data->min_nominal, 0, ',', '.');
        $this->max_nominal = "Rp. ".number_format($data->max_nominal, 0, ',', '.');
        $this->percentase = $data->percentase;
        $this->status = $data->status;
    }

    // Save the data
    public function save() {
        $this->validate();
        $this->min_nominal = preg_replace('/[^0-9]/', '', $this->min_nominal);
        $this->max_nominal = preg_replace('/[^0-9]/', '', $this->max_nominal);

        if ($this->id) {
            $this->update();
        } else {
            $this->store();
        }

        $this->reset();
    }

    // Store data
    public function store() {
        SettingWithdraw::create($this->only([
            'min_nominal',
            'max_nominal',
            'percentase',
            'status',
        ]));
    }

    // Update data
    public function update() {
        SettingWithdraw::find($this->id)->update($this->all());
    }

    // Delete data
    public function delete($id) {
        SettingWithdraw::find($id)->delete();
    }

}
