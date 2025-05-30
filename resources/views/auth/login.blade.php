@extends('layouts.app')

@section('title', __('auth.login'))

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="d-flex justify-content-center w-100">
            <div class="d-flex" style="width: 1040px; border-radius: 8px; overflow: hidden;">
                <div style="position: relative; width: 520px; height: 600px; background: #4339F2;">
                    <img src="{{ asset('images/vhod_img.svg') }}" alt="Вход" style="width: 100%; height: 100%; object-fit: cover;">
                    <img src="{{ asset('images/star.gif') }}" alt="star" 
                         style="position: absolute;
                                top: 50%;
                                left: 50%;
                                transform: translate(-50%, -50%);
                                width: 520px;
                                height: 520px;
                                mix-blend-mode: lighten;
                                pointer-events: none;">
                </div>
                <div style="width: 520px; background: white;">
                    <div class="card border-0 h-100">
                        <div class="card-body p-5 d-flex flex-column justify-content-center">
                            <div>
                                <h4 class="mb-4 text-center" style="font-weight: 500; font-size: 22px;">Вход в аккаунт</h4>
                                <form method="POST" action="{{ route('login') }}" class="d-flex flex-column align-items-center">
                                    @csrf

                                    <div class="mb-3" style="width: 328px;">
                                        <input id="email" 
                                               type="email" 
                                               class="form-control bg-light border-0 @error('email') is-invalid @enderror" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               required 
                                               placeholder="Почта"
                                               style="height: 48px; background: #F5F5F5 !important; color: #272727;"
                                               autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4" style="width: 328px;">
                                        <input id="password" 
                                               type="password" 
                                               class="form-control bg-light border-0 @error('password') is-invalid @enderror" 
                                               name="password" 
                                               required
                                               placeholder="Пароль"
                                               style="height: 48px; background: #F5F5F5 !important; color: #272727;">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div style="width: 328px;">
                                        <button type="submit" 
                                                class="btn btn-primary w-100 py-2"
                                                style="background: #1682FD; border: none; border-radius: 12px; height: 48px;">
                                            Войти
                                        </button>
                                    </div>

                                    <div class="text-center mt-3">
                                        <p class="mb-2" style="color: #6C757D;">
                                            Нет аккаунта? 
                                            <a href="{{ route('register') }}" 
                                               style="color: #1682FD; text-decoration: none; font-weight: 500;">
                                                Регистрация
                                            </a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control::placeholder {
        color: #767676 !important;
        opacity: 1;
    }
</style>
@endsection 