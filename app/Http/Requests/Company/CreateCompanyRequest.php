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
            "name"=>"required|string",
            "email"=>"required|string|email|ends_with:gmail.com|unique:companies,email",
            "phone_number"=>"required|string",
            "logo_path"=>"nullable|image|memis:png,jpg"
        ];
    }
}
