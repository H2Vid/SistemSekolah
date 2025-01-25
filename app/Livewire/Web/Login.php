<?php

namespace App\Livewire\Web;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;

use BaseComponent;

#[Layout('layouts.web')]
class Login extends BaseComponent
{
    #[Title('Login')]

    public function render()
    {
        return view('livewire.web.login');
    }

    public function doLogin(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $check = Customer::where('email', $email);
        if($check->count() > 0){
            $getCustomer = $check->first();
            $hashPassword = $getCustomer->password;
            $customer_id = $getCustomer->id;
            if (Hash::check($password, $hashPassword)) {
                Session::put('status_login', true);
                Session::put('customer_id', $customer_id);
                return Redirect::to('/dashboard');
            }else{
                return Redirect::to('/login')->with('error', 'Password tidak sama. Silahkan coba lagi.');
            }
        }else{
            return Redirect::to('/login')->with('error', 'Akun tidak ditemukan. Silahkan coba lagi.');
        }
    }
}
