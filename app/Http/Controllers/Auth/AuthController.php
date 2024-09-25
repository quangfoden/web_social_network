<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PasswordResetToken;
use App\Services\AuthService;
use App\Events\SetDefaultUserRole;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(RegisterRequest $request)
    {
        $checkData = $this->authService->registerUser($request->all());
        if ($checkData['success']) {
            return response()->json([
                'response_index' => true,
                'response_type' => 'success',
                'response_data' => [$checkData['data']],
                'authenticated' => true,
            ], 200);
        } else {
            return response()->json([
                'response_index' => true,
                'response_type' => 'error',
                'response_data' => [$checkData['message']],
                'authenticated' => false,
            ], 200);
        }
    }
    public function loginUser($credentials, $remember)
    {
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            return $user;
        }
    
        return null;
    }
    
    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if ($user) {
            $user->update([
                'email_verified_at' => now(),
                'verification_token' => null,
            ]);

            return redirect('/login')->with('status', 'Email đã được xác thực thành công.');
        }

        return redirect('/error')->with('error', 'Mã xác thực không hợp lệ.');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = Str::random(60);
            PasswordResetToken::where('email', $request->email)->delete();
            PasswordResetToken::create([
                'email' => $request->email,
                'token' => $token
            ]);
            Mail::to($request->email)->send(new ResetPasswordMail($user, $token));

            return response()->json(['status' => 'Vui lòng kiểm tra email của bạn.'], 200);
        }

        return response()->json(['error' => 'Email không tồn tại.'], 404);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed|',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $resetToken = PasswordResetToken::where('token', $request->input('token'))->first();

        if (!$resetToken || $resetToken->email !== $request->input('email')) {
            return redirect('/error')->with('error', 'Mã xác thực không hợp lệ.');
        }

        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return redirect('/error')->with('error', 'Mã xác thực không hợp lệ.');
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        PasswordResetToken::where('token', $request->input('token'))->delete();

        return response()->json(['status' => 'Đổi mật khẩu thành công.'], 200);
    }



    public function login(LoginRequest $request)
    {
        $validator = $request->validated();
        $remember = $request->input('remember',false);
        $checkData = $this->authService->checkUser($validator);
        if (!$checkData['success']) {
            return response()->json([
                'response_index' => true,
                'response_type' => 'error',
                'response_data' => [$checkData['message']],
                'authenticated' => false,
            ]);
        } else {
            $user = $this->authService->loginUser($validator, $remember);
            $userRoles = User::with('roles:name')->find($user->id)->roles->pluck('name');
            $status = $user->status;
           
            return response()->json([
                'response_index' => true,
                'response_type' => 'success',
                'response_data' => ['Bạn đã đăng nhập thành công.'],
                'authenticated' => true,
                'status' => $status,
                'role' => $userRoles,
            ]);
        }
    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return response()->json(['message' => 'Không có refresh token'], 401);
        }

        $tokenInstance = PersonalAccessToken::findToken($refreshToken);

        if (!$tokenInstance || $tokenInstance->tokenable->id !== Auth::id()) {
            return response()->json(['message' => 'Refresh token không hợp lệ'], 401);
        }

        // Tạo Access Token mới
        $newAccessToken = $tokenInstance->tokenable->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $newAccessToken,
            'token_type' => 'Bearer',
            'expires_in' => 3600 
        ]);
    }


    public function loginWithFaceID(Request $request)
    {
        $user_id = $request['user_id'];
        $username = $request['username'];
        if ($user_id && $username) {
            $user = User::where('id', $user_id)
                ->where('user_name', $username)
                ->first();
            if ($user) {
                Auth::login($user);
                $userRoles = User::with('roles:name')->find($user->id)->roles->pluck('name');
                $status = $user->status;
                return response()->json([
                    'response_index' => true,
                    'response_type' => 'success',
                    'response_data' => ['Bạn đã đăng nhập thành công.'],
                    'authenticated' => true,
                    'status' => $status,
                    'role' => $userRoles
                ]);
            }
        }
        return response()->json([
            'response_index' => true,
            'response_type' => 'error',
            'response_data' => 'Vui lòng thử lại sau',
            'authenticated' => false,
        ], 401);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
    
        $response = response()->json([
            'message' => 'Logged out successfully',
        ]);
    
        $response->withCookie(cookie()->forget('refresh_token'));
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return $response;
    }
    
}
