<?php

namespace App\Http\Requests\TransferBalance;

use App\Http\Requests\BaseRequest;

class StoreTransferBalanceRequest extends BaseRequest
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
            'transferReason' => 'required|string|max:255',
            'fromAccount' => 'required',
            'toAccount' => 'required|different:fromAccount',
            'amount' => 'required|numeric|min:1|max:'.$this->availableBalance,
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
        ];
    }
}
