<?php

namespace App\Http\Requests\Asset;

use App\Http\Requests\BaseRequest;
use App\Models\Asset;

class UpdateAssetRequest extends BaseRequest
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
        $slug = $this->route('asset');
        $asset = Asset::where('slug', $slug)->first();

        return [

            'name' => 'required|string|max:50|unique:assets,name,'.$asset->id,
            'assetType' => 'required',
            'assetCost' => 'required|numeric',
            'depreciation' => 'required',
            'salvageValue' => 'nullable|numeric|min:0|max:'.$this->assetCost,
            'usefulLife' => $this->depreciation == 1 ? 'required|numeric|min:0' : '',
            'note' => 'nullable|string|max:255',
        ];
    }
}
