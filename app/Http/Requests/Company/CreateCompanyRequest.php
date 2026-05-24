<?php

namespace App\Http\Requests\Company;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",

            "email" => "required|email|unique:companies,email",

            "phone_number" => "required|string|max:20",

            "logo" => "required|image|mimes:jpg,jpeg,png,webp",

            "booking_mode" => "required|in:DAILY_RESET,CONTINUOUS_QUEUE",
        ];
    }
}
