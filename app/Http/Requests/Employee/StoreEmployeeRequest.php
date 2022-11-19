<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\BaseRequest;

class StoreEmployeeRequest extends BaseRequest
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

            'employeeName' => 'required|string|max:255',
            'department' => 'required',
            'designation' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'commission' => 'nullable|numeric',
            'mobileNumber' => 'required|string|max:20',
            'birthDate' => 'required|date|date_format:Y-m-d|before:today',
            'appointmentDate' => 'required|date|date_format:Y-m-d',
            'joiningDate' => 'required|date|date_format:Y-m-d',
            'gender' => 'required|string',
            'bloodGroup' => 'nullable|string',
            'religion' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'email' => $this->allowLogin == true ? 'required|string|email:rfc,dns|max:255|unique:users,email' : 'nullable',
            'password' => $this->allowLogin == true ? 'required|string|max:255|min:8' : 'nullable',
            'role' => $this->allowLogin == true ? 'required' : 'nullable',
        ];
    }
}
