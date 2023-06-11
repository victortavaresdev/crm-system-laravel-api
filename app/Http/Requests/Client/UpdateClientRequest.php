<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_name' => ['sometimes', 'string', 'max:255'],
            'contact_email' => ['sometimes', 'string', 'email', 'max:255'],
            'contact_phone_number' => ['sometimes', 'string', 'max:255'],
            'company_name' => ['sometimes', 'string', 'max:255'],
            'company_address' => ['sometimes', 'string', 'max:255'],
            'company_city' => ['sometimes', 'string', 'max:255'],
            'company_zip' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
