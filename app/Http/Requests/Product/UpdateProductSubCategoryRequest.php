<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use App\Models\ProductSubCategory;

class UpdateProductSubCategoryRequest extends BaseRequest
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
        $slug  = $this->route('product_sub_category');

        $subCategory = ProductSubCategory::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:50|unique:product_sub_categories,name,'.$subCategory->id,
            'category' => 'required',
            'note' => 'nullable|string|max:255'
        ];
    }
}
