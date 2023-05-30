<?php


namespace App\Repositories;



use App\Entities\Eqms\Categories;
use App\Entities\Eqms\SubCategories;
use App\Entities\Eqms\EquipmentList;
use App\Entities\Eqms\VendorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentListRepo
{
    protected $equipment;
    protected $vendorType;
    protected $categories;
    protected $subCategories;
    protected $request;

    /**
     *
     * EquipmentListRepo constructor.
     * @param EquipmentList $equipmentList
     */
    public function __construct(EquipmentList $equipmentList, VendorType $vendorType,Categories $categories, SubCategories $subCategories)
    {
        $this->equipment = $equipmentList;
        $this->vendorType = $vendorType;
        $this->categories = $categories;
        $this->subCategories =$subCategories;

    }

    /**
     * Find all vendors for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->equipment->orderBy('equipment_no', 'desc')->get();
    }



    /**
     * Find all vendors for all
     * @return mixed
     */
    public function findAllData() {
        //Todo: Applied filters option as you need
        return $this->equipment->orderBy('equipment_no', 'desc')
            ->leftjoin('equipment_assign', 'equipment_list.equipment_assign_id', '=', 'equipment_assign.equipment_assign_id')
            ->leftjoin('vendor_list', 'equipment_list.vendor_no', '=', 'vendor_list.vendor_no')
            ->leftjoin('l_equipment_status', 'equipment_list.equipment_status_id', '=', 'l_equipment_status.equipment_status_id')
            ->select('equipment_list.*', 'equipment_assign.department_id', 'equipment_assign.emp_id', 'vendor_list.vendor_name', 'l_equipment_status.status_name');
    }

    /**
     * Find vendor specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->equipment->where('equipment_no', $id)->first();

//         return DB::selectOne("select el.*, ec.catagory_name, esc.sub_catagory_name, vl.vendor_name from equipment_list el, l_equipment_catagory ec, l_equipment_sub_catagory esc, vendor_list vl
// where el.equipment_no = '$id'
// and ec.catagory_no = el.catagory_no
// and el.sub_catagory_no = esc.sub_catagory_no
// and el.vendor_no = vl.vendor_no");
    }

    /**
     * Get Join Data objects for Detail view
     *
     * @return mixed
     */
    public function getData($id) {
        return  $this->equipment->where('equipment_no', $id)
            ->leftjoin('l_equipment_catagory', 'equipment_list.catagory_no', '=', 'l_equipment_catagory.catagory_no')
            ->leftjoin('l_equipment_sub_catagory', 'equipment_list.sub_catagory_no', '=', 'l_equipment_sub_catagory.sub_catagory_no')
            ->leftjoin('vendor_list', 'equipment_list.vendor_no', '=', 'vendor_list.vendor_no')
            ->select('equipment_list.*', 'l_equipment_catagory.catagory_name', 'l_equipment_sub_catagory.sub_catagory_name', 'vendor_list.vendor_name')
            ->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getEquipment() {
        return $this->equipment;
    }

    /**
     * Get vendor types
     *
     * @return mixed
     */
    public function getVendorTypes() {
        return $this->vendorType->get();
    }
    /**
     * Get Categories types
     *
     * @return mixed
     */
    public function getCategoriesTypes() {
        return $this->categories->get();
    }
    /**
     * Get sub Categories types
     *
     * @return mixed
     */
    public function getSubCategoriesTypes() {
        return $this->subCategories->get();
    }
}
