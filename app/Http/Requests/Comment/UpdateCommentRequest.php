<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Each;
class UpdateCommentRequest extends FormRequest
{
    public static array $extensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp','txt',
        'mp3', 'wav', 'mp4',
        "doc", "docx", "pdf", "csv", "xls", "xlsx",
        "zip"
    ];
    /**
     * Determine if the user is authorized to make this request.
     */
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
            //
            'content' => 'required_without:file',
            'file' => [
                'nullable',
                'file',
                File::types(self::$extensions)
            ],
        ];
    }

    public function messages()
    {
        return [
            'content.required_without_all' => 'Vui lòng nhập nội dung hoặc chọn tệp tin.',
            'file.file' => 'Tệp tin không hợp lệ.',
            'file.mimes' => 'Định dạng tệp tin không hợp lệ.',
        ];
    }
}
