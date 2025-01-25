<?php

namespace App\Livewire\Web;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

use Livewire\Component;

#[Layout('layouts.web')]
class Vip extends Component
{
    #[Title('VIP')]

    public function render()
    {
        return view('livewire.web.vip');
    }
}
