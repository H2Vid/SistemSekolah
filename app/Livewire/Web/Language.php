<?php

namespace App\Livewire\Web;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Language as LanguageModel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use Livewire\Component;

class Language extends Component
{
    public function mount($num)
    {
        Session::put('language_id', $num);
        return Redirect::to('/home');
    }

}
