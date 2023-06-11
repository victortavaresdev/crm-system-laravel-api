<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'deadline' => ['required', 'string', 'date'],
            'assigned_client' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::enum(ProjectStatus::class)],
        ];
    }
}
