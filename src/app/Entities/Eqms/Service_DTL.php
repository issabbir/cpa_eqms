<?php

namespace App\Entities\Eqms;

use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class Service_DTL extends Model
{
    protected $table= 'service_dtl';
    protected $primaryKey = 's_d_id';

    public function empInfo()
    {
        return $this->belongsTo(Employee::class, 'operator_emp_id','emp_id');
    }

}
