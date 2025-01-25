<?php

namespace App\Livewire\Cms\Setting;

use App\Livewire\Forms\Cms\Setting\FormStore;
use App\Livewire\Forms\Cms\Setting\FormStoreCabang;
use App\Models\Store as StoreModel;
use App\Models\StoreUser;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Session;
use Livewire\WithFileUploads;
use App\Traits\CheckAccess;
use BaseComponent;

class Store extends BaseComponent
{
    use CheckAccess;
    use WithFileUploads;
    public FormStore $form;
    public FormStoreCabang $formCabang;
    public Session $session;
    
    #[Validate('nullable|image:jpeg,png,jpg,svg|max:2048')]
    public $image;
    

    public $title = 'Toko';
    public $searchBy = [
        [
            'name' => 'Nama',
            'field' => 'stores.name',
        ],
        [
            'name' => 'Nomor HP',
            'field' => 'stores.phone',
        ],
        [
            'name' => 'Email',
            'field' => 'stores.email',
        ],
        [
            'name' => 'Alamat',
            'field' => 'stores.address',
        ],
    ],
    $isUpdate = false,
    $search = '',
    $paginate = 10,
    $orderBy = 'stores.name',
    $order = 'asc';
    public $styles;

    public function render()
    {

        $this->crud($this->title);

        $this->form->getDetail(session()->get('store_id'));
        
        $this->formCabang->setStore(session()->get('store_id'));

        $model = StoreModel::where('store_id', session()->get('store_id'))->where('type', 2);

        $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }
        
        return view('livewire.cms.setting.store', compact('get'))->title($this->title);
    }

    public function saveWithUpload() {
        $this->form->image = $this->image;
        $this->image = null;
        $this->save();
        $this->render();
    }

    
}
