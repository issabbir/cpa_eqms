<?php

namespace App\Entities\Eqms;


use Illuminate\Database\Eloquent\Model;

class InventoryDemand extends Model
{
    protected $table= 'INVENTORY_DEMAND';
    protected $primaryKey = 'INVENTORY_DEMAND_ID';
    public $timestamps = false;
    public $sequence = 'ID_SEQ';

}
