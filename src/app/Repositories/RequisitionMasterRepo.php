<?php


namespace App\Repositories;
use App\Entities\Eqms\RequisitionMasterList;
use App\Entities\Eqms\SeRequisitions;
use App\Entities\Eqms\SubCategories;
use App\Entities\Eqms\EquipmentList;
use App\Entities\Eqms\VendorType;
use Illuminate\Http\Request;

class RequisitionMasterRepo
{
    protected $requisitionMaster;
    protected $request;

    /**
     *
     * RequisitionMasterRepo constructor.
     * @param RequisitionMasterList $requisitionMasterList
     */
    public function __construct( RequisitionMasterList $requisitionMasterList)
    {
        $this->requisitionMaster = $requisitionMasterList;


    }

    /**
     * Find all vendors for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->requisitionMaster->orderBy('requisition_mst_no', 'desc')->get();
    }

    /**
     * Find vendor specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->requisitionMaster->where('requisition_mst_no', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getRequisitionMaster() {
        if(auth()->user()->hasRole('CCMS_SYSTEM_ANALYST')){
            return $this->requisitionMaster->where('requisition_status_id','=','2')->orderBy('requisition_mst_no', 'desc');
        }elseif (auth()->user()->hasRole('CCMS_MEMBER_FINANCE')){
            return $this->requisitionMaster->where('requisition_status_id','=','3')->orderBy('requisition_mst_no', 'desc');
        }else{
            return $this->requisitionMaster->orderBy('requisition_mst_no', 'desc');
        }

    }

    public function getRequisitionMasterDashboard() {
        if(auth()->user()->hasRole('CCMS_SYSTEM_ANALYST')){
            return SeRequisitions::where('requisition_status_id','=','2');
        }elseif (auth()->user()->hasRole('CCMS_MEMBER_FINANCE')){
            return SeRequisitions::where('requisition_status_id','=','3');
        }elseif (auth()->user()->hasRole('CCMS_SERVICE_ENGINEER')){
            return SeRequisitions::where('V_SE_REQUISITIONS.requistion_by',auth()->user()->user_id)
                ->leftjoin('cpa_security.sec_users', 'cpa_security.sec_users.user_id', '=', 'V_SE_REQUISITIONS.requistion_by');
            /*return SeRequisitions::where('V_SE_REQUISITIONS.requistion_by',auth()->user()->user_id)
                //->where('V_SE_REQUISITIONS.requisition_status_id','1')
                ->leftjoin('cpa_security.sec_users', 'cpa_security.sec_users.user_id', '=', 'V_SE_REQUISITIONS.requistion_by');*/
        }
        else{
            return SeRequisitions::all();
        }

    }


}
