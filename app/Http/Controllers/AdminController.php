<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceCorrectionRequest;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * 管理者用勤怠一覧画面
     */
    public function attendanceList(Request $request)
    {
        $date = $request->get('date') ? Carbon::parse($request->get('date')) : Carbon::today();

        $attendances = Attendance::with(['user', 'breaks'])
            ->whereDate('date', $date)
            ->orderBy('user_id')
            ->orderBy('date')
            ->get();

        return view('admin.attendance.list', compact('attendances', 'date'));
    }

    /**
     * 管理者用勤怠詳細画面
     */
    public function attendanceShow($id)
    {
        $attendance = Attendance::with(['user', 'breaks'])->findOrFail($id);

        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * 管理者用勤怠更新
     */
    public function attendanceUpdate(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'clock_in_time' => 'required|date_format:H:i',
            'clock_out_time' => 'required|date_format:H:i',
            'break_start_time' => 'nullable|date_format:H:i',
            'break_end_time' => 'nullable|date_format:H:i',
            'note' => 'required|string|max:1000',
        ]);

        // 出勤・退勤時刻を更新
        $attendance->clock_in = $attendance->date->format('Y-m-d') . ' ' . $request->clock_in_time;
        $attendance->clock_out = $attendance->date->format('Y-m-d') . ' ' . $request->clock_out_time;
        $attendance->note = $request->note;
        $attendance->save();

        // 休憩時間を更新
        if ($request->break_start_time && $request->break_end_time) {
            $break = $attendance->breaks->first();
            if (!$break) {
                $break = $attendance->breaks()->create([]);
            }
            $break->break_start = $attendance->date->format('Y-m-d') . ' ' . $request->break_start_time;
            $break->break_end = $attendance->date->format('Y-m-d') . ' ' . $request->break_end_time;
            $break->save();
        }

        return redirect()->route('admin.attendance.show', $attendance->id)
            ->with('success', '勤怠情報を更新しました。');
    }

    /**
     * スタッフ一覧画面
     */
    public function staffList()
    {
        $users = User::orderBy('name')->get();

        return view('admin.staff.list', compact('users'));
    }

    /**
     * スタッフ別勤怠一覧画面
     */
    public function staffAttendanceList(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $month = $request->get('month') ? Carbon::parse($request->get('month') . '-01') : Carbon::now()->startOfMonth();

        $attendances = Attendance::with(['breaks'])
            ->where('user_id', $id)
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->orderBy('date')
            ->get();

        return view('admin.staff.attendance.list', compact('user', 'attendances', 'month'));
    }

    /**
     * 修正申請一覧画面（管理者）
     */
    public function correctionRequestList()
    {
        $pendingRequests = AttendanceCorrectionRequest::with(['user', 'attendance'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedRequests = AttendanceCorrectionRequest::with(['user', 'attendance', 'approvedBy'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.correction-request.list', compact('pendingRequests', 'approvedRequests'));
    }

    /**
     * 修正申請承認画面
     */
    public function correctionRequestApprove($id)
    {
        $request = AttendanceCorrectionRequest::with(['user', 'attendance.breaks'])->findOrFail($id);

        return view('admin.correction-request.approve', compact('request'));
    }

    /**
     * 修正申請承認処理
     */
    public function correctionRequestApprovePost($id)
    {
        $correctionRequest = AttendanceCorrectionRequest::with(['attendance'])->findOrFail($id);

        // 勤怠情報を更新
        $attendance = $correctionRequest->attendance;

        if ($correctionRequest->requested_clock_in) {
            $attendance->clock_in = $attendance->date->format('Y-m-d') . ' ' . $correctionRequest->requested_clock_in->format('H:i');
        }

        if ($correctionRequest->requested_clock_out) {
            $attendance->clock_out = $attendance->date->format('Y-m-d') . ' ' . $correctionRequest->requested_clock_out->format('H:i');
        }

        if ($correctionRequest->requested_note) {
            $attendance->note = $correctionRequest->requested_note;
        }

        $attendance->save();

        // 休憩時間を更新
        if ($correctionRequest->requested_break_start && $correctionRequest->requested_break_end) {
            $break = $attendance->breaks->first();
            if (!$break) {
                $break = $attendance->breaks()->create([]);
            }
            $break->break_start = $attendance->date->format('Y-m-d') . ' ' . $correctionRequest->requested_break_start->format('H:i');
            $break->break_end = $attendance->date->format('Y-m-d') . ' ' . $correctionRequest->requested_break_end->format('H:i');
            $break->save();
        }

        // 修正申請を承認済みに更新
        $correctionRequest->status = 'approved';
        $correctionRequest->approved_by = Auth::id();
        $correctionRequest->approved_at = now();
        $correctionRequest->save();

        return redirect()->route('admin.correction-request.list')
            ->with('success', '修正申請を承認しました。');
    }

    /**
     * 修正申請詳細画面
     */
    public function correctionRequestShow($id)
    {
        $correctionRequest = AttendanceCorrectionRequest::with(['user', 'attendance.breaks'])->findOrFail($id);

        return view('admin.correction-request.show', compact('correctionRequest'));
    }
}
