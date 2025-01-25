<?php

namespace App\Livewire\Forms\Cms;

use Illuminate\Support\Str;
use App\Models\Siswa;
use App\Models\User;
use App\Models\StoreUser;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use App\Traits\WithSaveFile;
use Carbon\Carbon;
use Livewire\Form;

class FormSiswa extends Form
{
    use WithSaveFile;
    
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $nisn = '';

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

    #[Validate('required')]
    public $nama_wali = '';

    #[Validate('required')]
    public $no_telepon_wali = '';

    #[Validate('required')]
    public $angkatan = '';

    #[Validate('required')]
    public $kode_kelas = '';

    #[Validate('nullable|image:jpeg,png,jpg,svg|max:2048')]
    public $image;

    #[Validate('required')]
    public $status_siswa = '';

    #[Validate('required')]
    public $semester_id = '';
    
    // Get the data
    public function getDetail($id) {
        $data = Siswa::find($id);

        $this->id = $id;
        $this->nisn = $data->nisn;
        $this->nama = $data->nama;
        $this->jenis_kelamin = $data->jenis_kelamin;
        $this->tanggal_lahir = $data->tanggal_lahir;
        $this->tempat_lahir = $data->tempat_lahir;
        $this->agama = $data->agama;
        $this->no_hp = $data->no_hp;
        $this->email = $data->email;
        $this->alamat = $data->alamat;
        $this->nama_wali = $data->nama_wali;
        $this->no_telepon_wali = $data->no_telepon_wali;
        $this->angkatan = $data->angkatan;
        $this->kode_kelas = $data->kode_kelas;
        $this->image = $data->image;
        $this->status_siswa = $data->status_siswa;
        $this->semester_id = $data->semester_id;
    }

    // Save the data
    public function save() {
        $this->validate();

        try{
            if ($this->id) {
                $this->update();
            } else {
                $this->store();
            }
            $this->reset();
        } catch (\Exception $e) {

        }
        

    }

    // Store data
    public function store() {
        $save_path = Siswa::$FILE_PATH;

        // Save image
        if($this->image) {
            $this->image = $this->saveFile($this->image, $save_path, $save_path)['filename'];
        } else {
            $this->image = '';
        }

        Siswa::create($this->only([
            'nisn',
            'nama',
            'jenis_kelamin',
            'tanggal_lahir',
            'tempat_lahir',
            'agama',
            'no_hp',
            'email',
            'alamat',
            'nama_wali',
            'no_telepon_wali',
            'angkatan',
            'kode_kelas',
            'image',
            'status_siswa',
            'semester_id',
        ]));

        $user = User::create([
            'name' => $this->nama,
            'username' => $this->nisn,
            'email' => $this->email,
            'role_name' => "siswa",
            'password' => Hash::make(Carbon::createFromFormat('Y-m-d', $this->tanggal_lahir)->format('dmY')),
        ]);

        $user->assignRole(3);

        $getUser = User::where('username', $this->nisn)->where('email', $this->email)->first();
        $store = StoreUser::create(
            [
                'store_id' => 1,
                'user_id' => $getUser->id,
                'status' => 1
            ]
        );
    }

    // Update data
    public function update() {
        $old = Siswa::find($this->id);
        $save_path = Siswa::$FILE_PATH;

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
        $getSiswa = Siswa::where('id', $id)->first();
        $email = $getSiswa->email;
        $nisn = $getSiswa->nisn;
        Siswa::find($id)->delete();
        User::where('username', $nisn)->where('email', $email)->delete();
    }

}