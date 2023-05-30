<?php

namespace App\Entities\Eqms;

use Illuminate\Database\Eloquent\Model;

class EquipmentRequest extends Model
{
    protected $table= 'equip_request';
    protected $primaryKey = 'eqr_id';
    public $timestamps = false;
}
