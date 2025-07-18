@extends('layouts.app')

@section('title', 'メール認証')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">メール認証</h4>
            </div>
            <div class="card-body text-center">
                <p class="mb-4">
                    ご登録いただいたメールアドレスに認証メールを送信しました。<br>
                    メールをご確認いただき、認証を完了してください。
                </p>

                <div class="d-grid gap-2 mb-3">
                    <button type="button" class="btn btn-primary" onclick="resendEmail()">
                        メールを再送信する
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">ログイン画面に戻る</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function resendEmail() {
        // メール再送信の処理
        alert('メールを再送信しました。');
    }
</script>
@endsection