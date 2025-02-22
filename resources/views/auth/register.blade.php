<x-auth-layout>
    <x-slot:title>
        Register
    </x-slot>

    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo" style="display: flex; justify-content: center; align-items: center; margin-top: -50px;">
                    <a href="#"><img src="{{ asset('assets/compiled/svg/logo-pos-livewire3.png') }}" style="width: 100%; height: 160px; margin-bottom: -80px;" alt="Logo"></a>
                </div>
                <p class="auth-subtitle mb-5">Daftarkan toko anda sekarang juga!</p>

                <form action="{{ route('auth.register.store') }}" method="POST">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control form-control-xl @error('name') is-invalid @enderror" placeholder="Name" name="name" value="{{ old('name') ?? '' }}">
                        <div class="form-control-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') ?? '' }}">
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="text" class="form-control form-control-xl @error('username') is-invalid @enderror" placeholder="Username" name="username" value="{{ old('username') ?? '' }}">
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" placeholder="Password" name="password">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group position-relative has-icon-left mb-4" style="margin-bottom: -5px!important;">
                        <input type="password" class="form-control form-control-xl" placeholder="Confirm Password" name="password_confirmation">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <button  class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Sign Up</button>
                </form>
                <div class="text-center mt-5 text-lg fs-4">
                    <p class='text-gray-600' style="margin-top: -20px;">Sudah punya akun? <a href="{{ route('auth.login.index') }}" class="font-bold">Log
                            in</a>.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right" style="display: flex; justify-content: center; align-items: center;">
                <img src="{{ asset('assets/compiled/png/icon-login.png') }}" style="width: 80%;" alt="Logo">
            </div>
        </div>
    </div>
</x-auth-layout>
