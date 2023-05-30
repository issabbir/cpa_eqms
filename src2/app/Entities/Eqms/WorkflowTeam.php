<?php

namespace App\Entities\Eqms;

use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class WorkflowTeam extends Model
{
    protected $table = 'workflow_team';
    protected $primaryKey = 'team_id';

    protected $with = ['employee'];
    public function department(){
        return $this->belongsTo(Department::class,'department_id');
    }

    public function employee(){
        return $this->belongsto(Employee::class,'emp_id');
    }
}
