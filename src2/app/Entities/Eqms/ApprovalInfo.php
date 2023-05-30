<?php

namespace App\Entities\Eqms;

use Illuminate\Database\Eloquent\Model;
use App\Entities\Pmis\Employee\Employee;

class ApprovalInfo extends Model
{
    protected $table = 'approval_info';
    protected $primaryKey = 'approval_info_id';

    public function employee(){
        return $this->belongsTo(Employee::class,'recipient_emp_id');
    }

    public function workflow_receipt()
    {
        return $this->belongsTo(Workflow::class, 'workflow_recipient_id', 'workflow_recipient_id');
    }

    public function status(){
        return $this->belongsTo(LApprovalStatus::class,'approval_status_id','approval_status_id');
    }
}
