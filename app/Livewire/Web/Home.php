<?php

namespace App\Livewire\Web;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

#[Layout('layouts.web')]
class Home extends Component
{
    #[Title('Home')]

    public function mount(){
        return Redirect::to('/cms/dashboard');
    }

    public function render()
    {
        return view('livewire.web.home');
    }
}
