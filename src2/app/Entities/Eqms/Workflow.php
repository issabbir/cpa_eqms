<?php

/**
 * Created by PhpStorm.
 * User: Mohammad Hossian
 * Date: 14/01/21
 * Time: 10:00 AM
 */

namespace App\Entities\Eqms;

use App\Entities\Admin\LDepartment;
use App\Entities\Pmis\Employee\Employee;
use App\Entities\Security\User;
use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $table = 'workflow_recipient';
    protected $primaryKey = 'workflow_recipient_id';

    //protected $with = ['employee','department'];
    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id','employee_id');
    }
    public function department(){
        return $this->belongsTo(LDepartment::class,'department_id');
    }

    public function emp_user_id()
    {
        return $this->belongsTo(User::class, 'emp_code', 'user_name');
    }
}
