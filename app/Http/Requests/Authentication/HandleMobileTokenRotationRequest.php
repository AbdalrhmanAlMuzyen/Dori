<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class HandleMobileTokenRotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
     
    public function rules(): array
    {
        return [
            "refresh_token"=>"required|string",
            "device_id"=>"required|string"
        ];
    }
}