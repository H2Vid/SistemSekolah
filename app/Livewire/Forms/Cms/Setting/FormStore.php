<?php

namespace App\Livewire\Forms\Cms\Setting;

use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use App\Models\Store;
use App\Traits\WithSaveFile;
use Livewire\Form;

class FormStore extends Form
{
    use WithSaveFile;

    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required|string')]
    public $name;

    #[Validate('nullable|numeric')]
    public $phone;

    #[Validate('required|string')]
    public $address;

    #[Validate('nullable|string|email')]
    public $email;

    #[Validate('nullable|numeric')]
    public $type;

    #[Validate('nullable|image:jpeg,png,jpg,svg|max:2048')]
    public $image;

    // Get the data
    public function getDetail($id) {
        $data = Store::find($id);

        $this->id = $id;
        $this->name = $data->name;
        $this->phone = $data->phone;
        $this->address = $data->address;
        $this->email = $data->email;
        $this->type = $data->type;
        $this->image = $data->image;
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
        $save_path = Store::$FILE_PATH;

        // Save image
        if($this->image) {
            $this->image = $this->saveFile($this->image, $save_path, $save_path)['filename'];
        } else {
            $this->image = '';
        }

        Store::create($this->only([
            'name',
            'phone',
            'address',
            'email',
            'image',
        ]));
    }

    // Update data
    public function update() {
        $old = Store::find($this->id);
        $save_path = Store::$FILE_PATH;

        // Save image
        if($this->image) {
            $this->image = $this->saveFile($this->image, $save_path, $save_path)['filename'];
        } else {
            $this->image = $old->image;
        }

        $old->update($this->all());
    }

    // Delete data
    public function delete($id) {
        Store::find($id)->delete();
    }

}
