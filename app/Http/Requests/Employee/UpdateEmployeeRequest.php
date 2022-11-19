<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\BaseRequest;
use App\Models\Employee;

class UpdateEmployeeRequest extends BaseRequest
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
        $slug = $this->route('employee');

        $employee = Employee::where('slug', $slug)->first();
        $userId = null;
        if (isset($employee->user)) {
            $user = $employee->user;
            $userId = $user->id;
        }

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
            'email' => $this->allowLogin == true ? 'required|string|email:rfc,dns|max:255|unique:users,email,'.$userId : 'nullable',
            'password' => ($this->allowLogin == true) && (isset($this->password) || empty($employee->user_id)) ? 'required|string|max:255|min:8' : 'nullable',
            'role' => $this->allowLogin == true ? 'required' : 'nullable',

        ];
    }
}
