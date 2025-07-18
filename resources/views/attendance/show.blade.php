@extends('layouts.app')

@section('title', '勤怠詳細')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">勤怠詳細 - {{ $attendance->date->format('Y年m月d日') }}</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>基本情報</h5>
                        <p><strong>名前:</strong> {{ $attendance->user->name }}</p>
                        <p><strong>日付:</strong> {{ $attendance->date->format('Y年m月d日') }}</p>
                        <p><strong>ステータス:</strong>
                            @switch($attendance->status)
                            @case('勤務外')
                            <span class="badge bg-secondary">勤務外</span>
                            @break
                            @case('出勤中')
                            <span class="badge bg-success">出勤中</span>
                            @break
                            @case('休憩中')
                            <span class="badge bg-warning">休憩中</span>
                            @break
                            @case('退勤済')
                            <span class="badge bg-danger">退勤済</span>
                            @break
                            @endswitch
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>勤怠時間</h5>
                        <p><strong>出勤時刻:</strong> {{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '-' }}</p>
                        <p><strong>退勤時刻:</strong> {{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '-' }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>休憩記録</h5>
                    @if($attendance->breaks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>開始時刻</th>
                                    <th>終了時刻</th>
                                    <th>休憩時間</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendance->breaks as $break)
                                <tr>
                                    <td>{{ $break->break_start ? $break->break_start->format('H:i') : '-' }}</td>
                                    <td>{{ $break->break_end ? $break->break_end->format('H:i') : '-' }}</td>
                                    <td>
                                        @if($break->break_start && $break->break_end)
                                        {{ \Carbon\Carbon::parse($break->break_start)->diffInMinutes(\Carbon\Carbon::parse($break->break_end)) }}分
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">休憩記録がありません</p>
                    @endif
                </div>

                <div class="mb-4">
                    <h5>修正申請</h5>
                    <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="clock_in_time" class="form-label">出勤時刻 <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('clock_in_time') is-invalid @enderror"
                                    id="clock_in_time" name="clock_in_time"
                                    value="{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '' }}" required>
                                @error('clock_in_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="clock_out_time" class="form-label">退勤時刻 <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('clock_out_time') is-invalid @enderror"
                                    id="clock_out_time" name="clock_out_time"
                                    value="{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '' }}" required>
                                @error('clock_out_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="break_start_time" class="form-label">休憩開始時刻</label>
                                <input type="time" class="form-control @error('break_start_time') is-invalid @enderror"
                                    id="break_start_time" name="break_start_time"
                                    value="{{ $attendance->breaks->first() ? $attendance->breaks->first()->break_start->format('H:i') : '' }}">
                                @error('break_start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="break_end_time" class="form-label">休憩終了時刻</label>
                                <input type="time" class="form-control @error('break_end_time') is-invalid @enderror"
                                    id="break_end_time" name="break_end_time"
                                    value="{{ $attendance->breaks->first() ? $attendance->breaks->first()->break_end->format('H:i') : '' }}">
                                @error('break_end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">備考 <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('note') is-invalid @enderror"
                                id="note" name="note" rows="3" required>{{ $attendance->note }}</textarea>
                            @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">修正申請</button>
                    </form>
                </div>

                @if($attendance->correctionRequests->count() > 0)
                <div class="mb-4">
                    <h5>修正申請履歴</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>申請日時</th>
                                    <th>申請内容</th>
                                    <th>ステータス</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendance->correctionRequests as $request)
                                <tr>
                                    <td>{{ $request->created_at->format('Y/m/d H:i') }}</td>
                                    <td>
                                        出勤: {{ $request->requested_clock_in ? $request->requested_clock_in->format('H:i') : '-' }}<br>
                                        退勤: {{ $request->requested_clock_out ? $request->requested_clock_out->format('H:i') : '-' }}<br>
                                        休憩開始: {{ $request->requested_break_start ? $request->requested_break_start->format('H:i') : '-' }}<br>
                                        休憩終了: {{ $request->requested_break_end ? $request->requested_break_end->format('H:i') : '-' }}<br>
                                        備考: {{ $request->requested_note }}
                                    </td>
                                    <td>
                                        @if($request->status === '承認待ち')
                                        <span class="badge bg-warning">承認待ち</span>
                                        @else
                                        <span class="badge bg-success">承認済み</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection