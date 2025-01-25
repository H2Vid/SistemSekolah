<?php

namespace App\Livewire\Cms\Setting;

use App\Livewire\Forms\Cms\Setting\FormLanguage;
use Illuminate\Support\Facades\Session;
use App\Models\Language as LanguageModel;
use App\Traits\CheckAccess;
use BaseComponent;

class Language extends BaseComponent
{
    use CheckAccess;

    public FormLanguage $form;
    public Session $session;
    public $title = 'Bahasa';

    public $searchBy = [
            [
                'name' => 'Nama',
                'field' => 'name',
            ],
            [
                'name' => 'Status',
                'field' => 'status',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'name',
        $order = 'asc';


    public function render()
    {
        // validasi fitur
        $akses = $this->crud($this->title);

        // set store_id
        $this->form->setStore(session()->get('store_id'));
        
        $model = LanguageModel::where('languages.store_id', session()->get('store_id'));

        $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.setting.language', compact('get'))->title($this->title);
    }
}
