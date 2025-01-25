<?php

namespace App\Livewire\Cms\Setting;

use App\Livewire\Forms\Cms\Setting\FormRole;
use Spatie\Permission\Models\Role as RoleModel;
use App\Models\RoleApplication;

use App\Models\Menu as MenuModel;
use App\Traits\CheckAccess;

use BaseComponent;

class Role extends BaseComponent
{
    use CheckAccess;
    public FormRole $form;
    public $title = 'Role';
    public $akses = null;
    public $val_role_name = "";
    public $val_role_id = 0;
    // public $menus = [];

    public $searchBy = [
            [
                'name' => 'Name',
                'field' => 'name',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'name',
        $order = 'asc';
    
    public $searchByMenu = [
            [
                'name' => 'Name',
                'field' => 'menus.name',
            ],
        ],
        $isUpdateMenu = false,
        $searchMenu = '',
        $paginateMenu = 10,
        $orderByMenu = 'menus.ordering',
        $orderMenu = 'asc';



    public function render()
    {
        $this->crud($this->title);

        $get = $this->getDataWithFilter(new RoleModel, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }
        $model = MenuModel::with('roleApplications');
        $get_menu = $this->getDataWithFilterMenu($model, [
            'orderByMenu' => $this->orderByMenu,
            'orderMenu' => $this->orderMenu,
            'paginateMenu' => $this->paginateMenu,
            'sMenu' => $this->searchMenu,
        ], $this->searchByMenu);

        return view('livewire.cms.setting.role', compact('get', 'get_menu'))->title($this->title);
    }

    public function detail($id=null){
        $this->val_role_id = $id;
        $get = RoleModel::where('id', $id)->first();
        $this->val_role_name = $get->name;

        $model = MenuModel::whereHas('roleApplications', function ($query) use ($id) {
            $query->where('role_id', $id);
        })->with('roleApplications');
        // dd($model->get());

        $get_menu = $this->getDataWithFilterMenu($model, [
            'orderByMenu' => $this->orderByMenu,
            'orderMenu' => $this->orderMenu,
            'paginateMenu' => $this->paginateMenu,
            'sMenu' => $this->searchMenu,
        ], $this->searchByMenu);
    }
    
    public function saveMenu($menu_id, $status)
    {
        if($status == 1){
            RoleApplication::create(
                [
                    'role_id' => $this->val_role_id,
                    'menu_id' => $menu_id,
                    'status_menu' => $status,
                ]
            );
        }else{
            
        }
    }

    public function saveStatus($id, $status){
        if($status != ""){
            $this->dispatch('notification');
        }
    }

    public function updateMenu($menu_id, $id, $status)
    {
        if($status == 2){
            $roleApplication = RoleApplication::find($id);
            $roleApplication->delete();
        }else if($status == 1){
            $roleApplication = RoleApplication::find($id);
            $roleApplication->menu_id = $menu_id;
            $roleApplication->save();
        }else{

        }
    }
    public function updateStatusInsert($id, $status)
    {
        $roleApplication = RoleApplication::find($id);
        $roleApplication->status_insert = $status;
        $roleApplication->save();
    }
    public function updateStatusRead($id, $status)
    {
        $roleApplication = RoleApplication::find($id);
        $roleApplication->status_read = $status;
        $roleApplication->save();
    }
    public function updateStatusUpdate($id, $status)
    {
        $roleApplication = RoleApplication::find($id);
        $roleApplication->status_update = $status;
        $roleApplication->save();
    }
    public function updateStatusDelete($id, $status)
    {
        $roleApplication = RoleApplication::find($id);
        $roleApplication->status_delete = $status;
        $roleApplication->save();
    }
    public function updateStatusExport($id, $status)
    {
        $roleApplication = RoleApplication::find($id);
        $roleApplication->status_export = $status;
        $roleApplication->save();
    }
}
