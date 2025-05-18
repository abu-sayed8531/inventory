<?php

namespace App\Http\Controllers;

use PDO;
use App\Models\User;
use App\Mail\OtpMail;
use App\Helper\jwtToken;
use Illuminate\Http\Request;
use App\Http\Requests\UserLogin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function profilePage()
    {
        return view('pages.dashboard.profile-page');
    }
    public function userLoginPage(Request $request)
    {
        // $token = jwtToken::createToken('user@mail.com', 5);
        // $payload = jwtToken::verifyToken($token);
        //dd($request->cookie('token'));
        //dd(base64_decode('eyJpc3MiOiJleGFtcGxlIiwidXNlcl9pZCI6NSwidXNlcl9lbWFpbCI6InVzZXJAbWFpbC5jb20iLCJleHAiOjE3NDczMDY5NTcsImlhdCI6MTc0NzIyMDU1N30 '));
        //print_r(explode('.', $token));



        // return response()->json('success')->cookie('token', $token);
        return view('pages.auth.login-page');
    }
    function userLogin(UserLogin $request)
    {
        try {

            $validated = $request->validated();
            $user = User::where('email', $validated['email'])->where('password', $validated['password'])->first();
            if ($user) {

                $token = jwtToken::createToken($user->email, $user->id);
                return response()->json(
                    [

                        'data' => $user,
                        'status' => 'success'
                    ]
                )->cookie('token', $token, time() + 3600 * 24);
            }
        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }
    public function userRegistrationPage()
    {

        return view('pages.auth.registration-page');
    }
    public function userRegistration(Request $request)
    {
        try {

            $validator = Validator::mak($request->all(), [
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'email'      => 'required|max:50',
                'phone' => 'max:20|nullable',
                'password' => 'required|string|max:255|min:3'
            ]);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'errors' => $validator->errors(),
                        'message' => 'Validation fails',
                        'status' => 422,
                    ]
                );
            }
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $request->password,
                'mobile'  => $request->phone,
            ]);

            return response()->json([
                'data' => $user,
                'message' => 'User created successfully',
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    function logout(Request $request)
    {
        $userId = $request->header('user_id');
        $email = $request->header('user_email');
        $user = User::where(['id' => $userId, 'email' => $email])->first();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
                'status' => 401,
            ]);
        }
        return response()->json([
            'message' => 'Logout successfully',
            'status' => 200,
        ])->cookie('token', null, -1);
    }
    public function resetPasswordPage()
    {
        return view('pages.auth.reset-pass-page');
    }
    public function resetPassword(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'password' => 'required|min:3',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                    'message' => 'Validation failed',

                ]);
            }
            $password = $request->password;
            $email = $request->header('user_email');
            $user = User::where('email', $email)->first()->update(['password' => $password]);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                    'status' => 404,
                ]);
            }
            return response()->json([
                'message' => 'Password reset successfully',
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'There is an error',
            ]);
        }
    }
    public function sendOtpPage()
    {
        return view('pages.auth.send-otp-page');
    }
    function sendOtp(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => 404,
            ]);
        }
        $otp = rand(1000, 9999);
        $user->update(['otp' => $otp]);
        Mail::to($email)->send(new OtpMail($otp));
        return response()->json([
            'message' => 'Mil sent successfully',
            'status' => 200,
        ]);
    }
    public function verifyOtpPage()
    {
        return view('pages.auth.verify-otp-page');
    }
    public function verifyOtp(Request $request)
    {
        $otp = $request->otp;
        $email = $request->email;
        $user = User::where(['email' => $email, 'otp' => $otp])->first();
        if (!$user) {
            return response()->json([
                'message' => 'Otp or Email don not match',
                'status' => '404',
            ]);
        }
        $user->update(['otp' => 0]);
        $token = jwtToken::createTokenForPasswordReset($user->email);
        return response()->json([
            'message' => 'Otp verifies successfully',
            'status' => 200,
        ])->cookie('token', $token, time() + 3600 * 24);
    }
}
