<?php

namespace App\Http\Requests;

use App\Rules\CustomDomainValidation;
use App\Rules\DomainValidation;
use Illuminate\Foundation\Http\FormRequest;

class DomainRequestUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'tenant_id' => ['required', 'string'],
            'requested_domain' => ['required', 'string', new CustomDomainValidation()],
            'status' => ['required', 'integer'],
        ];
    }
}
