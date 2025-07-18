<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakRecord;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // テストユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        // 管理者ユーザーを作成
        $admin = User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // 今日の勤怠データを作成
        $today = Carbon::today();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'clock_in' => Carbon::parse('09:00'),
            'clock_out' => Carbon::parse('18:00'),
            'status' => '退勤済',
            'memo' => '通常勤務',
            'note' => '特に問題なし',
        ]);

        // 休憩記録を作成（テーブル名を確認）
        try {
            \App\Models\BreakRecord::create([
                'attendance_id' => $attendance->id,
                'break_start' => Carbon::parse('12:00'),
                'break_end' => Carbon::parse('13:00'),
            ]);
        } catch (\Exception $e) {
            // テーブルが存在しない場合はスキップ
            echo "BreakRecord table not found, skipping...\n";
        }

        // 昨日の勤怠データを作成
        $yesterday = Carbon::yesterday();
        Attendance::create([
            'user_id' => $user->id,
            'date' => $yesterday,
            'clock_in' => Carbon::parse('08:30'),
            'clock_out' => Carbon::parse('17:30'),
            'status' => '退勤済',
            'memo' => '早めに出勤',
            'note' => '体調良好',
        ]);

        // 一昨日の勤怠データを作成
        $dayBeforeYesterday = Carbon::yesterday()->subDay();
        Attendance::create([
            'user_id' => $user->id,
            'date' => $dayBeforeYesterday,
            'clock_in' => Carbon::parse('09:15'),
            'clock_out' => Carbon::parse('18:15'),
            'status' => '退勤済',
            'memo' => '通常勤務',
            'note' => '特に問題なし',
        ]);
    }
}
