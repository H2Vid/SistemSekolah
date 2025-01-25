<?php

namespace App\Livewire\Forms\Cms;

use App\Models\MataPelajaran;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormMataPelajaran extends Form
{    
    #[Validate('nullable|numeric')]
    public $id = '';

    #[Validate('required')]
    public $kode_mapel = '';

    #[Validate('required')]
    public $tingkat_kelas = '';

    #[Validate('required')]
    public $nama = '';

    #[Validate('required')]
    public $nilai_kkm = '';

    #[Validate('required')]
    public $kategori = '';

    public $kategoriOptions = ['Umum', 'Dasar Program Keahlian', 'Kompetensi Kejuruan'];

    // Ambil data mata pelajaran berdasarkan ID
    public function getDetail($id) {
        $data = MataPelajaran::find($id);

        $this->id = $id;
        $this->kode_mapel = $data->kode_mapel;
        $this->tingkat_kelas = $data->tingkat_kelas;
        $this->nama = $data->nama;
        $this->nilai_kkm = $data->nilai_kkm;
        $this->kategori = $data->kategori;
    }

    // Simpan data
    public function save() {
        // Jika sedang mengedit, ambil data lama
        $existingMapel = $this->id ? MataPelajaran::find($this->id) : null;

        // Aturan validasi untuk kode_mapel
        $rules = [
            'kode_mapel' => 'required|unique:mata_pelajaran,kode_mapel' . ($this->id ? ',' . $this->id : ''),
        ];

        // Jika sedang mengedit dan kode_mapel tidak diubah, tidak perlu validasi unik
        if ($this->id && $existingMapel && $existingMapel->kode_mapel === $this->kode_mapel) {
            $rules['kode_mapel'] = 'required'; // Wajib diisi tapi tidak dicek unik
        }

        $messages = [
            'kode_mapel.unique' => 'Kode Mapel Telah Digunakan',
        ];

        $this->validate($rules, $messages);

        $this->validate($rules);

        if ($this->id) {
            $this->update();
        } else {
            $this->store();
        }

        $this->reset();
    }

    // Tambah data baru
    public function store() {
        MataPelajaran::create($this->only([
            'kode_mapel',
            'tingkat_kelas',
            'nama',
            'nilai_kkm',
            'kategori',
        ]));
    }

    // Update data
    public function update() {
        $mapel = MataPelajaran::find($this->id);
        if ($mapel) {
            $mapel->update($this->only([
                'kode_mapel',
                'tingkat_kelas',
                'nama',
                'nilai_kkm',
                'kategori',
            ]));
        }
    }

    // Hapus data
    public function delete($id) {
        MataPelajaran::find($id)->delete();
    }
}
