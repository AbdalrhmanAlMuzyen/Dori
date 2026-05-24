<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email"=>"required|string",
            "password"=>"required|string",
            "device_id"=>"required|string|unique:user_devices,device_id",
            "device"=>"required|string",
            "platform"=>"nullable|string",
        ];
    }

    public function withValidator($validator)
    {   
        $validator->sometimes("browser","required|string",function(){   
            return $this->platform === 'web';
        });
    }   
}
