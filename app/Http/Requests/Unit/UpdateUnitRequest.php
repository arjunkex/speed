<?php

namespace App\Http\Requests\Unit;

use App\Http\Requests\BaseRequest;
use App\Models\Unit;

class UpdateUnitRequest extends BaseRequest
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
        $slug  = $this->route('unit');

        $unit = Unit::where('slug', $slug)->first();

        return [
            'name' => 'required|string|max:50|unique:units,name,'.$unit->id,
            'code' => 'required|string|max:50|unique:units,code,'.$unit->id,
            'note' => 'nullable|string|max:255'
        ];
    }
}
