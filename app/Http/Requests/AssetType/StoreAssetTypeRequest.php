<?php

namespace App\Http\Requests\AssetType;

use App\Http\Requests\BaseRequest;

class StoreAssetTypeRequest extends BaseRequest
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
            'name' => 'required|string|max:50|unique:asset_types',
            'note' => 'nullable|string|max:255',
        ];
    }
}
