<?php

namespace App\Http\Requests\Tenant;

use App\Rules\DomainValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRule;

class StoreTenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'company' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'alpha_dash', new DomainValidation()],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ];
    }
}
