<?php


namespace App\Repositories;

use App\Entities\Eqms\EquipmentAssigne;
use App\Entities\Eqms\EquipmentList;
use App\Entities\Eqms\L_Department;
use App\Entities\Admin\LDptSection;
use App\Entities\Eqms\VendorList;
use Illuminate\Http\Request;

class EquipmentAssigneRepo
{
    protected  $equipmentAssigne;
    protected  $equipmentList;
    protected  $department;
    protected  $section;
    protected  $request;

    /**
     *
     * EquipmentAssigneRepo constructor.
     * @param EquipmentAssigne $equipmentAssigne
     */
    public function __construct(EquipmentAssigne $equipmentAssigne, EquipmentList $equipmentList, VendorList $vendorList, L_Department $department, LDptSection $section)
    {
        $this->equipmentAssigne = $equipmentAssigne;
        $this->equipmentList = $equipmentList;
        $this->vendorList = $vendorList;
        $this->department = $department;
        $this->section = $section;
    }

    /**
     * Find all EquipmentAssignes for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->equipmentAssigne->get();
    }

    /**
     * Find all department for all
     * @return mixed
     */
    public function findAllDepartment() {
        //Todo: Applied filters option as you need
        return $this->department->get();
    }

    /**
     * Find all section for all
     * @return mixed
     */
    public function findAllSection() {
        //Todo: Applied filters option as you need
        return $this->section->get();
    }

    /**
     * Find all EquipmentAssignes for all
     * @return mixed
     */
    public function findAllEquipmentList() {
        //Todo: Applied filters option as you need
        return $this->equipmentList->get();
    }

    /**
     * Find all EquipmentAssignes for all
     * @return mixed
     */
    public function findAllVendorList() {
        //Todo: Applied filters option as you need
        return $this->vendorList->get();
    }

    /**
     * Find EquipmentAssigne specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->equipmentAssigne->where('equipment_assign_id', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getEquipmentAssigne() {
        return $this->equipmentAssigne;
    }
}
