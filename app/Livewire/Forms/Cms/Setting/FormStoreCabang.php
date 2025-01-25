<?php

namespace App\Livewire\Forms\Cms\Setting;

use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use App\Models\Store;
use Livewire\Form;

class FormStoreCabang extends Form
{
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('nullable|numeric')]
    public $store_id = '';

    #[Validate('required|string')]
    public $name;

    #[Validate('nullable|numeric')]
    public $phone;

    #[Validate('required|string')]
    public $address;

    #[Validate('nullable|string|email')]
    public $email;

    #[Validate('nullable|numeric')]
    public $type = 2;

    public $image = "";


    public function setStore($id) {
        $this->store_id = $id;
    }
    // Get the data
    public function getDetail($id) {
        $data = Store::find($id);

        $this->id = $id;
        $this->name = $data->name;
        $this->phone = $data->phone;
        $this->address = $data->address;
        $this->email = $data->email;
        $this->type = $data->type;
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
        Store::create($this->only([
            'store_id',
            'name',
            'phone',
            'address',
            'email',
            'type',
            'image',
        ]));
    }

    // Update data
    public function update() {
        $old = Store::find($this->id);
        $old->update($this->all());
    }

    // Delete data
    public function delete($id) {
        Store::find($id)->delete();
    }

}
