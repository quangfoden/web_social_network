<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Services\AuthService;
use App\Events\SetDefaultUserRole;
use App\Http\Requests\Auth\LoginRequest;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            $msg = [];

            foreach (array_values($validator->errors()->toArray()) as $val) {
                foreach ($val as $error) {
                    $msg[] = $error;
                }
            }

            $res = [
                'response_index' => true,
                'response_type' => 'error',
                'response_data' => $msg,
                'authenticated' => false,
            ];

            return response()->json($res, 200);
        }

        if ($request->password !== $request->password_confirmation) {
            $res = [
                'response_index' => true,
                'response_type' => 'error',
                'response_data' => ["Repassword fail"],
                'authenticated' => false,
            ];
            return response()->json($res, 200);
        }

        $checkData = $this->authService->registerUser($request->all());
        if ($checkData['success']) {
            $res = [
                'response_index' => true,
                'response_type' => 'success',
                'response_data' =>  [$checkData['data']],
                'authenticated' => true,
            ];
        } else {
            $res = [
                'response_index' => true,
                'response_type' => 'error',
                'response_data' =>  [$checkData['message']],
                'authenticated' => false,
            ];
        }
        return response()->json($res, 200);
    }
    public function login(LoginRequest $request)
    {
        $validator = $request->validated();
        $checkData = $this->authService->checkUser($validator);
        if (!$checkData['success']) {
            return response()->json([
                'response_index' => true,
                'response_type' => 'error',
                'response_data' => [$checkData['message']],
                'authenticated' => false,
            ]);
        } else {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            $userRoles = User::with('roles:name')->find($user->id)->roles->pluck('name');
            $status = $user->status;
            return response()->json([
                'response_index' => true,
                'response_type' => 'success',
                'response_data' => ['Bạn đã đăng nhập thành công.'],
                'authenticated' => true,
                'status' => $status,
                'role' => $userRoles,
                'access_token' => $token, // Trả về token
                'token_type' => 'Bearer'
            ]);
        }
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
        return response()->json(['message' => 'Logged out successfully']);
    }
}
