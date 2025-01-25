<?php

namespace App\Livewire\Cms\Setting;

use App\Livewire\Forms\Cms\Setting\FormBank;
use App\Models\SettingBank;
use App\Traits\CheckAccess;
use BaseComponent;

class Bank extends BaseComponent
{
    use CheckAccess;

    public FormBank $form;
    public $title = 'Bank';

    public $searchBy = [
            [
                'name' => 'Nama',
                'field' => 'name',
            ],
            [
                'name' => 'Kode',
                'field' => 'code',
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

        $get = $this->getDataWithFilter(new SettingBank, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.setting.bank', compact('get'))->title($this->title);
    }
}
