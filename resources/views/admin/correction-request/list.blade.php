@extends('layouts.admin')

@section('title', '管理者 - 修正申請一覧')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">修正申請一覧（管理者）</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="requestTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                            承認待ち ({{ $pendingRequests->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                            承認済み ({{ $approvedRequests->count() }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="requestTabsContent">
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        @if($pendingRequests->count() > 0)
                        <div class="table-responsive mt-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>申請日時</th>
                                        <th>申請者</th>
                                        <th>対象日</th>
                                        <th>申請内容</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingRequests as $request)
                                    <tr>
                                        <td>{{ $request->created_at->format('Y/m/d H:i') }}</td>
                                        <td>{{ $request->user->name }}</td>
                                        <td>{{ $request->attendance->date->format('Y/m/d') }}</td>
                                        <td>
                                            出勤: {{ $request->requested_clock_in ? $request->requested_clock_in->format('H:i') : '-' }}<br>
                                            退勤: {{ $request->requested_clock_out ? $request->requested_clock_out->format('H:i') : '-' }}<br>
                                            備考: {{ $request->requested_note }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.correction-request.approve', $request->id) }}"
                                                class="btn btn-sm btn-primary">承認</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <p class="text-muted">承認待ちの申請がありません</p>
                        </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="approved" role="tabpanel">
                        @if($approvedRequests->count() > 0)
                        <div class="table-responsive mt-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>申請日時</th>
                                        <th>申請者</th>
                                        <th>対象日</th>
                                        <th>申請内容</th>
                                        <th>承認日時</th>
                                        <th>承認者</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedRequests as $request)
                                    <tr>
                                        <td>{{ $request->created_at->format('Y/m/d H:i') }}</td>
                                        <td>{{ $request->user->name }}</td>
                                        <td>{{ $request->attendance->date->format('Y/m/d') }}</td>
                                        <td>
                                            出勤: {{ $request->requested_clock_in ? $request->requested_clock_in->format('H:i') : '-' }}<br>
                                            退勤: {{ $request->requested_clock_out ? $request->requested_clock_out->format('H:i') : '-' }}<br>
                                            備考: {{ $request->requested_note }}
                                        </td>
                                        <td>{{ $request->approved_at ? $request->approved_at->format('Y/m/d H:i') : '-' }}</td>
                                        <td>{{ $request->approvedBy ? $request->approvedBy->name : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <p class="text-muted">承認済みの申請がありません</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection