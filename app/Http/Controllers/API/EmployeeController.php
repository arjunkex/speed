<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\PayrollResource;
use App\Http\Resources\SalaryIncrementResource;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Role;
use App\Models\SalaryIncrement;
use App\Models\User;
use App\Services\ImageService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    private $imageService;
    // define middleware
    public function __construct(ImageService $imageService)
    {
        $this->middleware('can:employee-list', ['only' => ['index', 'search']]);
        $this->middleware('can:employee-create', ['only' => ['create']]);
        $this->middleware('can:employee-view', ['only' => ['show']]);
        $this->middleware('can:employee-edit', ['only' => ['update']]);
        $this->middleware('can:employee-delete', ['only' => ['destroy']]);

        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return EmployeeResource::collection(Employee::with('department', 'user')->latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws Exception
     */
    public function store(StoreEmployeeRequest $request)
    {
        $this->checkSubscriptionLimitByModelName('Employee');

        try {
            // generate code
            $code = 1;
            $lastEmployee = Employee::latest()->first();
            if ($lastEmployee) {
                $code = $lastEmployee->emp_id + 1;
            }

            // upload thumbnail and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = $this->imageService->uploadImageAndGetPath($request->image, 'employees');
            }

            // create a user if allowLogin is true
            if ($request->allowLogin == true) {
                // get role
                $role = Role::where('slug', $request->role['slug'])->first();
                // store user
                $user = User::create([
                    'name' => $request->employeeName,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'account_role' => 0,
                ]);
                $user->roles()->attach($role->id);
                $user->permissions()->attach($user->roles[0]->permissions);
            }

            // create employee
            Employee::create([
                'name' => $request->employeeName,
                'emp_id' => $code,
                'department_id' => $request->department['id'],
                'designation' => $request->designation,
                'salary' => $request->salary,
                'commission' => $request->commission,
                'mobile_number' => $request->mobileNumber,
                'birth_date' => $request->birthDate,
                'gender' => $request->gender,
                'blood_group' => $request->bloodGroup,
                'religion' => $request->religion,
                'appointment_date' => $request->appointmentDate,
                'joining_date' => $request->joiningDate,
                'address' => $request->address,
                'status' => $request->status,
                'image_path' => $imageName,
                'user_id' => isset($user) ? $user->id : null,
            ]);

            return $this->responseWithSuccess('Employee added successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $employee = Employee::with('department', 'user')->where('slug', $slug)->first();

            return new EmployeeResource($employee);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, $slug)
    {
        $employee = Employee::where('slug', $slug)->first();

        try {
            // get department
            $department = Department::where('slug', $request->department['slug'])->first();

            // upload thumbnail and set the name
            $imageName = $employee->image_path;
            if ($request->image) {
                if ($imageName) {
                    $this->imageService->checkImageExistsAndDelete($imageName, 'employees');
                }
                $imageName = $this->imageService->uploadImageAndGetPath($request->image, 'employees');
            }

            // operations for allow login
            if ($request->allowLogin == true) {
                // get role
                $role = Role::where('slug', $request->role['slug'])->first();
                if (isset($employee->user_id)) {
                    // update user login
                    $user = $employee->user;
                    $password = $user->password;
                    if (! empty($request->password)) {
                        $password = Hash::make($request->password);
                    }
                    $user->update([
                        'name' => $request->employeeName,
                        'email' => $request->email,
                        'password' => $password,
                        'is_active' => 1,
                    ]);
                    $user->roles()->sync($role->id);
                    $user->permissions()->sync($user->roles[0]->permissions);
                } else {
                    // store user login
                    $user = User::create([
                        'name' => $request->employeeName,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'account_role' => 0,
                    ]);
                    $user->roles()->attach($role->id);
                    $user->permissions()->attach($user->roles[0]->permissions);
                }
            } else {
                if (isset($employee->user)) {
                    $employee->user->update([
                        'is_active' => 0,
                    ]);
                    $employee->user->permissions()->detach($employee->user->roles[0]->permissions);
                }
            }
            // update employee
            $employee->update([
                'name' => $request->employeeName,
                'department_id' => $request->department['id'],
                'designation' => $request->designation,
                'salary' => $request->salary,
                'commission' => $request->commission,
                'mobile_number' => $request->mobileNumber,
                'birth_date' => $request->birthDate,
                'gender' => $request->gender,
                'blood_group' => $request->bloodGroup,
                'religion' => $request->religion,
                'appointment_date' => $request->appointmentDate,
                'joining_date' => $request->joiningDate,
                'address' => $request->address,
                'status' => $request->status,
                'image_path' => $imageName,
                'user_id' => isset($user) ? $user->id : null,
            ]);

            return $this->responseWithSuccess('Employee updated successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        try {
            $employee = Employee::where('slug', $slug)->first();
            //delete employee image
            if ($employee->image_path) {
                $this->imageService->checkImageExistsAndDelete($employee->image_path, 'employees');
            }
            // remove user login
            if (isset($employee->user)) {
                $employee->user->update([
                    'is_active' => 0,
                ]);
                $employee->user->permissions()->detach($employee->user->roles[0]->permissions);
            }
            $employee->delete();

            return $this->responseWithSuccess('Employee deleted successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * search resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $term = $request->term;
        $query = Employee::with('department');

        if ($request->startDate && $request->endDate) {
            $query = $query->whereBetween('joining_date', [$request->startDate, $request->endDate]);
        }

        $query->where(function ($query) use ($term) {
            $query->where('name', 'Like', '%'.$term.'%')
                ->orWhere('emp_id', 'Like', '%'.$term.'%')
                ->orWhere('designation', 'Like', '%'.$term.'%')
                ->orWhere('mobile_number', 'Like', '%'.$term.'%')
                ->orWhere('salary', 'Like', '%'.$term.'%')
                ->orWhereHas('department', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%');
                });
        });

        return EmployeeResource::collection($query->latest()->paginate($request->perPage));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allEmployees()
    {
        $allEmployees = Employee::with('department')->where('status', 1)->latest()->get();

        return EmployeeResource::collection($allEmployees);
    }

    // return employee payroll
    public function employeePayroll(Request $request, $slug)
    {
        $employee = Employee::where('slug', $slug)->first();
        $allPayroll = Payroll::where('employee_id', $employee->id)->latest()->paginate($request->perPage);

        return PayrollResource::collection($allPayroll);
    }

    // search employee payroll
    public function searchEmployeePayroll(Request $request, $slug)
    {
        $term = $request->term;

        $employee = Employee::where('slug', $slug)->first();
        $allPayroll = Payroll::where('employee_id', $employee->id)->where(function ($query) use ($term) {
            $query->orWhere('salary_month', 'LIKE', '%'.$term.'%')
                ->orWhere('deduction_reason', 'LIKE', '%'.$term.'%')
                ->orWhereHas('payrollTransaction', function ($newQuery) use ($term) {
                    $newQuery->where('amount', 'LIKE', '%'.$term.'%')
                        ->orWhereHas('cashbookAccount', function ($newQuery) use ($term) {
                            $newQuery->where('account_number', 'LIKE', '%'.$term.'%');
                        });
                });
        })->latest()->paginate($request->perPage);

        return PayrollResource::collection($allPayroll);
    }

    // return employee salary incremetns
    public function employeeIncrements(Request $request, $slug)
    {
        $employee = Employee::where('slug', $slug)->first();
        $allIncrements = SalaryIncrement::with('employee')->where('empolyee_id', $employee->id)->latest()->paginate($request->perPage);

        return SalaryIncrementResource::collection($allIncrements);
    }

    // search employee salary incremetns
    public function searchEmployeeIncrements(Request $request, $slug)
    {
        $term = $request->term;

        $employee = Employee::where('slug', $slug)->first();
        $allIncrements = SalaryIncrement::with('employee')->where('empolyee_id', $employee->id)->where(function ($query) use ($term) {
            $query->orWhere('increment_amount', 'LIKE', '%'.$term.'%')
                ->orWhereHas('employee', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('name', 'LIKE', '%'.$term.'%')
                        ->orWhere('emp_id', 'LIKE', '%'.$term.'%')
                        ->orWhere('designation', 'LIKE', '%'.$term.'%')
                        ->orWhere('salary', 'LIKE', '%'.$term.'%');
                });
        })->latest()->paginate($request->perPage);

        return SalaryIncrementResource::collection($allIncrements);
    }
}
