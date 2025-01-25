<?php

namespace App\Livewire\Web;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

use Livewire\Component;

#[Layout('layouts.web')]
class Contact extends Component
{
    #[Title('Contact')]

    public function render()
    {
        return view('livewire.web.contact');
    }
}
