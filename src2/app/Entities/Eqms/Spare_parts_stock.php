<?php


namespace App\Entities\Eqms;
use  Illuminate\Database\Eloquent\Model;
use App\Entities\Eqms\L_Parts;;


class Spare_parts_stock extends model
{
    protected $table= 'spare_parts_stock';
    protected $primaryKey = 's_p_stock_id';


    public function parts()
    {
        return $this->belongsTo(L_Parts::class, 'part_id','part_id');
    }
}
