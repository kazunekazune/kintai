@extends('layouts.admin')

@section('title', '管理者 - 修正申請承認')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">修正申請承認</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>申請情報</h5>
                        <p><strong>申請者:</strong> {{ $request->user->name }}</p>
                        <p><strong>申請日時:</strong> {{ $request->created_at->format('Y年m月d日 H:i') }}</p>
                        <p><strong>対象日:</strong> {{ $request->attendance->date->format('Y年m月d日') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>現在の勤怠情報</h5>
                        <p><strong>出勤時刻:</strong> {{ $request->attendance->clock_in ? $request->attendance->clock_in->format('H:i') : '-' }}</p>
                        <p><strong>退勤時刻:</strong> {{ $request->attendance->clock_out ? $request->attendance->clock_out->format('H:i') : '-' }}</p>
                        <p><strong>備考:</strong> {{ $request->attendance->note }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>申請内容</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>項目</th>
                                    <th>現在の値</th>
                                    <th>申請値</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>出勤時刻</td>
                                    <td>{{ $request->attendance->clock_in ? $request->attendance->clock_in->format('H:i') : '-' }}</td>
                                    <td>{{ $request->requested_clock_in ? $request->requested_clock_in->format('H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>退勤時刻</td>
                                    <td>{{ $request->attendance->clock_out ? $request->attendance->clock_out->format('H:i') : '-' }}</td>
                                    <td>{{ $request->requested_clock_out ? $request->requested_clock_out->format('H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>休憩開始時刻</td>
                                    <td>{{ $request->attendance->breaks->first() ? $request->attendance->breaks->first()->break_start->format('H:i') : '-' }}</td>
                                    <td>{{ $request->requested_break_start ? $request->requested_break_start->format('H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>休憩終了時刻</td>
                                    <td>{{ $request->attendance->breaks->first() ? $request->attendance->breaks->first()->break_end->format('H:i') : '-' }}</td>
                                    <td>{{ $request->requested_break_end ? $request->requested_break_end->format('H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td>備考</td>
                                    <td>{{ $request->attendance->note }}</td>
                                    <td>{{ $request->requested_note }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.correction-request.list') }}" class="btn btn-secondary">戻る</a>
                    <form action="{{ route('admin.correction-request.approve.post', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('この修正申請を承認しますか？')">
                            承認する
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection