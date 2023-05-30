<?php

namespace App\Managers\Eqms;

use App\Entities\Eqms\Department;
use App\Entities\Pmis\Employee\Employee;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;

class SetupManager
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function findDepartment($id = null)
    {
        if($id) {
            return Department::where('department_id', $id)->get();
        }
        return Department::orderBy('department_name','asc')->get();
    }

    public function findEmpDetails($id = null)
    {
        if ($id)
            return Employee::find($id);

        return Employee::orderBy("emp_id", 'asc')->limit(15)->get(['emp_id', 'emp_name', 'emp_code', 'dpt_division_id', 'dpt_department_id', 'designation_id']);
    }

    public function findEmpByCurrentDepartment($name = null, $departID = null)
    {
        $con = "";
        if($departID)
        {
            $con .= " and e.current_department_id=".$departID;
        }
        try{
            $query = <<<QUERY
select u.emp_id, emp_code, (emp_code||' '||emp_name) AS option_name, emp_name,
designation_id, current_department_id, section_id, bill_code, dpt_division_id, u.user_id as id, u.emp_id as employee_id
from pmis.employee e, cpa_security.sec_users u
where u.emp_id = e.emp_id $con
and (LOWER(emp_name) like trim('%$name%') or emp_code like trim('%$name%')) order by emp_name asc
QUERY;
//return $query;exit;
            return DB::select(DB::raw($query));
        }catch (\Exception $e){
            return $e->getMessage();
        }

    }
}
