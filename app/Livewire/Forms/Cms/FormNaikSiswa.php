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

class FormNaikSiswa extends Form
{
    
    #[Validate('required')]
    public $semester_naik = '';
    
    // Save the data
    public function save() {
        dd("soni");

        $this->reset();
    }

    public function naik(){
        dd("sonisetiawan");
    }

}