<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "device_id"=>"required|string",
            "refresh_token"=>"required|string",
            "fcm_token"=>"required|string"
        ];
    }
}
