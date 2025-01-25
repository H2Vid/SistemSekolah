<?php

namespace App\Livewire\Forms\Web;

use App\Models\Customer;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormRegister extends Form
{
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $name = '';

    #[Validate('required')]
    public $email = '';

    // Save the data
    public function save() {
        // $this->validate();
        dd($this);
        // $this->store();
        // $this->reset();
    }

    // Store data
    public function store() {
        Customer::create($this->only([
            'store_id',
            'name',
            'status',
        ]));
    }

    // // Update data
    // public function update() {
    //     Language::find($this->id)->update($this->all());
    // }

    // // Delete data
    // public function delete($id) {
    //     Language::find($id)->delete();
    // }

}
