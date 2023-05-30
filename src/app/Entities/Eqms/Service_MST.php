<?php

namespace App\Entities\Eqms;

use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class Service_MST extends Model
{
    protected $table= 'service_mst';
    protected $primaryKey = 's_m_id';

    public function empInfo()
    {
        return $this->belongsTo(Employee::class, 'operator_emp_id','emp_id');
    }

}
