<?php

namespace App\Livewire\Cms\Setting;

use App\Livewire\Forms\Cms\Setting\FormUser;
use App\Models\User as UserModel;
use App\Models\Role as RoleModel;
use App\Models\Store as StoreModel;
use Illuminate\Support\Facades\Session;
use App\Traits\CheckAccess;
use BaseComponent;

class User extends BaseComponent
{
    use CheckAccess;
    public FormUser $form;
    public Session $session;

    public $title = 'Pengguna';

    public $searchBy = [
            [
                'name' => 'Name',
                'field' => 'users.name',
            ],
            [
                'name' => 'Username',
                'field' => 'users.username',
            ],
            [
                'name' => 'Email',
                'field' => 'users.email',
            ],
            [
                'name' => 'Role',
                'field' => 'roles.name',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'users.name',
        $order = 'asc';

    public $roles = [];
    public $stores = [];

    public function render()
    {
        $this->crud($this->title);
        if(session()->get('store_type') == 1){
            $model = UserModel::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->join('store_users', 'store_users.user_id', '=', 'users.id')
                ->join('stores', 'stores.id', '=', 'store_users.store_id')
                ->select('users.*', 'roles.name as role')
                ->where(function($query) {
                    $query->where('stores.store_id', 0)
                        ->orWhere('stores.store_id', session()->get('store_id'));
                });

            $this->stores = StoreModel::join('store_users', 'store_users.store_id', '=', 'stores.id')->where(function($query) {
                    $query->where('stores.store_id', 0)
                        ->orWhere('stores.store_id', session()->get('store_id'));
                })->select('stores.id', 'stores.name')->get();
        }else if(session()->get('store_type') == 2){
            $this->stores = StoreModel::join('store_users', 'store_users.store_id', '=', 'stores.id')->where('stores.store_id', session()->get('store_id'))->get();
        }else{
            
        }

        $this->roles = RoleModel::all();
        $this->stores = StoreModel::all();

        $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.setting.user', compact('get'))->title($this->title);
    }
}
