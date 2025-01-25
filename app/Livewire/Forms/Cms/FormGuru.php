<?php

namespace App\Livewire\Forms\Cms;

use Illuminate\Support\Str;
use App\Models\Guru;
use App\Models\User;
use App\Models\StoreUser;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use App\Traits\WithSaveFile;
use Carbon\Carbon;
use Livewire\Form;

class FormGuru extends Form
{
    use WithSaveFile;

    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $nip = '';

    #[Validate('required')]
    public $nama = '';

    #[Validate('required')]
    public $jenis_kelamin = '';

    #[Validate('required')]
    public $tanggal_lahir = '';

    #[Validate('required')]
    public $tempat_lahir = '';

    #[Validate('required')]
    public $agama = '';

    #[Validate('required')]
    public $no_hp = '';

    #[Validate('required')]
    public $email = '';

    #[Validate('required')]
    public $alamat = '';

    #[Validate('nullable|image:jpeg,png,jpg,svg|max:2048')]
    public $image;

    #[Validate('required')]
    public $status_guru = '';

    // Get the data
    public function getDetail($id) {
        $data = Guru::find($id);

        $this->id = $id;
        $this->nip = $data->nip;
        $this->nama = $data->nama;
        $this->jenis_kelamin = $data->jenis_kelamin;
        $this->tanggal_lahir = $data->tanggal_lahir;
        $this->tempat_lahir = $data->tempat_lahir;
        $this->agama = $data->agama;
        $this->no_hp = $data->no_hp;
        $this->email = $data->email;
        $this->alamat = $data->alamat;
        $this->image = $data->image;
        $this->status_guru = $data->status_guru;
    }

    // Save the data
    public function save() {
        $this->validate();

        // Cek duplikasi NIP hanya jika NIP diubah
        $this->checkForDuplicateNip();

        if ($this->id) {
            $this->update();
        } else {
            $this->store();
        }

        $this->reset();
    }

    // Method untuk cek duplikasi NIP
    private function checkForDuplicateNip() {
        $existingGuru = Guru::find($this->id);
    
        // Jika ID tidak ditemukan, abaikan pengecekan perubahan NIP
        if (!$existingGuru || $this->nip !== $existingGuru->nip) {
            // Cek apakah NIP sudah ada di database untuk guru lain
            $duplicateGuru = Guru::where('nip', $this->nip)->where('id', '!=', $this->id)->exists();
    
            if ($duplicateGuru) {
                session()->flash('error', 'NIP sudah terdaftar. Mohon gunakan NIP yang lain.');
                throw new \Exception('NIP sudah terdaftar.');
            }
        }
    }
    
    // Store data
    public function store() {
        $save_path = Guru::$FILE_PATH;

        // Save image
        if($this->image) {
            $this->image = $this->saveFile($this->image, $save_path, $save_path)['filename'];
        } else {
            $this->image = '';
        }

        Guru::create($this->only([
            'nip',
            'nama',
            'jenis_kelamin',
            'tanggal_lahir',
            'tempat_lahir',
            'agama',
            'no_hp',
            'email',
            'alamat',
            'image',
            'status_guru',
        ]));

        $user = User::create([
            'name' => $this->nama,
            'username' => $this->nip,
            'email' => $this->email,
            'role_name' => "guru",
            'password' => Hash::make(Carbon::createFromFormat('Y-m-d', $this->tanggal_lahir)->format('dmY')),
        ]);

        $user->assignRole(2);

        $getUser = User::where('username', $this->nip)->where('email', $this->email)->first();
        StoreUser::create(
            [
                'store_id' => 1,
                'user_id' => $getUser->id,
                'status' => 1
            ]
        );
    }

    // Update data
    public function update() {
        $old = Guru::find($this->id);
        $save_path = Guru::$FILE_PATH;

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
        $get = Guru::where('id', $id)->first();
        $email = $get->email;
        $nip = $get->nip;
        Guru::find($id)->delete();
        User::where('username', $nip)->where('email', $email)->delete();
    }
}
