<?php


namespace App\Repositories;


use App\Entities\Eqms\EquipmentAdd;
use App\Entities\Eqms\EquipmentList;
use App\Entities\Eqms\VendorList;

class EquipmentAddRepo
{
    protected  $equipmentAdd;
    protected  $equipmentList;
    protected  $vendorList;
    protected $request;

    /**
     *
     * EquipmentAddRepo constructor.
     * @param EquipmentAdd $equipmentAdd
     */
    public function __construct(EquipmentAdd $equipmentAdd, EquipmentList $equipmentList, VendorList $vendorList)
    {
        $this->equipmentAdd = $equipmentAdd;
        $this->equipmentList = $equipmentList;
        $this->vendorList = $vendorList;
    }

    /**
     * Find all equipmentAdds for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->equipmentAdd->orderBy('equipment_add_no', 'desc')
                ->leftjoin('vendor_list', 'equipment_add.vendor_no', '=', 'vendor_list.vendor_no')
                ->select('equipment_add.*', 'vendor_list.vendor_name')
                ->get();
    }

    /**
     * Find all equipmentAdds for all
     * @return mixed
     */
    public function findAllEquipmentList() {
        //Todo: Applied filters option as you need
        return $this->equipmentList->get();
    }
    public function findAllEquipmentAddList() {
        //Todo: Applied filters option as you need
        return $this->equipmentAdd->get();
    }
    /**
     * Find all equipmentAdds for all
     * @return mixed
     */
    public function findAllVendorList() {
        //Todo: Applied filters option as you need
        return $this->vendorList->get();
    }

    /**
     * Find equipmentAdd specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->equipmentAdd->where('equipment_add_no', $id)
                ->leftjoin('vendor_list', 'equipment_add.vendor_no', '=', 'vendor_list.vendor_no')
                ->select('equipment_add.*', 'vendor_list.vendor_name')
                ->first();
    }


    /**
     * Find all vendors for all
     * @return mixed
     */
    public function findSingleDtl() {
        //Todo: Applied filters option as you need
        return $this->equipmentAdd->whereNotNull('equipment_add_id')
                    ->leftjoin('equipment_list', 'equipment_add.equipment_id', '=', 'equipment_list.equipment_id')
                    ->select('equipment_add.quantity', 'equipment_list.equipment_name', 'equipment_list.equipment_name_bn')
                    ->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getEquipmentAdd() {
        return $this->equipmentAdd;
    }
}
