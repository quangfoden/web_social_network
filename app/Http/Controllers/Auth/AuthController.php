<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Events\SetDefaultUserRole;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
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

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if ($user) {
            $res = [
                'response_index' => true,
                'response_type' => 'error',
                'response_data' => [
                    "success",
                    "user_data" => $user
                ],
                'authenticated' => true,
            ];
            $user->assignRole('user');
        }
        return response()->json($res, 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
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

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $userRoles = User::with('roles:name')->find($user->id)->roles->pluck('name');
            $status = $user->status;
            $res = [
                'response_index' => true,
                'response_type' => 'success',
                'response_data' => ['You Have Logged In Successfully'],
                'authenticated' => true,
                'status' => $status,
                'role' => $userRoles
            ];

            return response()->json($res, 200);
        } else {
            $res = [
                'response_index' => true,
                'response_type' => 'error',
                'response_data' => ['Given Credentials Does Not Match Our Record'],
                'authenticated' => false,
            ];

            return response()->json($res, 200);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
