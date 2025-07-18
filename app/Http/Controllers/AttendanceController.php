<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\BreakRecord;
use App\Http\Requests\AttendanceUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
            
        if (!$attendance) {
            $attendance = new Attendance([
                'user_id' => $user->id,
                'date' => $today,
                'status' => '勤務外'
            ]);
        }
        
        return view('attendance.index', compact('attendance'));
    }

    public function clockIn()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
            
        if (!$attendance) {
            $attendance = new Attendance([
                'user_id' => $user->id,
                'date' => $today,
                'status' => '勤務外'
            ]);
        }
        
        if ($attendance->status === '勤務外') {
            $attendance->clock_in = Carbon::now();
            $attendance->status = '出勤中';
            $attendance->save();
        }
        
        return redirect()->route('attendance.index')->with('success', '出勤しました。');
    }

    public function clockOut()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
            
        if ($attendance && $attendance->status === '出勤中') {
            $attendance->clock_out = Carbon::now();
            $attendance->status = '退勤済';
            $attendance->save();
            
            return redirect()->route('attendance.index')->with('success', 'お疲れ様でした。');
        }
        
        return redirect()->route('attendance.index')->with('error', '出勤していません。');
    }

    public function breakStart()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
            
        if ($attendance && $attendance->status === '出勤中') {
            $attendance->status = '休憩中';
            $attendance->save();
            
            // 休憩記録を作成
            BreakRecord::create([
                'attendance_id' => $attendance->id,
                'break_start' => Carbon::now(),
            ]);
            
            return redirect()->route('attendance.index')->with('success', '休憩開始しました。');
        }
        
        return redirect()->route('attendance.index')->with('error', '出勤していません。');
    }

    public function breakEnd()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
            
        if ($attendance && $attendance->status === '休憩中') {
            $attendance->status = '出勤中';
            $attendance->save();
            
            // 最新の休憩記録を更新
            $breakRecord = $attendance->breaks()->latest()->first();
            if ($breakRecord) {
                $breakRecord->update([
                    'break_end' => Carbon::now(),
                ]);
            }
            
            return redirect()->route('attendance.index')->with('success', '休憩終了しました。');
        }
        
        return redirect()->route('attendance.index')->with('error', '休憩中ではありません。');
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        
        $attendances = Attendance::where('user_id', $user->id)
            ->whereYear('date', Carbon::parse($month)->year)
            ->whereMonth('date', Carbon::parse($month)->month)
            ->orderBy('date', 'desc')
            ->get();
            
        return view('attendance.list', compact('attendances', 'month'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $attendance = Attendance::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        return view('attendance.show', compact('attendance'));
    }

    public function update(AttendanceUpdateRequest $request, $id)
    {
        $user = Auth::user();
        $attendance = Attendance::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        // 修正申請を作成
        $attendance->correctionRequests()->create([
            'user_id' => $user->id,
            'requested_clock_in' => $request->clock_in_time ? Carbon::parse($request->clock_in_time) : null,
            'requested_clock_out' => $request->clock_out_time ? Carbon::parse($request->clock_out_time) : null,
            'requested_break_start' => $request->break_start_time ? Carbon::parse($request->break_start_time) : null,
            'requested_break_end' => $request->break_end_time ? Carbon::parse($request->break_end_time) : null,
            'requested_note' => $request->note,
            'status' => '承認待ち',
        ]);
        
        return redirect()->route('attendance.show', $id)->with('success', '修正申請を送信しました。');
    }
}
