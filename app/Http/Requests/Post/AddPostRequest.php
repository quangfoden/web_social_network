<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Each;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;

class AddPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public static array $extensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp','txt',
        'mp3', 'wav', 'mp4',
        "doc", "docx", "pdf", "csv", "xls", "xlsx",
        "zip"
    ];
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'required_without:files',
            'privacy' => 'required|in:public,friends,only_me',
            'files' => [
                'max:50',
                function ($attribute, $value, $fail) {
                    $totalSize = collect($value)->sum(fn (UploadedFile $file) => $file->getSize());
                    if ($totalSize > 1 * 1024 * 1024 * 1024) {
                        $fail('Tổng kích thước của tất cả các tệp không được vượt quá 1GB.');
                    }
                }
            ],
            'files.*'=>[
                'file',
                File::types(self::$extensions)
            ]
        ];
    }

    /**
     * Get the custom error messages for the validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'content.required_without' => 'Vui lòng nhập nội dung hoặc chọn tệp tin.',
            'privacy.required' => 'Vui lòng chọn quyền riêng tư cho bài viết.',
            'privacy.in' => 'Quyền riêng tư không hợp lệ.',
            'files.max' => 'Số lượng tệp tin tối đa là 50 tệp.',
            'files.*.mimes' => 'Định dạng tệp tin không hợp lệ.',
        ];
    }
}
