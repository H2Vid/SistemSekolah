<?php

namespace App\Livewire\Cms\Setting;

use App\Livewire\Forms\Cms\Setting\FormMenu;
use App\Models\Menu as MenuModel;
use Livewire\Attributes\Url;
use BaseComponent;
use App\Traits\CheckAccess;

class Menu extends BaseComponent
{
    use CheckAccess;
    public FormMenu $form;
    public $title = 'Menu';

    #[Url(keep: true)]
    public $on = 'cms';

    #[Url(keep: true)]
    public $view_parent_only = 0;

    public $parents = [];

    public $searchBy = [
            [
                'name' => 'Parent',
                'field' => 'p.name',
            ],
            [
                'name' => 'Name',
                'field' => 'menus.name',
            ],
            [
                'name' => 'On',
                'field' => 'menus.on',
            ],
            [
                'name' => 'Type',
                'field' => 'menus.type',
            ],
            [
                'name' => 'Icon',
                'field' => 'menus.icon',
            ],
            [
                'name' => 'Route',
                'field' => 'menus.route',
            ],
            [
                'name' => 'Ordering',
                'field' => 'menus.ordering',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'menus.ordering',
        $order = 'asc';

    public function mount() {
        $this->parents = MenuModel::whereNull('parent_id')->get();
    }

    public function render()
    {
        $this->crud($this->title);

        $model = MenuModel::leftJoin('menus as p', 'p.id', 'menus.parent_id')
            ->select('menus.*', 'p.name as parent_name')
            ->where('menus.on', $this->on);

        if ($this->view_parent_only) {
            $model->whereNull('menus.parent_id');
        }

        $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.setting.menu', compact('get'))->title($this->title);
    }
}
