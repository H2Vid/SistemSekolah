<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\Store;
use App\Models\StoreUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register() {
        return view('auth.register');
    }

    public function store(RegisterRequest $request) {
        $validated = $request->validated();

        $user = User::create($validated);
        event(new Registered($user));

        // 1 = super admin
        $user->assignRole(1);

        Store::create([
            'name' => $request->name,
            'phone' => "",
            'address' => "",
            'email' => $request->email,
            'image' => "",
            'type' => 1,
            'status' => 1,
        ]);
        $getStore = Store::where('name', $request->name)->where('email', $request->email)->first();

        // Auth::login($user);
        $getUser = User::where('username', $request->username)->where('email', $request->email)->first();
        $store = StoreUser::create(
            [
                'store_id' => $getStore->id,
                'user_id' => $getUser->id,
                'status' => 1
            ]
        );
        return redirect()->intended(RouteServiceProvider::LOGIN);
    }
}
