<?php

namespace App\Livewire\Web;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

use Livewire\Component;

#[Layout('layouts.web')]
class Dashboard extends Component
{
    #[Title('Dashboard')]

    public function render()
    {
        return view('livewire.web.dashboard');
    }
}
