<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\BaseRequest;
use App\Models\Employee;

class StoreSalarayIncrementRequest extends BaseRequest
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
        $date = date('Y-m-d');

        if (! empty($this->employee)) {
            $employee = Employee::where('slug', $this->employee['slug'])->first();
            $date = $employee->joining_date;
        }

        return [

            'reason' => 'required|string|max:255',
            'employee' => 'required',
            'incrementAmount' => 'required|numeric',
            'incrementDate' => 'nullable|date|date_format:Y-m-d|after_or_equal:'.$date,
            'note' => 'nullable|string|max:255',

        ];
    }
}
