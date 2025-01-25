<?php

namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait WithGetFilterData {

    public function getDataWithFilter(Model|Builder $model, array $data, array $searchBy = [
        [
            'name' => '',
            'field' => '',
            'no_search' => true,
        ]
    ]) {
        $orderBy = $data['orderBy'] ?? 'id';
        $orderByDua = $data['$orderByDua'] ?? '';
        $orderByTiga = $data['$orderByTiga'] ?? '';
        $order = $data['order'] ?? 'asc';
        $paginate = $data['paginate'] ?? 10;
        $s = $data['s'] ?? '';

        $model = $model->where(function ($query) use ($s, $searchBy) {
            foreach ($searchBy as $key => $value) {
                if(!isset($value['no_search'])) {
                    if ($key == 0) {
                        $query->where($value['field'], 'like', "%$s%");
                    } else {
                        $query->orWhere($value['field'], 'like', "%$s%");
                    }
                }
            }
        });

        $model = $model->orderBy($orderBy, $order);
        if($orderByDua != ''){
            $model = $model->orderBy($orderByDua, $order);
        }
        
        if($orderByTiga != ''){
            $model = $model->orderBy($orderByTiga, $order);
        }

        $model = $model->latest();

        return $model->paginate($paginate);
    }

    public function getDataWithFilterMenu(Model|Builder $model, array $data, array $searchByMenu = [
        [
            'name' => '',
            'field' => '',
            'no_search' => true,
        ]
    ]) {
        $orderByMenu = $data['orderByMenu'] ?? 'id';
        $orderMenu = $data['orderMenu'] ?? 'asc';
        $paginateMenu = $data['paginateMenu'] ?? 10;
        $sMenu = $data['sMenu'] ?? '';

        $model = $model->where(function ($query) use ($sMenu, $searchByMenu) {
            foreach ($searchByMenu as $key => $value) {
                if(!isset($value['no_search'])) {
                    if ($key == 0) {
                        $query->where($value['field'], 'like', "%$sMenu%");
                    } else {
                        $query->orWhere($value['field'], 'like', "%$sMenu%");
                    }
                }
            }
        });

        $model = $model->orderBy($orderByMenu, $orderMenu);

        $model = $model->latest();

        return $model->paginate($paginateMenu);
    }    

    public function getDataWithFilterDetail(Model|Builder $model, array $data, array $searchBy = [
        [
            'name' => '',
            'field' => '',
            'no_search' => true,
        ]
    ]) {
        $orderBy = $data['orderBy'] ?? 'id';
        $order = $data['order'] ?? 'asc';
        $paginate = $data['paginate'] ?? 10;
        $s = $data['s'] ?? '';

        $model = $model->where(function ($query) use ($s, $searchBy) {
            foreach ($searchBy as $key => $value) {
                if(!isset($value['no_search'])) {
                    if ($key == 0) {
                        $query->where($value['field'], 'like', "%$s%");
                    } else {
                        $query->orWhere($value['field'], 'like', "%$s%");
                    }
                }
            }
        });

        $model = $model->orderBy($orderBy, $order);

        $model = $model->latest();

        return $model->paginate($paginate);
    }
}
