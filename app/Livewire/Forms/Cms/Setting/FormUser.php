<?php

namespace App\Livewire\Forms\Cms\Setting;

use App\Models\User;
use App\Models\StoreUser;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormUser extends Form
{
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $role_id = '';

    #[Validate('required')]
    public $store_id = '';

    #[Validate('required')]
    public $name = '';

    #[Validate('required')]
    public $username = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    // Get the data
    public function getDetail($id) {
        $data = User::find($id);

        $this->id = $id;
        $this->name = $data->name;
        $this->username = $data->username;
        $this->email = $data->email;
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
        $value_store_id = $this->store_id;
        $value_role_id = $this->role_id;

        unset($this->role_id);
        unset($this->store_id);
        $user = User::create($this->only([
            'name',
            'username',
            'email',
            'password',
        ]));

        $user->assignRole($value_role_id);

        $getUser = User::where('username', $this->username)->where('email', $this->email)->first();
        $store = StoreUser::create(
            [
                'store_id' => $value_store_id,
                'user_id' => $getUser->id,
                'status' => 1
            ]
        );
        
    }

    // Update data
    public function update() {
        User::find($this->id)->update($this->all());
    }

    // Delete data
    public function delete($id) {
        User::find($id)->delete();
    }
}
