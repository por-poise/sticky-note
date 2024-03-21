<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function show()
    {
        return view('auth.forgot_password');
    }

    public function send(Request $request)
    {
        Log::debug("start ForgotPasswordController.send");
        $request->validate([
            'email' => 'required|email',
       ]);

       $status = Password::sendResetLink(
           $request->only('email')
       );

       Log::debug("status: $status");

       return $status === Password::RESET_LINK_SENT
           ? redirect('/sent_forgot_password')
           : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
