<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\BaseRequest;

class StoreAccountRequest extends BaseRequest
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
            'bankName' => ['required', 'string', 'max:100'],
            'branchName' => ['nullable', 'string', 'max:100'],
            'accountNumber' => ['required', 'string', 'max:100', 'unique:accounts,account_number'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
