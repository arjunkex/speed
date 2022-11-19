<?php

namespace App\Http\Requests\Purchase;

use App\Http\Requests\BaseRequest;
use App\Rules\MinOne;

class UpdatePurchaseReturnRequest extends BaseRequest
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

            'returnReason' => 'required|string|min:2|max:255',
            'selectedProducts' => ['required', 'distinct', new MinOne],
            'account' => $this->returnAmount > 0 ? 'required' : 'nullable',
            'date' => 'nullable|date_format:Y-m-d',
            'note' => 'nullable|string|max:255'
        ];
    }
}
