<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Admin\LDepartment;
use App\Entities\Pmis\Employee\Employee;
use App\Enums\Pmis\Employee\Statuses;
use App\Http\Controllers\Controller;
use App\Contracts\LookupContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Managers\LookupManager;
use App\Managers\Pmis\Employee\EmployeeManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    /** @var EmployeeManager */
    private $employeeManager;

    /** @var LookupManager */
    private $lookupManager;


    public function __construct(EmployeeContract $employeeManager, LookupContract $lookupManager)
    {
        $this->employeeManager = $employeeManager;
        $this->lookupManager = $lookupManager;
    }

    public function employees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findEmployeeCodesBy($searchTerm);

        return $employees;
    }

    public function employeesWithName(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findEmployeesWithNameBy($searchTerm);

        return $employees;
    }

    public function employeesWithDept(Request $request,$empDept)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findDeptWiseEmployeeCodesBy($searchTerm,$empDept);

        return $employees;
    }

    public function employee(Request $request, $empId)
    {
        return $this->employeeManager->findEmployeeInformation($empId);
    }

    public function deptName(Request $request)
    {
        $searchTerm = $request->get('term');
        $deptName = LDepartment::select('*')
            ->where(
                [
                    ['department_name', 'like', ''.$searchTerm.'%'],
                ]
            )->orderBy('department_name', 'ASC')->limit(10)->get(['department_id', 'department_name']);

        return $deptName;
    }
}
