<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use App\Models\Media;
use Illuminate\Http\UploadedFile;

class MediaService
{
    public function updatemedia($model, $type, UploadedFile $file)
    {
        if (!in_array($type, ['image', 'video'])) {
            return response()->json(['error' => 'Invalid media type'], 400);
        }
        $directory = public_path('uploads');
        File::makeDirectory($directory, $mode = 0777, true, true);
        $filename = uniqid() . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $path = 'uploads/' . $filename;
        $file->storeAs('uploads', $filename);
        $media = new Media([
            'type' => $type,
            'path' => $path,
        ]);
        $model->media()->save($media);
        return response()->json(['message' => 'Media data processed and stored successfully']);
    }
}
