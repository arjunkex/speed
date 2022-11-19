<?php

namespace App\Http\Requests\Brand;

use App\Http\Requests\BaseRequest;
use App\Models\Brand;

class UpdateBrandRequest extends BaseRequest
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
        $slug = $this->route('brand');

        $brand = Brand::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:255|unique:brands,name,'.$brand->id,
            'shortCode' => 'required|string|max:50|unique:brands,name,'.$brand->id,
            'note' => 'nullable|string|max:255'
        ];
    }
}
