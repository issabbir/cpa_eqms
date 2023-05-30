<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\EquipmentRequest;
use App\Entities\Eqms\EquipmentRequestDtl;
use App\Entities\Eqms\EquipmentRequestMst;
use App\Entities\Eqms\L_Equipment_Type;
use App\Entities\Eqms\L_EquipmentRequester;
use App\Entities\Eqms\L_Load_Capacity;
use App\Entities\Eqms\L_LocationType;
use App\Entities\Eqms\L_RequestStatus;
use App\Entities\Eqms\L_WorkType;
use App\Entities\Eqms\Location;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class EquipmentAssignController extends Controller
{
    public function index()
    {
        return view('eqms.equipassign.index', [
            'reqList' => L_EquipmentRequester::all(),
            'boList' => BerthOperator::all(),
            'wtList' => L_WorkType::all(),
            'reqstList' => L_RequestStatus::all(),
            'locationList' => Location::all(),
            'loctypList' => L_LocationType::all(),
            'eqptypList' => L_Equipment_Type::all(),
            'ldcpctList' => L_Load_Capacity::all(),
        ]);
    }

    public function dataTableList()
    {
        $querys = "SELECT DISTINCT er.*
    FROM EQMS.EQUIP_REQUEST er
    where er.REQ_STATUS_ID = 2
ORDER BY er.REQ_STATUS_UPDATE_DATE DESC" ;
        $queryResult = DB::select($querys);

        return datatables()->of($queryResult)
            ->addColumn('req_date', function ($query) {
                if ($query->req_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->req_date)->format('d-m-Y');
                }
            })
            ->addColumn('req_status_update_date', function ($query) {
                if ($query->req_status_update_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->req_status_update_date)->format('d-m-Y');
                }
            })
            ->addColumn('req_for_date', function ($query) {
                if ($query->req_for_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->req_for_date)->format('d-m-Y');
                }
            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('equip-assign-edit', [$query->eqr_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);//onclick="$('#vendor_register').toggle('slow')"
    }

    public function edit(Request $request, $id)
    {
        $mData = EquipmentRequest::select('*')
            ->where('eqr_id', '=', $id)
            ->first();
        /*$mmData = EquipmentRequestMst::select('*')
            ->where('eqr_id', '=', $id)
            ->get();*/

//        $querys = "SELECT m.*,d.erd_id, CASE WHEN rd.ERD_ID IS NOT NULL THEN 'Y' ELSE 'N' END FINDINGS
//  FROM EQMS.EQUIP_REQUEST_MST m, EQMS.EQUIP_REQUEST_DTL d, EQMS.ROSTER_DTL rd
// WHERE m.ERM_ID(+) = d.ERM_ID AND d.ERD_ID = rd.ERD_ID(+) and m.eqr_id = :eqr_id" ;
        $querys = "SELECT DISTINCT m.*
  FROM EQMS.EQUIP_REQUEST_MST m, EQMS.EQUIP_REQUEST_DTL d, EQMS.ROSTER_DTL rd
 WHERE     m.EQR_ID(+) = d.EQR_ID
       AND d.ERD_ID = rd.ERD_ID(+)
       AND m.eqr_id = :eqr_id";
        $mmData = db::select($querys,['eqr_id' => $id]);

        $mdData = EquipmentRequestDtl::select('*')
            ->where('eqr_id', '=', $id)
            ->get();

        return view('eqms.equipassign.index', [
            'mData' => $mData,
            'mmData' => $mmData,
            'mdData' => $mdData,
            'reqList' => L_EquipmentRequester::all(),
            'boList' => BerthOperator::all(),
            'wtList' => L_WorkType::all(),
            'reqstList' => L_RequestStatus::all(),
            'locationList' => Location::all(),
            'loctypList' => L_LocationType::all(),
            'eqptypList' => L_Equipment_Type::all(),
            'ldcpctList' => L_Load_Capacity::all(),
        ]);
    }

    public function dtlPost(Request $request)
    {
        $response = $this->dtl_ins($request);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);
        return redirect('/equip-assign/' . $request->get('dtl_eqr_id'));
    }

    private function dtl_ins(Request $request)
    {//dd($request);
        try {
            DB::beginTransaction();
            if ($request->get('tab_erd_id')) {
                foreach ($request->get('tab_erd_id') as $indx => $value) {
                    //if($request->get('dtl_equip_type_id')[$indx] != '' && $request->get('dtl_r_d_id')[$indx] != ''){
                        $status_code = sprintf("%4000s", "");
                        $status_message = sprintf("%4000s", "");
                        $params_dtl = [
                            "p_ERD_ID" => $request->get('tab_erd_id')[$indx],
                            "p_EQUIP_ID" => $request->get('dtl_equip_type_id')[$indx],
                            "p_R_D_ID" => $request->get('dtl_r_d_id')[$indx],
                            "p_ASSIGN_BY" => auth()->id(),
                            "o_status_code" => &$status_code,
                            "o_status_message" => &$status_message
                        ];

                        DB::executeProcedure("EQMS.EQUIPMENT_ASSIGN", $params_dtl);//dd($params_dtl);
                        if ($params_dtl['o_status_code'] != 1) {
                            DB::rollBack();
                            return $params_dtl;
                        }
                    /*}else{
                        continue;
                    }*/
                }

            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        //dd($a);

        return $params_dtl;
    }

    public function getEquipDrop($id, $loc_id)
    {
        $msg = '';
        $sql = "SELECT EQUIP_ID,
       EQUIP_NAME || ' - ' || EQUIP_TYPE equipment_name
  FROM EQMS.EQUIPMENT
 WHERE EQUIP_TYPE_ID IN (SELECT EQUIP_TYPE_ID
                           FROM EQUIP_REQUEST_DTL
                          WHERE EQUIP_TYPE_ID = :EQUIP_TYPE_ID)";
        $datas = db::select($sql,['EQUIP_TYPE_ID' => $id]);
        foreach ($datas as $data){
            $msg .= '<option value="'.$data->equip_id.'">'.$data->equipment_name.'</option>';
        }

        $msg1 = '';
        $sql1 = "SELECT dd.OPERATOR_EMP_CODE || ' - ' || dd.operator_name op_name, OPERATOR_ID, R_D_ID
  FROM EQMS.ROSTER_DTL dd
 WHERE dd.LOCATION_ID IN (SELECT LOCATION_ID
                            FROM EQUIP_REQUEST_DTL
                           WHERE LOCATION_ID = :LOCATION_ID)";
        $datas1 = db::select($sql1,['LOCATION_ID' => $loc_id]);
        foreach ($datas1 as $data){
            $msg1 .= '<option value="'.$data->r_d_id.'">'.$data->op_name.'</option>';
        }

        return  response(
            [
                'options' => $msg,
                'option' => $msg1
            ]
        );
    }
}
