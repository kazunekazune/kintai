@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="text-center mb-4">ログイン</h2>

        <form method="POST" action="{{ route('login.post') }}" novalidate>
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">パスワード</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password">
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-dark btn-lg">ログインする</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('register') }}" class="text-decoration-none">会員登録はこちら</a>
        </div>
    </div>
</div>
@endsection