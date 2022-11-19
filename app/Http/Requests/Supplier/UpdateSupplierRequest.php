<?php

namespace App\Http\Requests\Supplier;

use App\Http\Requests\BaseRequest;
use App\Models\Supplier;

class UpdateSupplierRequest extends BaseRequest
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
        $slug  = $this->route('supplier');

        $supplier = Supplier::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:20|min:3',
            'email' => 'nullable|email|max:255|min:3|unique:suppliers,email,'.$supplier->id,
            'companyName' => 'nullable|string|max:100|min:2',
            'address' => 'nullable|string|max:255'
        ];
    }
}
