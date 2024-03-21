<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index(Request $request) {
        if (Auth::check()) {
            return view('auth.mypage');
        } else {
            return redirect('/login');
        }
    }
}
