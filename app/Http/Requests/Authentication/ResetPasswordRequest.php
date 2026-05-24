<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "new_password"=>"required|string|min:8",
            "token"=>"required|string",
            "email"=>"required|string"
        ];
    }
}
