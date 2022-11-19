<?php

namespace App\Http\Requests\AssetType;

use App\Http\Requests\BaseRequest;
use App\Models\AssetType;

class UpdateAssetTypeRequest extends BaseRequest
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
        $slug = $this->route('asset_type');
        $assetType = AssetType::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:50|unique:asset_types,name,'.$assetType->id,
            'note' => 'nullable|string|max:255',
        ];
    }
}
