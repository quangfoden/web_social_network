<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\Media;
use Illuminate\Http\UploadedFile;

class PostService
{
    public function updatemedia($model, $type, UploadedFile $file)
    {
        if (!in_array($type, ['image', 'video'])) {
            return response()->json(['error' => 'Invalid media type'], 400);
        }
        $directory = public_path('uploads');
        File::makeDirectory($directory, $mode = 0777, true, true);
        $filename = uniqid() . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $path = 'uploads/Upload_posts/' . $filename;
        $file->storeAs('uploads/Upload_posts', $filename);
        $media = new Media([
            'type' => $type,
            'path' => $path,
        ]);
        $model->media()->save($media);
        return response()->json(['message' => 'Media data processed and stored successfully']);
    }
    public function formatTimeAgo($dateTime)
    {
        $carbonDateTime = Carbon::parse($dateTime);
        $diffInMinutes = $carbonDateTime->diffInMinutes();
        $diffInHours = $carbonDateTime->diffInHours();
        $diffInDays = $carbonDateTime->diffInDays();
        if ($diffInMinutes < 1) {
            return 'vừa xong';
        } else if ($diffInMinutes < 60) {
            return $diffInMinutes . ' phút trước';
        } elseif ($diffInHours < 24) {
            return $diffInHours . ' giờ trước';
        } elseif ($diffInDays < 7) {
            return $diffInDays . ' ngày trước';
        } else {
            return $carbonDateTime->format('d-m-Y H:i:s');
        }
    }
}
