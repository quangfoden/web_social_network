<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    protected UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function allusers()
    {
        $users = User::with(['roles' => function ($query) {
            $query->where('status', 1);
        }])->get()->toArray();

        $roles = Role::where('status', 1)->get()->toArray();
        $responseData = [
            'status' => 200,
            'success' => true,
            'message' => 'success',
            'data' => ['users' => $users, 'roles' => $roles]
        ];
        return response()->json($responseData);
    }
    public function allaccount()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'user')->where('status', 1);
        })
            ->get()
            ->toArray();

        $responseData = [
            'status' => 200,
            'success' => true,
            'message' => 'success',
            'data' => ['users' => $users]
        ];
        return response()->json($responseData);
    }

    public function roles()
    {
        $roles = Role::all()
            ->toArray();
        $responseData = ['status' => 200, 'success' => true, 'message' => 'success', 'data' => $roles];
        return response()->json($responseData);
    }
    public function createNewUser(Request $request)
    {
        $role = Role::where('id', $request->input('role'))->get()->first();
        $oldUser = User::where('email', $request->input('email'))->get()->first();
        if ($oldUser) {
            $responseData = [
                'status' => 200,
                'success' => false,
                'message' => 'This account already exists'
            ];
            return response()->json($responseData);
        }
        if ($role) {
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'user_name' => $request->input('user_name'),
                'avatar' => '/images/avatar.gif',
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'status' => $request->input('status'),
                'is_lock' => $request->input('is_lock'),
            ]);
            $user->assignRole($role->name);
            $user->load([
                'roles' => function ($query) {
                    $query->where('status', 1);
                },
            ]);
            $responseData = [
                'status' => 200,
                'success' => true,
                'message' => 'The user successfully created',
                'data' => ['user_created' => $user]
            ];
            return response()->json($responseData);
        } else {
            $responseData = [
                'status' => 200,
                'success' => false,
                'message' => 'Role not found'
            ];
            return response()->json($responseData);
        }
    }
    public function changeRoleUser($id, Request $request)
    {
        $user = User::find($id);
        if ($user) {
            $user->load([
                'roles' => function ($query) {
                    $query->where('status', 1);
                }
            ]);
            $roles = Role::where('status', 1)->get();
            foreach ($roles as $role) {
                if ($request->has('role_' . $role->id) && $request->input('role_' . $role->id)) {
                    $user->assignRole($role->name);
                } else {
                    $user->removeRole($role->name);
                }
            }
            $user->load([
                'roles' => function ($query) {
                    $query->where('status', 1);
                },
            ]);
            $status = $request->input('status');
            if ($status === 1) {
                $login_attempts = 0;
            };
            $this->userRepo->update($id, [
                'status' => $status,
                'is_lock' => $request->input('is_lock'),
                'login_attempts' => $login_attempts
            ]);

            $responseData = [
                'status' => 200,
                'success' => true,
                'message' => 'The user successfully updated',
                'data' => ['user_update' => $user]
            ];
            return response()->json($responseData);
        } else {
            $responseData = [
                'status' => 200,
                'success' => true,
                'message' => 'The user not found'
            ];
            return response()->json($responseData);
        }
    }
    public function updateUser(Request $request)
    {
        $user = $request->user();
        $userUpdate = User::find($user->id);
        $userUpdate->last_name = $request->input('last_name');
        $userUpdate->first_name = $request->input('first_name');
        $userUpdate->phone_number = $request->input('phone_number');
        $userUpdate->address = $request->input('address');
        $userUpdate->save();
        $responseData = ['status' => 200, 'success' => true, 'message' => 'The user successfully updated'];
        return response()->json($responseData);
    }
    public function change_password(Request $request)
    {
        $user = $request->user();
        if (!Hash::check($request->input('password'), $user->password)) {
            $responseData = ['status' => 200, 'success' => false, 'message' => 'Password cũ không chính xác !'];
            return response()->json($responseData);
        }
        $password_new = $request->input('password_new');
        $repassword_new = $request->input('repassword_new');
        if ($password_new !== $repassword_new or !$password_new) {
            $responseData = ['status' => 200, 'success' => false, 'message' => 'Cập nhật mật khẩu không thành công ! xác nhận mật khẩu không đúng !'];
            return response()->json($responseData);
        }
        $user->password = Hash::make($password_new);
        $user->save();
        $responseData = ['status' => 200, 'success' => true, 'message' => 'Cập nhật mật khẩu thành công !'];
        return response()->json($responseData);
    }
    public function adminChangePasswordUser($id, Request $request)
    {
        $user = User::find($id);
        $password_new = $request->input('password_new');
        $repassword_new = $request->input('repassword_new');
        if ($password_new !== $repassword_new or !$password_new) {
            $responseData = ['status' => 200, 'success' => false, 'message' => 'Cập nhật mật khẩu không thành công ! xác nhận mật khẩu không đúng !'];
            return response()->json($responseData);
        }
        $user->password = Hash::make($password_new);
        $user->save();
        $responseData = ['status' => 200, 'success' => true, 'message' => 'Cập nhật mật khẩu thành công !'];
        return response()->json($responseData);
    }
}
