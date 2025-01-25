<?php

namespace App\Livewire\Cms;

use App\Livewire\Forms\Cms\FormProfile;
use App\Models\User as UserModel;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Support\Facades\Session;
use App\Traits\CheckAccess;
use Livewire\WithFileUploads;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;


use BaseComponent;
use App\Traits\WithSaveFile;

use App\Models\User as TraitsUser;
use App\Models\Guru as TraitsGuru;
use App\Models\Siswa as TraitsSiswa;

class Profile extends BaseComponent
{
    use WithFileUploads;
    use CheckAccess;
    public FormUser $form;
    public Session $session;

    public $title = [];

    public $profiles;

    #[Validate('nullable|image:jpeg,png,jpg,svg|max:2048')]
    public $image = '';

    public $nama = '';
    public $name = '';
    public $username = '';
    public $nip = '';
    public $nisn = '';
    public $email = '';
    public $password = '';
    public $jenis_kelamin = '';
    public $tanggal_lahir = '';
    public $tempat_lahir = '';
    public $agama = '';
    public $no_hp = '';
    public $alamat = '';
    public $nama_wali = '';
    public $no_telepon_wali = '';

    public function render()
    {
        
        if(session()->get('role_id') == 1){
            $get = TraitsUser::where('username', session()->get('store_username'))->select('image')->first();
            $img = "user/".$get->image;
        }else if(session()->get('role_id') == 2){
            $get = TraitsGuru::where('nip', session()->get('store_username'))->select('image')->first();
            $img = "guru/".$get->image;
        }else if(session()->get('role_id') == 3){
            $get = TraitsSiswa::where('nisn', session()->get('store_username'))->select('image')->first();
            $img = "siswa/".$get->image;
        }

        $this->title[] = "Profile";
        $this->title[] = $img;

        if(session()->get('role_id') == 2){
            $this->profiles = Guru::where('nip', session()->get('store_username'))->first();
            $this->image = $this->profiles->image;
            $this->nip = $this->profiles->nip;
            $this->nama = $this->profiles->nama;
            $this->jenis_kelamin = $this->profiles->jenis_kelamin;
            $this->tanggal_lahir = $this->profiles->tanggal_lahir;
            $this->tempat_lahir = $this->profiles->tempat_lahir;
            $this->agama = $this->profiles->agama;
            $this->no_hp = $this->profiles->no_hp;
            $this->email = $this->profiles->email;
            $this->alamat = $this->profiles->alamat;
            return view('livewire.cms.profile-guru')->title($this->title);        
        }else if(session()->get('role_id') == 3){
            $this->profiles = Siswa::where('nisn', session()->get('store_username'))->first();
            $this->image = $this->profiles->image;
            $this->nisn = $this->profiles->nisn;
            $this->nama = $this->profiles->nama;
            $this->jenis_kelamin = $this->profiles->jenis_kelamin;
            $this->tanggal_lahir = $this->profiles->tanggal_lahir;
            $this->tempat_lahir = $this->profiles->tempat_lahir;
            $this->agama = $this->profiles->agama;
            $this->no_hp = $this->profiles->no_hp;
            $this->email = $this->profiles->email;
            $this->alamat = $this->profiles->alamat;
            $this->nama_wali = $this->profiles->nama_wali;
            $this->no_telepon_wali = $this->profiles->no_telepon_wali;
            return view('livewire.cms.profile-siswa')->title($this->title);        
        }else{
            $this->profiles = UserModel::where('id', session()->get('user_id'))->first();
            $this->image = $this->profiles->image;
            $this->name = $this->profiles->name;
            $this->email = $this->profiles->email;
            $this->username = $this->profiles->username;
            $this->jenis_kelamin = $this->profiles->jenis_kelamin;
            $this->tanggal_lahir = $this->profiles->tanggal_lahir;
            $this->tempat_lahir = $this->profiles->tempat_lahir;
            $this->agama = $this->profiles->agama;
            $this->no_hp = $this->profiles->no_hp;
            $this->alamat = $this->profiles->alamat;

            return view('livewire.cms.profile')->title($this->title);        
        }
    }



    public function simpanProfile(Request $request){
        try{
            if(session()->get('role_id') == 2){
                $save_path = Guru::$FILE_PATH;

                if ($request->hasFile('image')) {
                    // Get the file from the request
                    $image = $request->file('image');
                    
                    // Save the file using the saveFile method
                    $savedImage = $this->saveFiles($image, $save_path, 'image');
            
                    // Check if the file was saved successfully
                    if ($savedImage) {
                        $image = $savedImage['filename']; // Get the filename from the saved image data
                    } else {
                        return Redirect::to('/cms/profile/')->with('error', 'error data!');    
                    }

                    $proccess = Guru::where('nip', session()->get('store_username'))->update([
                        'nama' => $request->input('nama'),
                        'nip' => $request->input('nip'),
                        'email' => $request->input('email'),
                        'jenis_kelamin' => $request->input('jenis_kelamin'),
                        'tanggal_lahir' => $request->input('tanggal_lahir'),
                        'tempat_lahir' => $request->input('tempat_lahir'),
                        'agama' => $request->input('agama'),
                        'no_hp' => $request->input('no_hp'),
                        'alamat' => $request->input('alamat'),
                        'image' => $image,
                    ]);

                    if($request->input('password') != ""){
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('nama'),
                            'username' => $request->input('nip'),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password')),
                        ]);
                    }else{
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('nama'),
                            'username' => $request->input('nip'),
                            'email' => $request->input('email'),
                        ]);
                    }
                } else {
                    // No image uploaded, set default value
                    $proccess = Guru::where('nip', session()->get('store_username'))->update([
                        'nama' => $request->input('nama'),
                        'nip' => $request->input('nip'),
                        'email' => $request->input('email'),
                        'jenis_kelamin' => $request->input('jenis_kelamin'),
                        'tanggal_lahir' => $request->input('tanggal_lahir'),
                        'tempat_lahir' => $request->input('tempat_lahir'),
                        'agama' => $request->input('agama'),
                        'no_hp' => $request->input('no_hp'),
                        'alamat' => $request->input('alamat'),
                    ]);

                    if($request->input('password') != ""){
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('nama'),
                            'username' => $request->input('nip'),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password')),
                        ]);
                    }else{
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('nama'),
                            'username' => $request->input('nip'),
                            'email' => $request->input('email'),
                        ]);
                    }
                }
            }else if(session()->get('role_id') == 3){
                $save_path = Siswa::$FILE_PATH;

                if ($request->hasFile('image')) {
                    // Get the file from the request
                    $image = $request->file('image');
                    
                    // Save the file using the saveFile method
                    $savedImage = $this->saveFiles($image, $save_path, 'image');
            
                    // Check if the file was saved successfully
                    if ($savedImage) {
                        $image = $savedImage['filename']; // Get the filename from the saved image data
                    } else {
                        return Redirect::to('/cms/profile/')->with('error', 'error data!');    
                    }

                    $proccess = Siswa::where('nisn', session()->get('store_username'))->update([
                        'nama' => $request->input('nama'),
                        'nisn' => $request->input('nisn'),
                        'email' => $request->input('email'),
                        'jenis_kelamin' => $request->input('jenis_kelamin'),
                        'tanggal_lahir' => $request->input('tanggal_lahir'),
                        'tempat_lahir' => $request->input('tempat_lahir'),
                        'agama' => $request->input('agama'),
                        'no_hp' => $request->input('no_hp'),
                        'alamat' => $request->input('alamat'),
                        'nama_wali' => $request->input('nama_wali'),
                        'no_telepon_wali' => $request->input('no_telepon_wali'),
                        'image' => $image,
                    ]);

                    if($request->input('password') != ""){
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('nama'),
                            'username' => $request->input('nisn'),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password')),
                        ]);
                    }else{
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('nama'),
                            'username' => $request->input('nisn'),
                            'email' => $request->input('email'),
                        ]);
                    }
                } else {
                    // No image uploaded, set default value
                    $proccess = Siswa::where('nisn', session()->get('store_username'))->update([
                        'nama' => $request->input('nama'),
                        'nisn' => $request->input('nisn'),
                        'email' => $request->input('email'),
                        'jenis_kelamin' => $request->input('jenis_kelamin'),
                        'tanggal_lahir' => $request->input('tanggal_lahir'),
                        'tempat_lahir' => $request->input('tempat_lahir'),
                        'agama' => $request->input('agama'),
                        'no_hp' => $request->input('no_hp'),
                        'alamat' => $request->input('alamat'),
                        'nama_wali' => $request->input('nama_wali'),
                        'no_telepon_wali' => $request->input('no_telepon_wali'),
                    ]);

                    if($request->input('password') != ""){
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('nama'),
                            'username' => $request->input('nisn'),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password')),
                        ]);
                    }else{
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('nama'),
                            'username' => $request->input('nisn'),
                            'email' => $request->input('email'),
                        ]);
                    }
                }
            }else{
                $save_path = UserModel::$FILE_PATH;

                if ($request->hasFile('image')) {
                    // Get the file from the request
                    $image = $request->file('image');
                    
                    // Save the file using the saveFile method
                    $savedImage = $this->saveFiles($image, $save_path, 'image');
            
                    // Check if the file was saved successfully
                    if ($savedImage) {
                        $image = $savedImage['filename']; // Get the filename from the saved image data
                    } else {
                        // Handle the case where the file could not be saved
                        // return Redirect::to('/dashboard')->with('error', 'Failed to save image.');
                        return Redirect::to('/cms/profile/')->with('error', 'error data!');
        
                    }

                    if($request->input('password') != ""){
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('name'),
                            'username' => $request->input('username'),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password')),
                            'jenis_kelamin' => $request->input('jenis_kelamin'),
                            'tanggal_lahir' => $request->input('tanggal_lahir'),
                            'tempat_lahir' => $request->input('tempat_lahir'),
                            'agama' => $request->input('agama'),
                            'no_hp' => $request->input('no_hp'),
                            'alamat' => $request->input('alamat'),
                            'image' => $image,
                    ]);
                    }else{
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('name'),
                            'username' => $request->input('username'),
                            'email' => $request->input('email'),
                            'jenis_kelamin' => $request->input('jenis_kelamin'),
                            'tanggal_lahir' => $request->input('tanggal_lahir'),
                            'tempat_lahir' => $request->input('tempat_lahir'),
                            'agama' => $request->input('agama'),
                            'no_hp' => $request->input('no_hp'),
                            'alamat' => $request->input('alamat'),
                            'image' => $image,
                        ]);
                    }
                } else {
                    // No image uploaded, set default value
                    if($request->input('password') != ""){
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('name'),
                            'username' => $request->input('username'),
                            'email' => $request->input('email'),
                            'password' => Hash::make($request->input('password')),
                            'jenis_kelamin' => $request->input('jenis_kelamin'),
                            'tanggal_lahir' => $request->input('tanggal_lahir'),
                            'tempat_lahir' => $request->input('tempat_lahir'),
                            'agama' => $request->input('agama'),
                            'no_hp' => $request->input('no_hp'),
                            'alamat' => $request->input('alamat'),
                        ]);
                    }else{
                        $proccess = UserModel::where('id', session()->get('user_id'))->update([
                            'name' => $request->input('name'),
                            'username' => $request->input('username'),
                            'email' => $request->input('email'),
                            'jenis_kelamin' => $request->input('jenis_kelamin'),
                            'tanggal_lahir' => $request->input('tanggal_lahir'),
                            'tempat_lahir' => $request->input('tempat_lahir'),
                            'agama' => $request->input('agama'),
                            'no_hp' => $request->input('no_hp'),
                            'alamat' => $request->input('alamat'),
                        ]);
                    }
                }


            }
            return Redirect::to('/cms/profile')->with('success', 'Data berhasil diubah!');
        } catch (\Exception $e) {
            // Jika ada error, kirim notifikasi error atau log error
            return Redirect::to('/cms/profile')->with('danger', 'Mohon input dengan benar');
            
        }
    }

    public function saveFiles($file, $path = 'file', $base_file_name = 'file') {
        // Set base file name
        $base_file_name = $base_file_name . '_' . date('d-m-Y') . '_';

        // If file is not exist
        if(!$file) return false;

        $filename = $base_file_name . uniqid() . '.' . $file->extension();
        $file->storeAs( $path, $filename);

        return [
            'filename' => $filename,
            'path' => $path,
        ];
    }
}
