<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceCorrectionRequestController;

// ホームページ - ログイン済みの場合は適切な画面にリダイレクト
Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        if (\Illuminate\Support\Facades\Auth::user()->is_admin) {
            return redirect()->route('admin.attendance.list');
        }
        return redirect()->route('attendance.index');
    }
    return redirect()->route('login');
})->name('home');

// カスタムログインルート
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ], [
        'email.required' => 'メールアドレスを入力してください',
        'email.email' => '有効なメールアドレスを入力してください',
        'password.required' => 'パスワードを入力してください',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $credentials = request()->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput();
    }

    request()->session()->regenerate();

    if (Auth::user()->is_admin) {
        return redirect()->intended(route('admin.attendance.list'));
    }

    return redirect()->intended(route('attendance.index'));
})->name('login.post');

// 一般ユーザー用ルート
Route::middleware(['auth'])->group(function () {
    // 勤怠打刻画面
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])->name('attendance.break-start');
    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])->name('attendance.break-end');

    // 勤怠一覧画面
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');

    // 勤怠詳細画面
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/{id}/update', [AttendanceController::class, 'update'])->name('attendance.update');

    // 修正申請一覧画面
    Route::get('/stamp_correction_request/list', [AttendanceCorrectionRequestController::class, 'list'])->name('correction-request.list');
});

// 管理者ログイン画面（認証前）
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

// 管理者ログイン処理
Route::post('/admin/login', function () {
    $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ], [
        'email.required' => 'メールアドレスを入力してください',
        'email.email' => '有効なメールアドレスを入力してください',
        'password.required' => 'パスワードを入力してください',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    if (Auth::attempt(request()->only('email', 'password'))) {
        $user = Auth::user();
        if ($user->is_admin) {
            request()->session()->regenerate();
            return redirect()->intended(route('admin.attendance.list'));
        } else {
            Auth::logout();
            return back()->withErrors([
                'email' => '管理者権限がありません。',
            ])->withInput();
        }
    }

    return back()->withErrors([
        'email' => 'ログイン情報が登録されていません',
    ])->withInput();
})->name('admin.login.post');

// 管理者用ルート
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // 勤怠一覧画面（管理者）
    Route::get('/attendance/list', [AdminController::class, 'attendanceList'])->name('admin.attendance.list');

    // 勤怠詳細画面（管理者）
    Route::get('/attendance/{id}', [AdminController::class, 'attendanceShow'])->name('admin.attendance.show');
    Route::post('/attendance/{id}/update', [AdminController::class, 'attendanceUpdate'])->name('admin.attendance.update');

    // スタッフ一覧画面
    Route::get('/staff/list', [AdminController::class, 'staffList'])->name('admin.staff.list');

    // スタッフ別勤怠一覧画面
    Route::get('/attendance/staff/{id}', [AdminController::class, 'staffAttendanceList'])->name('admin.staff.attendance.list');

    // 修正申請一覧画面（管理者）
    Route::get('/correction-request/list', [AdminController::class, 'correctionRequestList'])->name('admin.correction-request.list');

    // 修正申請承認画面
    Route::get('/correction-request/approve/{id}', [AdminController::class, 'correctionRequestApprove'])->name('admin.correction-request.approve');
    Route::post('/correction-request/approve/{id}', [AdminController::class, 'correctionRequestApprovePost'])->name('admin.correction-request.approve.post');

    // 修正申請詳細画面
    Route::get('/correction-request/{id}', [AdminController::class, 'correctionRequestShow'])->name('admin.correction-request.show');
});
