<?php

namespace App\Http\Requests\Department;

use App\Http\Requests\BaseRequest;
use App\Models\Department;

class UpdateDepartmentRequest extends BaseRequest
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
        $slug = $this->route('department');

        $department = Department::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:50|unique:departments,name,'.$department->id,
            'note' => 'nullable|string|max:255',
        ];
    }
}
