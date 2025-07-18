@extends('layouts.admin')

@section('title', '管理者 - 勤怠一覧')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">勤怠一覧（管理者）</h4>
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.attendance.list', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
                        class="btn btn-outline-secondary">前日</a>
                    <span class="btn btn-outline-primary">{{ $date->format('Y年m月d日') }}</span>
                    <a href="{{ route('admin.attendance.list', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
                        class="btn btn-outline-secondary">翌日</a>
                </div>
            </div>
            <div class="card-body">
                @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>名前</th>
                                <th>出勤時刻</th>
                                <th>退勤時刻</th>
                                <th>休憩時間</th>
                                <th>勤務時間</th>
                                <th>ステータス</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->user->name }}</td>
                                <td>{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '-' }}</td>
                                <td>{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '-' }}</td>
                                <td>
                                    @php
                                    $totalBreakMinutes = 0;
                                    foreach($attendance->breaks as $break) {
                                    if ($break->break_start && $break->break_end) {
                                    $totalBreakMinutes += $break->break_start->diffInMinutes($break->break_end);
                                    }
                                    }
                                    @endphp
                                    {{ $totalBreakMinutes }}分
                                </td>
                                <td>
                                    @if($attendance->clock_in && $attendance->clock_out)
                                    @php
                                    $workMinutes = $attendance->clock_in->diffInMinutes($attendance->clock_out) - $totalBreakMinutes;
                                    @endphp
                                    {{ floor($workMinutes / 60) }}時間{{ $workMinutes % 60 }}分
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    <a href="{{ route('admin.attendance.show', $attendance->id) }}"
                                        class="btn btn-sm btn-primary">詳細</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-muted">この日の勤怠記録がありません</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection