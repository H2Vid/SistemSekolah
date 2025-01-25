<?php

namespace App\Livewire\Web;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Tagline;
use App\Models\AboutUs;
use App\Models\AlumniTestimonial;
use App\Models\Staff as StaffModel;
use App\Models\Extracurricular as ExtracurricularModel;
use App\Models\Facility as FacilityModel;
use App\Models\Hero as HeroModel;
use App\Models\Partner as PartnerModel;
use App\Models\Article as ArticleModel;

use Livewire\Component;

#[Layout('layouts.web')]
class ProductDetail extends Component
{
    #[Title('Product Detail')]

    public function render()
    {
        return view('livewire.web.product-detail');
    }
}
