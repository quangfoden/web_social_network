<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Tên là bắt buộc.',
            'first_name.string' => 'Tên phải là một chuỗi ký tự.',
            'first_name.max' => 'Tên không được vượt quá 255 ký tự.',

            'last_name.required' => 'Họ là bắt buộc.',
            'last_name.string' => 'Họ phải là một chuỗi ký tự.',
            'last_name.max' => 'Họ không được vượt quá 255 ký tự.',

            'user_name.required' => 'Tên người dùng là bắt buộc.',
            'user_name.string' => 'Tên người dùng phải là một chuỗi ký tự.',
            'user_name.max' => 'Tên người dùng không được vượt quá 255 ký tự.',

            'email.required' => 'Email là bắt buộc.',
            'email.string' => 'Email phải là một chuỗi ký tự.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',

            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.string' => 'Mật khẩu phải là một chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',

            'password_confirmation.required' => 'Mật khẩu xác nhận là bắt buộc.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all(); 
        throw new HttpResponseException(
            response()->json([
                'response_index' => true,
                'response_type' => 'error',
                'response_data' => [$errors[0]], 
                'authenticated' => false,
            ], 422)
        );
    }
}
