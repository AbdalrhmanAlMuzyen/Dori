<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class VerifyAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            "email"=>"required|string",
            "verification_token"=>"required|string"
        ];
    }
}
