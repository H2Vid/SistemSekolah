<?php

namespace App\Livewire\Cms;

use Illuminate\Support\Facades\Session;
use App\Models\Transaction as TransactionModel;
use App\Traits\CheckAccess;
use BaseComponent;

class Transaction extends BaseComponent
{
    use CheckAccess;

    public Session $session;
    public $title = 'Transaksi';

    public $searchBy = [
            [
                'name' => 'Kode',
                'field' => 'transactions.code',
            ],
            [
                'name' => 'Email',
                'field' => 'a.email',
            ],
            [
                'name' => 'Customer',
                'field' => 'a.name',
            ],
            [
                'name' => 'Produk',
                'field' => 'pd.name',
            ],
            [
                'name' => 'Harga',
                'field' => 'transactions.price',
            ],
            [
                'name' => 'Jumlah',
                'field' => 'transactions.qty',
            ],
            [
                'name' => 'Total Harga',
                'field' => 'transactions.total_price',
            ],
            [
                'name' => 'Pembayaran',
                'field' => 'transactions.payment',
            ],
            [
                'name' => 'Deskripsi',
                'field' => 'transactions.description',
            ],
        ],
        $isUpdate = false,
        $search = '',
        $paginate = 10,
        $orderBy = 'transactions.code',
        $order = 'desc';

    public $languages = [];

    public function render()
    {
        // validasi fitur
        $akses = $this->crud($this->title);
        $model = TransactionModel::join('customers as a', 'a.id', '=', 'transactions.customer_id')
                              ->join('products as b', 'b.id', '=', 'transactions.product_id')
                              ->join('product_details as pd', 'b.id', '=', 'pd.product_id')
                              ->join('languages as l', 'l.id', '=', 'pd.language_id')
                              ->where('l.status', 1);
        $selectFields = array_map(function($field) {
            return $field['field'];
        }, $this->searchBy);

        $selectFields[] = 'transactions.id';
        $selectFields[] = 'a.name as customer';
        $selectFields[] = 'pd.name as product';
        $selectFields[] = 'transactions.status';
        $selectFields[] = 'transactions.created_at';
        $selectFields[] = 'transactions.updated_at';

        $model = $model->select($selectFields);

        $get = $this->getDataWithFilter($model, [
            'orderBy' => $this->orderBy,
            'order' => $this->order,
            'paginate' => $this->paginate,
            's' => $this->search,
        ], $this->searchBy);

        if ($this->search != null) {
            $this->resetPage();
        }

        return view('livewire.cms.transaction', compact('get'))->title($this->title);
    }

    public function accepted($id=null){
        $this->dispatch('notification');
        TransactionModel::where('id', $id)->update(['status' => 200]);
        $this->render();
    }

    public function rejected($id=null){
        $this->dispatch('notification');
        TransactionModel::where('id', $id)->update(['status' => 300]);
        $this->render();
    }

}
