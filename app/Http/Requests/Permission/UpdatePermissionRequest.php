<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;
use App\Models\Permission;

class UpdatePermissionRequest extends BaseRequest
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
        $slug  = $this->route('permission');

        $permission = Permission::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|unique:permissions,slug,'.$permission->id,
            'guardName' => 'required|string|max:255'
        ];
    }
}
