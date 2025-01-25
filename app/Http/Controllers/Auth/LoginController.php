<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User as UserModel;

class LoginController extends Controller
{
    /**
     * Display login view
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle authentication request
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->validated();

        // Ambil data user berdasarkan username
        $user = UserModel::where('username', $request->username)
                         ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                         ->select('users.id', 'users.username', 'model_has_roles.role_id')
                         ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // **Validasi Status Guru (Role 2) berdasarkan NIP**
        if ($user->role_id == 2) {
            $guru = Guru::where('nip', $user->username)->first();
            if ($guru && $guru->status_guru == 'Tidak Aktif') {
                return redirect()->back()->with('error', 'Akun guru ini tidak aktif.');
            }
        }

        // **Validasi Status Siswa (Role 3) berdasarkan NISN**
        if ($user->role_id == 3) {
            $siswa = Siswa::where('nisn', $user->username)->first();
            if ($siswa && $siswa->status_siswa == 'Tidak Aktif') {
                return redirect()->back()->with('error', 'Akun siswa ini tidak aktif.');
            }
        }

        // **Jika lolos validasi, lanjutkan login**
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return redirect()->back()->with('error', 'Incorrect username or password.');
        }

        $request->session()->regenerate();

        // Ambil kembali data user untuk sesi setelah login
        $data = UserModel::where('users.id', $user->id)
                          ->leftJoin('store_users', 'store_users.user_id', '=', 'users.id')
                          ->leftJoin('stores', 'stores.id', '=', 'store_users.store_id')
                          ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                          ->select('users.id', 'users.username', 'store_users.store_id', 'stores.name', 'stores.type', 'model_has_roles.role_id')
                          ->first();

        if ($data) {
            Session::put('user_id', $data->id);
            Session::put('store_id', $data->store_id);
            Session::put('store_username', $data->username);
            Session::put('store_name', $data->name);
            Session::put('store_type', $data->type);
            Session::put('role_id', $data->role_id);
        }

        return redirect()->to(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session
     */
    public function destroy(Request $request) {

        // Auth::guard('cms')->logout();
        Auth::guard('web')->logout();
        // dd(1);
        Session::forget('user_id');
        Session::forget('store_id');
        Session::forget('store_username');
        Session::forget('store_name');
        Session::forget('store_type');
        Session::forget('role_id');

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return json_encode(['success' => true]);
    }
}
