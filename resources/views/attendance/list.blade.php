@extends('layouts.app')

@section('title', '勤怠一覧')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">勤怠一覧</h4>
                <div>
                    <a href="?month={{ \Carbon\Carbon::parse($month)->subMonth()->format('Y-m') }}" class="btn btn-outline-primary">前月</a>
                    <span class="mx-2">{{ \Carbon\Carbon::parse($month)->format('Y年m月') }}</span>
                    <a href="?month={{ \Carbon\Carbon::parse($month)->addMonth()->format('Y-m') }}" class="btn btn-outline-primary">翌月</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>日付</th>
                                <th>出勤時刻</th>
                                <th>退勤時刻</th>
                                <th>勤務時間</th>
                                <th>休憩時間</th>
                                <th>実働時間</th>
                                <th>ステータス</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date->format('m/d') }}</td>
                                <td>{{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : '-' }}</td>
                                <td>{{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '-' }}</td>
                                <td>
                                    @if($attendance->clock_in && $attendance->clock_out)
                                    @php
                                    $workHours = \Carbon\Carbon::parse($attendance->clock_in)->diffInHours(\Carbon\Carbon::parse($attendance->clock_out));
                                    $workMinutes = \Carbon\Carbon::parse($attendance->clock_in)->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_out)) % 60;
                                    @endphp
                                    {{ $workHours }}時間{{ $workMinutes > 0 ? $workMinutes . '分' : '' }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @php
                                    $totalBreakTime = 0;
                                    foreach($attendance->breaks as $break) {
                                    if($break->break_start && $break->break_end) {
                                    $totalBreakTime += \Carbon\Carbon::parse($break->break_start)->diffInMinutes(\Carbon\Carbon::parse($break->break_end));
                                    }
                                    }
                                    $breakHours = floor($totalBreakTime / 60);
                                    $breakMinutes = $totalBreakTime % 60;
                                    @endphp
                                    {{ $totalBreakTime > 0 ? $breakHours . '時間' . ($breakMinutes > 0 ? $breakMinutes . '分' : '') : '-' }}
                                </td>
                                <td>
                                    @if($attendance->clock_in && $attendance->clock_out)
                                    @php
                                    $workTime = \Carbon\Carbon::parse($attendance->clock_in)->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_out));
                                    $actualWorkTime = $workTime - $totalBreakTime;
                                    $actualHours = floor($actualWorkTime / 60);
                                    $actualMinutes = $actualWorkTime % 60;
                                    @endphp
                                    {{ $actualHours }}時間{{ $actualMinutes > 0 ? $actualMinutes . '分' : '' }}
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
                                    <a href="{{ route('attendance.show', $attendance->id) }}" class="btn btn-sm btn-primary">詳細</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">勤怠記録がありません</td>
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