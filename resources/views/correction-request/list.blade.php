@extends('layouts.app')

@section('title', '申請一覧')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">申請一覧</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>日付</th>
                                <th>申請種別</th>
                                <th>申請日時</th>
                                <th>ステータス</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($correctionRequests as $request)
                            <tr>
                                <td>{{ $request->attendance->date->format('Y/m/d') }}</td>
                                <td>勤怠修正申請</td>
                                <td>{{ $request->created_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    @switch($request->status)
                                    @case('pending')
                                    <span class="badge bg-warning">承認待ち</span>
                                    @break
                                    @case('approved')
                                    <span class="badge bg-success">承認済み</span>
                                    @break
                                    @case('rejected')
                                    <span class="badge bg-danger">却下</span>
                                    @break
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{ route('attendance.show', $request->attendance_id) }}" class="btn btn-sm btn-primary">詳細</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">申請記録がありません</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection