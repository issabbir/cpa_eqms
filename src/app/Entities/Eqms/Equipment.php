<?php

namespace App\Entities\Eqms;

use App\Entities\Pmis\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table= 'equipment';
    protected $primaryKey = 'equip_id';
    public $timestamps = false;

    public function capacity()
    {
        return $this->belongsTo(L_Load_Capacity::class,'load_capacity_id');
    }
}
