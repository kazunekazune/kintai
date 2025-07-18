@extends('layouts.admin')

@section('title', '管理者 - スタッフ一覧')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">スタッフ一覧</h4>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>氏名</th>
                                <th>メールアドレス</th>
                                <th>登録日</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('Y/m/d') }}</td>
                                <td>
                                    <a href="{{ route('admin.staff.attendance.list', $user->id) }}"
                                        class="btn btn-sm btn-primary">勤怠一覧</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-muted">スタッフが登録されていません</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection