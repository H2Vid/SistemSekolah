<?php

namespace App\Livewire\Forms\Cms;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormTransaction extends Form
{
    public $id;

    public $store_id;

    public $transaction_id = 0;

    #[Validate('nullable|numeric')]
    public $product_id = '';

    #[Validate('required|numeric')]
    public $qty = 1;

    // #[Validate('required|string')]
    public $price = 0;

    public $total_price = 0;

    public $name = '';
    public $stock = 0;
    
    public function setStore($store_id){
        $this->store_id = $store_id;
    }

    public function getDetail($id) {
        $data = Product::find($id);
        $this->product_id = $id;
        $this->name = $data->name;
        $this->stock = $data->stock;
        $this->price = "Rp. ".number_format($data->selling_price, 0, ',', '.');
    }

    // Save the data
    public function save() {

        $this->validate();
        $this->price = preg_replace('/[^0-9]/', '', $this->price);

        $this->total_price = $this->price * $this->qty;
        
        $cek_stock = Product::find($this->product_id);
        if($cek_stock->type_stock == 1){
            if($cek_stock->stock >= $this->qty){
                $cek = TransactionDetail::where('product_id', $this->product_id)->where('transaction_id', 0)->count();
                if($cek == 0){
                    $this->store();
                }else{
                    $this->update();
                }
            }
        }else{
            $cek = TransactionDetail::where('product_id', $this->product_id)->where('transaction_id', 0)->count();
            if($cek == 0){
                $this->store();
            }else{
                $this->update();
            }
        }
        
        $this->reset();
    }

    public function saveFinish($price, $discount, $cost, $tax, $total_price, $payment, $refund, $description) {
        $latestTransaction = Transaction::latest()->first();
        if ($latestTransaction) {
            $latestCode = $latestTransaction->code;
            // Extract the numeric part and increment it
            $numericPart = (int)substr($latestCode, -6) + 1;
            // Format the new code
            $code = 'LK-' . date('ym') . '-' . str_pad($numericPart, 6, '0', STR_PAD_LEFT);
        } else {
            // If there are no existing transactions, start from 1
            $code = 'LK-' . date('ym') . '-000001';
        }
        $transaction = Transaction::create([
            'store_id' => $this->store_id,
            'code' => $code,
            'price' => $price,
            'discount' => $discount,
            'cost' => $cost,
            'tax' => $tax,
            'total_price' => $total_price,
            'payment' => $payment,
            'refund' => $refund,
            'description' => $description,
        ]);
    
        $transaction_id = $transaction->id;
        TransactionDetail::where('transaction_id', 0)->update(['transaction_id' => $transaction_id]);

        // Update the stock in products table
        TransactionDetail::where('transaction_id', $transaction_id)->get()->each(function ($detail) {
            $product = Product::find($detail->product_id);
            if ($product) {
                $type_stock = $product->type_stock;
                if($type_stock == 1){
                    $product->stock -= $detail->qty;
                    if($product->stock >= 0){
                        $product->save();
                    }
                }
            }
        });

        $this->reset();
    }

    // Store data
    public function store() {
        unset($this->name);
        unset($this->stock);
        TransactionDetail::create($this->only([
            'store_id',
            'transaction_id',
            'product_id',
            'qty',
            'price',
            'total_price',
        ]));
    }

    // Update data
    public function update() {
        $old = TransactionDetail::where('product_id', $this->product_id)->where('transaction_id', 0)->first();
        $this->id = $old->id;
        $old_qty = $old ? $old->qty : 0;
        $old_total_price = $old ? $old->total_price : 0;
        $this->qty = $this->qty + $old_qty;
        $this->total_price = $this->total_price + $old_total_price;

        TransactionDetail::find($this->id)->update($this->all());
    }

    // Delete data
    public function delete($id) {
        TransactionDetail::find($id)->delete();
    }

}
