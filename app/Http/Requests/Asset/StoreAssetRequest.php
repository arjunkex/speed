<?php

namespace App\Http\Requests\Asset;

use App\Http\Requests\BaseRequest;

class StoreAssetRequest extends BaseRequest
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
            'name' => 'required|string|max:50|unique:assets,name',
            'assetType' => 'required',
            'assetCost' => 'required|numeric',
            'depreciation' => 'required',
            'salvageValue' => 'required|numeric|min:0|max:'.$this->assetCost,
            'usefulLife' => $this->depreciation == 1 ? 'required|numeric|min:.1' : '',
            'note' => 'nullable|string|max:255',
            'date' => $this->depreciation == 1 ? 'required|date|after_or_equal:today' : 'required',
        ];
    }
}
