<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\BaseRequest;
use App\Models\Account;

class UpdateAccountRequest extends BaseRequest
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
        $slug = $this->route('account');
        $account = Account::where('slug', $slug)->first();

        return [
            'bankName' => 'required|string|max:100',
            'branchName' => 'required|string|max:100',
            'accountNumber' => 'required|string|max:100|unique:accounts,account_number,'.$account->id,
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
