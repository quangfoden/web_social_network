<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class UsersController extends Controller
{
    protected UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function getUserByUser_Id($user_id)
    {
        $user = $this->userRepo->getUserByUserId($user_id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
        return response()->json($user);
    }
    public function getUserById($id)
    {
        $user = $this->userRepo->getUserById($id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
        return response()->json($user);
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

    public function getProfile(Request $request)
    {
        return response()->json([
            'myInfo' => $request->user(),
        ]);
    }
    public function getFriendChat($id)
    {
        $user = $this->userRepo->getUserChatById($id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
        return response()->json($user);
    }

    public function update_profile(Request $request, $id)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'user_name' => 'string|max:255',
            'bio' => 'nullable|string',
            'gender' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra định dạng ảnh
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Tìm người dùng theo ID
        $user = User::where('user_id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        // Cập nhật thông tin người dùng
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->user_name = $request->input('user_name');
        $user->bio = $request->input('bio');
        $user->gender = $request->input('gender');
        $user->birthdate = $request->input('birthdate');
        $user->phone_number = $request->input('phone_number');
        $user->address = $request->input('address');
        $user->occupation = $request->input('occupation');

        // Xử lý hình ảnh đại diện
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // Tạo thư mục avatar nếu chưa tồn tại
            $directory = public_path('uploads/avatar');
            File::makeDirectory($directory, $mode = 0777, true, true);

            // Tạo tên tệp duy nhất
            $filename = uniqid() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $path = '/uploads/avatar/' . $filename;

            // Lưu tệp hình ảnh
            $request->file('avatar')->move($directory, $filename); // Sử dụng move thay vì storeAs

            // Cập nhật đường dẫn hình ảnh trong model User
            $user->avatar = $path;
        }

        $user->save();
        $userFaceRegs = $user->userFaceRegs()->get();
        $user->user_face_regs = $userFaceRegs;
        return response()->json(['message' => 'Cập nhật thông tin thành công!', 'user' => $user], 200);
    }

    // update_profile_photo

    public function update_profile_photo(Request $request, $id)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra định dạng ảnh
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Tìm người dùng theo ID
        $user = User::where('user_id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Xử lý hình ảnh đại diện
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // Tạo thư mục avatar nếu chưa tồn tại
            $directory = public_path('uploads/avatar');
            File::makeDirectory($directory, $mode = 0777, true, true);

            // Tạo tên tệp duy nhất
            $filename = uniqid() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $path = '/uploads/avatar/' . $filename;

            // Lưu tệp hình ảnh
            $request->file('avatar')->move($directory, $filename); // Sử dụng move thay vì storeAs

            // Cập nhật đường dẫn hình ảnh trong model User
            $user->avatar = $path;
            $user->save(); // Lưu thay đổi
            $userFaceRegs = $user->userFaceRegs()->get();
            $user->user_face_regs = $userFaceRegs;
            return response()->json(['message' => 'Cập nhật avatar thành công!', 'user' => $user], 200);
        }

        return response()->json(['message' => 'No avatar file provided.'], 400);
    }

    public function update_profile_photo_cover(Request $request, $id)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra định dạng ảnh
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Tìm người dùng theo ID
        $user = User::where('user_id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Xử lý hình ảnh đại diện
        if ($request->hasFile('cover_photo')) {
            // Xóa ảnh cũ nếu có
            if ($user->cover_photo) {
                Storage::delete($user->cover_photo);
            }

            // Tạo thư mục avatar nếu chưa tồn tại
            $directory = public_path('uploads/cover_photo');
            File::makeDirectory($directory, $mode = 0777, true, true);

            // Tạo tên tệp duy nhất
            $filename = uniqid() . '.' . $request->file('cover_photo')->getClientOriginalExtension();
            $path = '/uploads/cover_photo/' . $filename;

            // Lưu tệp hình ảnh
            $request->file('cover_photo')->move($directory, $filename); // Sử dụng move thay vì storeAs

            // Cập nhật đường dẫn hình ảnh trong model User
            $user->cover_photo = $path;
            $user->save(); // Lưu thay đổi
            $userFaceRegs = $user->userFaceRegs()->get();
            $user->user_face_regs = $userFaceRegs;
            return response()->json(['message' => 'Cập nhật avatar thành công!', 'user' => $user], 200);
        }

        return response()->json(['message' => 'No avatar file provided.'], 400);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $currentUserId = auth()->id();

        if (!$query) {
            return response()->json([], 200);
        }

        $users = User::where('user_name', 'LIKE', "%{$query}%")
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin'); // Loại trừ người dùng có vai trò 'admin'
            })
            ->where('id', '!=', $currentUserId) // Loại trừ chính người dùng hiện tại
            ->get();

        return response()->json($users);
    }
}
