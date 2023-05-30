<?php


namespace App\Repositories;
use App\Entities\Eqms\EquipmentRequisitionList;
use App\Entities\Eqms\SubCategories;
use App\Entities\Eqms\EquipmentList;
use App\Entities\Eqms\VendorType;
use Illuminate\Http\Request;

class RequisitionDetailsRepo
{
    protected $requisitionDetails;
    protected $request;

    /**
     *
     * RequisitionDetailsRepo constructor.
     * @param EquipmentRequisitionList $equipmentRequisitionList
     */
    public function __construct(EquipmentRequisitionList $equipmentRequisitionList)
    {
        $this->requisitionDetails = $equipmentRequisitionList;


    }

    /**
     * Find all vendors for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->requisitionDetails->get();
    }

    /**
     * Find all vendors for all
     * @return mixed
     */
    public function findAllDtl() {
        //Todo: Applied filters option as you need
        return $this->requisitionDetails->whereNotNull('approve_sa_qty')->get();
    }

    /**
     * Find vendor specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->requisitionDetails->where('requisition_dtl_no', $id)->first();
    }
    public function findRequisitionDetails($requisition_mst_no)
    {
        return $this->requisitionDetails->where('requisition_mst_no', $requisition_mst_no)->get();
    }
    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getRequisitionDetails() {
        return $this->requisitionDetails;
    }


}
