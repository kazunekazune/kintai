@extends('layouts.admin')

@section('title', '修正申請詳細')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">修正申請詳細</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">氏名</label>
                    </div>
                    <div class="col-md-9">
                        <p class="form-control-plaintext">{{ $correctionRequest->user->name }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">日付</label>
                    </div>
                    <div class="col-md-9">
                        <p class="form-control-plaintext">{{ $correctionRequest->attendance->date->format('Y年m月d日') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">出勤・退勤</label>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-muted">現在の記録</label>
                                <p class="form-control-plaintext">
                                    {{ $correctionRequest->attendance->clock_in ? $correctionRequest->attendance->clock_in->format('H:i') : '未記録' }} ～
                                    {{ $correctionRequest->attendance->clock_out ? $correctionRequest->attendance->clock_out->format('H:i') : '未記録' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">申請内容</label>
                                <p class="form-control-plaintext">
                                    {{ $correctionRequest->requested_clock_in ? $correctionRequest->requested_clock_in->format('H:i') : '未記録' }} ～
                                    {{ $correctionRequest->requested_clock_out ? $correctionRequest->requested_clock_out->format('H:i') : '未記録' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">休憩時間</label>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-muted">現在の記録</label>
                                <p class="form-control-plaintext">
                                    {{ $correctionRequest->attendance->break_start ? $correctionRequest->attendance->break_start->format('H:i') : '未記録' }} ～
                                    {{ $correctionRequest->attendance->break_end ? $correctionRequest->attendance->break_end->format('H:i') : '未記録' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">申請内容</label>
                                <p class="form-control-plaintext">
                                    {{ $correctionRequest->requested_break_start ? $correctionRequest->requested_break_start->format('H:i') : '未記録' }} ～
                                    {{ $correctionRequest->requested_break_end ? $correctionRequest->requested_break_end->format('H:i') : '未記録' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">勤務時間</label>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-muted">現在の記録</label>
                                <p class="form-control-plaintext">
                                    @if($correctionRequest->attendance->work_hours)
                                    {{ number_format($correctionRequest->attendance->work_hours, 1) }}時間
                                    @else
                                    未計算
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">申請内容</label>
                                <p class="form-control-plaintext">
                                    @if($correctionRequest->requested_work_hours)
                                    {{ number_format($correctionRequest->requested_work_hours, 1) }}時間
                                    @else
                                    未計算
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">申請理由</label>
                    </div>
                    <div class="col-md-9">
                        <p class="form-control-plaintext">{{ $correctionRequest->reason ?: '理由なし' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">申請日時</label>
                    </div>
                    <div class="col-md-9">
                        <p class="form-control-plaintext">{{ $correctionRequest->created_at->format('Y年m月d日 H:i') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">ステータス</label>
                    </div>
                    <div class="col-md-9">
                        @if($correctionRequest->status === 'pending')
                        <span class="badge bg-warning">承認待ち</span>
                        @elseif($correctionRequest->status === 'approved')
                        <span class="badge bg-success">承認済み</span>
                        @elseif($correctionRequest->status === 'rejected')
                        <span class="badge bg-danger">却下</span>
                        @endif
                    </div>
                </div>

                @if($correctionRequest->status !== 'pending')
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">処理日時</label>
                    </div>
                    <div class="col-md-9">
                        <p class="form-control-plaintext">{{ $correctionRequest->processed_at ? $correctionRequest->processed_at->format('Y年m月d日 H:i') : '未処理' }}</p>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.correction-request.list') }}" class="btn btn-secondary">戻る</a>
                    @if($correctionRequest->status === 'pending')
                    <div>
                        <a href="{{ route('admin.correction-request.approve', $correctionRequest) }}" class="btn btn-success">承認</a>
                        <a href="{{ route('admin.correction-request.reject', $correctionRequest) }}" class="btn btn-danger">却下</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection