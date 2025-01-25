<?php

namespace App\Livewire\Forms\Cms;

use Illuminate\Support\Str;
use App\Models\Modul;
use Livewire\Attributes\Validate;
use App\Traits\WithSaveFile;
use Carbon\Carbon;
use Livewire\Form;

class FormModul extends Form
{
    use WithSaveFile;
    
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $id_jadwal = '';

    public $kode_mapel = '';

    #[Validate('required')]
    public $nama_file = '';

    public $tanggal_upload = '';

    #[Validate('nullable|mimes:pdf,doc,docx,ppt,pptx|max:2048')]
    public $file_upload;

    // Get the data
    public function getDetail($id) {
        $data = Modul::find($id);

        $this->id = $id;
        $this->id_jadwal = $data->id_jadwal;
        $this->kode_mapel = $data->kode_mapel;
        $this->nama_file = $data->nama_file;
        $this->tanggal_upload = $data->tanggal_upload;
        $this->file_upload = $data->file_upload;
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
 // Store data
public function store() {
    $this->validate();

    $save_path = Modul::$FILE_PATH;

    // Check if file upload exists and is valid
    if ($this->file_upload) {
        // Save file
        $this->file_upload = $this->saveFile($this->file_upload, $save_path, $save_path)['filename'];
    } else {
        // Ensure that file_upload is set to an empty string if no file uploaded
        $this->file_upload = '';
    }

    $this->tanggal_upload = date('Y-m-d H:i:s');

    // Save the rest of the data
    Modul::create($this->only([
        'id_jadwal',
        'kode_mapel',
        'nama_file',
        'file_upload',
        'tanggal_upload',
    ]));
}

// Update data
public function update() {
    $old = Modul::find($this->id);
    $save_path = Modul::$FILE_PATH;

    // Check if file upload exists and is valid
    if ($this->file_upload) {
        // Save new file
        $this->file_upload = $this->saveFile($this->file_upload, $save_path, $save_path)['filename'];
    } else {
        // Retain old file if no new file is uploaded
        $this->file_upload = $old->file_upload;
    }

    // Update the record with all the data
    $old->update($this->all());
}

    // Delete data
    public function delete($id) {
        Modul::find($id)->delete();
    }

}