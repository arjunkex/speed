<?php

namespace App\Http\Requests\Expense;

use App\Http\Requests\BaseRequest;
use App\Models\ExpenseCategory;

class UpdateExpenseCategoryRequest extends BaseRequest
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

        $slug = $this->route('expense_category');
        $category = ExpenseCategory::where('slug', $slug)->first();

        return [

            'name' => 'required|string|max:50|unique:expense_categories,name,'.$category->id,
            'note' => 'nullable|string|max:255'
        ];
    }
}
