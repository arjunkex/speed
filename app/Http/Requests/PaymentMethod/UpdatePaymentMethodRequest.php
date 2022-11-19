<?php

namespace App\Http\Requests\PaymentMethod;

use App\Http\Requests\BaseRequest;
use App\Models\PaymentMethod;

class UpdatePaymentMethodRequest extends BaseRequest
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
        $slug = $this->route('payment_method');
        $method = PaymentMethod::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:50|unique:payment_methods,name,'.$method->id,
            'shortCode' => 'required|string|max:50|unique:payment_methods,code,'.$method->id,
            'note' => 'nullable|string|max:255'
        ];
    }
}
