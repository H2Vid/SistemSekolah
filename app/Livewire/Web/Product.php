<?php

namespace App\Livewire\Web;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

use Livewire\Component;

#[Layout('layouts.web')]
class Product extends Component
{
    #[Title('Product')]

    public function render()
    {
        return view('livewire.web.product');
    }
}
