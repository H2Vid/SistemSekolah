@auth
    <a href="{{ route('cms.dashboard') }}">Dashboard</a>
@else
    <a href="{{ route('auth.login.index') }}">Login</a>
@endauth
