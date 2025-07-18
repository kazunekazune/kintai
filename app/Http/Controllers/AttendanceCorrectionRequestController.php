<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceCorrectionRequest;
use Illuminate\Support\Facades\Auth;

class AttendanceCorrectionRequestController extends Controller
{
    public function list()
    {
        $user = Auth::user();
        $correctionRequests = AttendanceCorrectionRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('correction-request.list', compact('correctionRequests'));
    }
}
