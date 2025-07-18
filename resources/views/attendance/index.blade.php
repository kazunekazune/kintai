@extends('layouts.app')

@section('title', '勤怠打刻')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <div class="mb-4">
            <span class="badge bg-secondary mb-2">
                @switch($attendance->status ?? '勤務外')
                @case('勤務外')
                勤務時間
                @break
                @case('出勤中')
                勤務中
                @break
                @case('休憩中')
                休憩中
                @break
                @case('退勤済')
                退勤済
                @break
                @endswitch
            </span>
            <h2 class="text-dark">{{ now()->format('Y年m月d日(D)') }}</h2>
            <h1 class="display-4 text-dark fw-bold">{{ now()->format('H:i') }}</h1>
        </div>

        <div class="d-grid gap-3">
            @if(($attendance->status ?? '勤務外') === '勤務外')
            <form action="{{ route('attendance.clock-in') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-dark btn-lg w-100">
                    出勤
                </button>
            </form>
            @endif

            @if(($attendance->status ?? '') === '出勤中')
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('attendance.clock-out') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-dark btn-lg w-100">
                            退勤
                        </button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('attendance.break-start') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark btn-lg w-100">
                            休憩入
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if(($attendance->status ?? '') === '休憩中')
            <form action="{{ route('attendance.break-end') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-dark btn-lg w-100">
                    休憩戻
                </button>
            </form>
            @endif

            @if(($attendance->status ?? '') === '退勤済')
            <div class="alert alert-light border">
                本日の業務は終了しました。
            </div>
            @endif
        </div>
    </div>
</div>
@endsection