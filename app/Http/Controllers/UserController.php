<?php

namespace App\Http\Controllers;



use App\Models\User;
use App\Mail\OtpMail;
use App\Helper\jwtToken;
use Illuminate\Http\Request;
use App\Http\Requests\UserLogin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function profilePage()
    {
        return view('pages.dashboard.profile-page');
    }

    public function userLoginPage()
    {
        // $token = jwtToken::createToken('user@mail.com', 5);
        // $payload = jwtToken::verifyToken($token);
        //dd($request->cookie('token'));
        //dd(base64_decode('eyJpc3MiOiJleGFtcGxlIiwidXNlcl9pZCI6NSwidXNlcl9lbWFpbCI6InVzZXJAbWFpbC5jb20iLCJleHAiOjE3NDczMDY5NTcsImlhdCI6MTc0NzIyMDU1N30 '));
        //print_r(explode('.', $token));



        // return response()->json('success')->cookie('token', $token);
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

            $validator = Validator::make($request->all(), [
                'email' => "required",
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'User validation fails',
                    'status' => 'fail'
                ], 422);
            }

            $user = User::where('email', $request->email)->where('password', $request->password)->first();
            if ($user) {

                $token = jwtToken::createToken($user->email, $user->id);
                return response()->json(
                    [
                        'message' => 'Logged in successfully',
                        'data' => $user,
                        'status' => 'success'
                    ],
                    200
                )->cookie('token', $token, time() + 3600 * 24);
            } else {
                return response()->json([
                    'message' => 'User not found',
                    'status' => 'failed'
                ], 401);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'message' => 'Internal server error',
                'status' => 'failed'
            ], 500);
        }
    }
    public function userRegistrationPage()
    {


        return view('pages.auth.registration-page');
    }
    public function userRegistration(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:50|min:3|string',
                'last_name' => 'required|max:50|min:3|string',
                'email'      => 'required|max:50|unique:users',
                'phone' => 'max:20|nullable',
                'password' => 'required|string|max:255|min:3'
            ]);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'errors' => $validator->errors(),
                        'message' => 'Validation fails',
                        'status' => 'failed',
                    ],
                    422
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
                'status' => 'success',
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'status' => 'failed',
                'message' => 'Internal server error'
            ], 500);
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
                'password' => 'required|min:3|confirmed',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                    'status' => 'failed'

                ], 422);
            }
            $password = $request->password;
            $email = $request->header('user_email');
            $user = User::where('email', $email)->first()->update(['password' => $password]);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                    'status' => 'failed',
                ], 401);
            }
            return response()->json([
                'message' => 'Password reset successfully',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'There is an error',
            ], 500);
        }
    }
    public function sendOtpPage()
    {
        return view('pages.auth.send-otp-page');
    }
    function sendOtp(Request $request)
    {
        try {

            $email = $request->email;
            $validation = Validator::make($request->all(), [
                "email" => 'required|email:filter_unicode',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'errors' => $validation->errors(),
                    'status' => 'failed',
                    'message' => 'Validation failed',
                ], 422);
            }
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                    'status' => 'failed',
                ], 401);
            }
            $otp = rand(1000, 9999);

            Mail::to($user)
                ->send(new OtpMail($otp));
            $user->update(['otp' => $otp]);
            return response()->json([
                'message' => 'Mil sent successfully',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => "failed",
                'message' => 'Internal server error'
            ], 500);
        }
    }
    public function verifyOtpPage()
    {
        return view('pages.auth.verify-otp-page');
    }
    public function verifyOtp(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'otp' => 'required|size:4',
                'email' => 'required|email:filter_unicode'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Validation Failed',
                    'errors' => $validation->errors(),
                    'status' => 'failed'
                ], 422);
            }
            $otp = $request->otp;
            $email = $request->email;
            $user = User::where(['email' => $email, 'otp' => $otp])->first();
            if (!$user) {
                return response()->json([
                    'message' => 'Otp or Email don not match',
                    'status' => 'failed',
                ], 401);
            }
            $user->update(['otp' => 0]);
            $token = jwtToken::createTokenForPasswordReset($user->email);
            return response()->json([
                'message' => 'Otp verifies successfully',
                'status' => 'success',
            ])->cookie('token', $token, time() + 3600 * 24);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'status' => 'failed',
            ], 500);
        }
    }
}
