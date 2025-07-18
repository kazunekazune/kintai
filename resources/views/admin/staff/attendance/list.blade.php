@extends('layouts.admin')

@section('title', '管理者 - スタッフ別勤怠一覧')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $user->name }}さんの勤怠一覧</h4>
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.staff.attendance.list', ['id' => $user->id, 'month' => $month->copy()->subMonth()->format('Y-m')]) }}"
                        class="btn btn-outline-secondary">前月</a>
                    <span class="btn btn-outline-primary">{{ $month->format('Y年m月') }}</span>
                    <a href="{{ route('admin.staff.attendance.list', ['id' => $user->id, 'month' => $month->copy()->addMonth()->format('Y-m')]) }}"
                        class="btn btn-outline-secondary">翌月</a>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('admin.staff.attendance.csv', ['id' => $user->id, 'month' => $month->format('Y-m')]) }}"
                        class="btn btn-success">CSV出力</a>
                </div>

                @if($attendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>日付</th>
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
                                <td>{{ $attendance->date->format('m/d') }}</td>
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
                    <p class="text-muted">この月の勤怠記録がありません</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection