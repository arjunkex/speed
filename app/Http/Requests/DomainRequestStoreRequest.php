<?php

namespace App\Http\Requests;

use App\Rules\CustomDomainValidation;
use Illuminate\Foundation\Http\FormRequest;

class DomainRequestStoreRequest extends FormRequest
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
            'requested_domain' => ['required', 'url', new CustomDomainValidation()],
        ];
    }
}
