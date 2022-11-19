<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;


class StorePermissionRequest extends BaseRequest
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
            'name' => 'required|string|max:50|unique:permissions,name',
            'guardName' => 'required|string|max:255',
        ];
    }
}
