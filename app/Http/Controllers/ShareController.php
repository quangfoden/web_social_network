<?php

namespace App\Http\Controllers;

use App\Models\Share;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    //
    public function sharePost(Request $request)
    {
        // Xác thực đầu vào
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'privacy' => 'required|in:public,friends,only_me',
        ]);

        // Tạo một bản ghi chia sẻ mới
        $share = Share::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->id(), // Lấy ID người dùng đang xác thực
            'privacy' => $request->privacy,
        ]);

        return response()->json(['message' => 'Chia sẽ bài viết thành công', 'share' => $share]);
    }
}
