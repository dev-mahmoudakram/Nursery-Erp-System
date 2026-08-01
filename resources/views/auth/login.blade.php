<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.auth.login') }} - {{ __('messages.app_name') }}</title>
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif
    <style>body{background:#f5f7f6;height:100vh;display:flex;align-items:center;}</style>
</head>
<body>
<div class="container" style="max-width:420px;">
    <div class="text-center mb-3">
        <a href="{{ route('lang.switch', 'ar') }}" class="btn btn-sm {{ app()->getLocale()==='ar'?'btn-dark':'btn-outline-dark' }}">AR</a>
        <a href="{{ route('lang.switch', 'en') }}" class="btn btn-sm {{ app()->getLocale()==='en'?'btn-dark':'btn-outline-dark' }}">EN</a>
    </div>
    <div class="card p-4">
        <h4 class="text-center mb-3">🌱 {{ __('messages.app_name') }}</h4>
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ __('messages.auth.email') }}</label>
                <input type="email" name="email" class="form-control" required autofocus value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">{{ __('messages.auth.password') }}</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">{{ __('messages.auth.remember') }}</label>
            </div>
            <button class="btn btn-success w-100">{{ __('messages.auth.login') }}</button>
        </form>
    </div>
</div>
</body>
</html>
