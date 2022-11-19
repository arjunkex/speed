<?php

namespace App\Http\Requests\GeneralSetting;

use App\Http\Requests\BaseRequest;

class StoreGeneralSettingRequest extends BaseRequest
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
            'companyName' => 'required|string|max:30',
            'companyTagline' => 'required|string|max:255|min:3',
            'emailAddress' => 'required|string|email|max:80',
            'phoneNumber' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'clientPrefix' => 'required|string|min:2|max:10',
            'supplierPrefix' => 'required|string|min:2|max:10',
            'employeePrefix' => 'required|string|min:2|max:10',
            'proCatPrefix' => 'required|string|min:2|max:10',
            'proSubCatPrefix' => 'required|string|min:2|max:10',
            'productPrefix' => 'required|string|min:2|max:10',
            'expCatPrefix' => 'required|string|min:2|max:10',
            'expSubCatPrefix' => 'required|string|min:2|max:10',
            'purchasePrefix' => 'required|string|min:2|max:10',
            'purchaseReturnPrefix' => 'required|string|min:2|max:10',
            'quotationPrefix' => 'required|string|min:2|max:10',
            'invoicePrefix' => 'required|string|min:2|max:10',
            'invoiceReturnPrefix' => 'required|string|min:2|max:10',
            'adjustmentPrefix' => 'required|string|min:2|max:10',
            'currency' => 'required',
            'language' => 'required|string|min:2|max:10',
            'copyrightText' => 'required|string|max:100',
            'defaultClient' => 'required',
            'defaultAccount' => 'required',
            'defaultVatRate' => 'required'
        ];
    }
}
