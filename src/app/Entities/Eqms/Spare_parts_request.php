<?php


namespace App\Entities\Eqms;
use App\Entities\Pmis\Employee\Employee;
use App\Entities\Eqms\L_Equipment_Type;
use App\Entities\Eqms\L_Workshop;
use Illuminate\Database\Eloquent\Model;

class Spare_parts_request extends model
{
    protected $table= 'spare_part_req_mst';
    protected $primaryKey = 's_p_req_mst_id';

    public function empInfo()
    {
        return $this->belongsTo(Employee::class, 'req_by_emp_id','emp_id');
    }
    public function equipType()
    {
        return $this->belongsTo(L_Equipment_Type::class, 'equip_type_id','equip_type_id');
    }
    public function Workshop()
    {
        return $this->belongsTo(L_Workshop::class, 'workshop_id','workshop_id');
    }


}
