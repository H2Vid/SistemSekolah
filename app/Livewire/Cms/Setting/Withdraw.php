<?php

namespace App\Livewire\Cms\Setting;

use App\Livewire\Forms\Cms\Setting\FormWithdraw;
use Illuminate\Support\Facades\Session;
use App\Models\SettingWithdraw;
use App\Traits\CheckAccess;
use BaseComponent;

class Withdraw extends BaseComponent
{
    use CheckAccess;

    public FormWithdraw $form;
    public Session $session;
    public $title = 'Penarikan';

    public $searchBy = [
            [
                'name' => 'Minimal Nominal',
                'field' => 'min_nominal',
            ],
            [
                'name' => 'Maksimal Nominal',
                'field' => 'max_nominal',
            ],
            [
                'name' => 'Persentase',
                'field' => 'percentase',
            ],
            [
                'name' => 'Status',
                'field' => 'status',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'min_nominal',
        $order = 'asc';


    public function render()
    {
        // validasi fitur
        $akses = $this->crud($this->title);

        $get = $this->getDataWithFilter(new SettingWithdraw, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.setting.withdraw', compact('get'))->title($this->title);
    }
}
