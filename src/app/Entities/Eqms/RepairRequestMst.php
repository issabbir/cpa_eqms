<?php

namespace App\Entities\Eqms;

use Illuminate\Database\Eloquent\Model;

class RepairRequestMst extends Model
{
    protected $table= 'repair_request_mst';
    protected $primaryKey = 'r_r_mst_id';
    public $timestamps = false;

    public function equipment() {
        return $this->belongsTo(Equipment::class, 'equip_id');
    }

}
