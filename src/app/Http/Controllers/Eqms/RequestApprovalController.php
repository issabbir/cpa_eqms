<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\ApprovalInfo;
use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\EquipmentRequest;
use App\Entities\Eqms\EquipmentRequestDtl;
use App\Entities\Eqms\L_Equipment_Type;
use App\Entities\Eqms\L_EquipmentRequester;
use App\Entities\Eqms\L_Load_Capacity;
use App\Entities\Eqms\L_LocationType;
use App\Entities\Eqms\L_RequestStatus;
use App\Entities\Eqms\L_WorkType;
use App\Entities\Eqms\Location;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class RequestApprovalController extends Controller
{
    public function index()
    {
        $empDepartment = HelperClass::get_emp_department_id();
        return view('eqms.requestapproval.index', [
            'department_id' => $empDepartment,
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
        /*$querys = "SELECT DISTINCT er.*
  FROM eqms.equip_request er, equip_request_mst rm
 WHERE     er.eqr_id = rm.eqr_id
       AND EXISTS
               (SELECT *
                  FROM equip_request_dtl d
                 WHERE d.EQR_ID = er.EQR_ID AND d.REQUESTED_EQUIP IS NOT NULL)
                 ORDER BY er.INSERT_DATE desc" ;

        $queryResult = DB::select($querys);*/


        $querys = "  SELECT DISTINCT er.*
    FROM eqms.equip_request er, equip_request_mst rm, APPROVAL_INFO ai
   WHERE     er.eqr_id = rm.eqr_id
         AND ai.RECIPIENT_EMP_ID = :RECIPIENT_EMP_ID
         AND EXISTS
                 (SELECT *
                    FROM APPROVAL_INFO ai
                   WHERE     ai.RECIPIENT_EMP_ID = :RECIPIENT_EMP_ID)
         AND EXISTS
                 (SELECT *
                    FROM equip_request_dtl d
                   WHERE d.EQR_ID = er.EQR_ID AND d.REQUESTED_EQUIP IS NOT NULL)
ORDER BY er.INSERT_DATE DESC";

        $queryResult = DB::select($querys, ['recipient_emp_id' => Auth()->user()->employee->emp_id]);


        return datatables()->of($queryResult)
            ->addColumn('req_date', function ($query) {
                if ($query->req_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->req_date)->format('d-m-Y');
                }
            })
            ->addColumn('req_for_date', function ($query) {
                if ($query->req_for_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->req_for_date)->format('d-m-Y');
                }
            })
            ->addColumn('req_status_update_date', function ($query) {
                if ($query->req_status_update_date == null) {
                    return '--';

                } else {
                    return Carbon::parse($query->req_status_update_date)->format('d-m-Y');
                }
            })
            ->addColumn('approved_yn', function ($query) {
                if ($query->req_status_id == "2") {
                    $html = <<<HTML
<span class="badge badge-success"> Approved</span>
HTML;
                    return $html;
                } else {
                    $html = <<<HTML
<span class="badge badge-danger"> Approval Pending</span>
HTML;
                    return $html;
                }

            })
            ->addColumn('action', function ($query) {
                /*if ($query->req_status_id == "2") {
                    return '';
                } else {*/
                    $actionBtn = '<a title="Edit" href="' . route('equip-request-approval-edit', [$query->eqr_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                    return $actionBtn;
                //}

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
        $querys = "SELECT DISTINCT
       m.*,
       CASE
           WHEN d.SUPPLIED_EQUIP IS NOT NULL AND d.SUPPLIED_DATE IS NOT NULL
           THEN
               'Y'
           ELSE
               'N'
       END
           FINDINGS
  FROM EQUIP_REQUEST_MST m, EQMS.EQUIP_REQUEST_DTL d
 WHERE m.ERM_ID = d.ERM_ID(+) AND m.eqr_id = NVL (:eqr_id, m.eqr_id)";
        $mmData = db::select($querys, ['eqr_id' => $id]);

        $mdData = EquipmentRequestDtl::select('*')
            ->where('eqr_id', '=', $id)
            ->get();
        $approvalData = ApprovalInfo::with(['employee', 'status'])
            ->where('reference_id', $id)
            ->where('current_yn', 'Y')
            ->orderBy('approval_seq_number')
            ->first();
        $curr_data = DB::table('approval_info')
            ->where('reference_id', $id)
            ->where('recipient_emp_id', Auth()->user()->employee->emp_id)
            ->first();
        $next_data = DB::table('approval_info')
            ->where('reference_id', $id)
            ->where('approval_ref_seq', ($curr_data->approval_ref_seq+1))
            ->first();

        return view('eqms.requestapproval.index', [
            'mData' => $mData,
            'mmData' => $mmData,
            'mdData' => $mdData,
            'next_data' => $next_data,
            'curr_data' => $curr_data,
            'workflows' => HelperClass::workflow(1, $id),
            'approvalData' => $approvalData,
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

    public function update(Request $request, $id)
    {
        $response = $this->ins_upd($request, $id);

        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('equip-request-approval-index');
    }

    private function ins_upd(Request $request)
    {//dd($request);
        $postData = $request->post();
        if (isset($postData['eqr_id'])) {
            $eqr_id = $postData['eqr_id'];
        } else {
            $eqr_id = '';
        }
        if ($request->get('update_val') == 1) {
            $status_id = 1;
        } else {
            $status_id = 2;
        }
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_REQUESTER_ID' => $eqr_id,
                'p_REQ_STATUS_ID' => $status_id,
                'p_NOTHI_NO' => $postData['nothi_no'],
                'p_NOTHI_NO_BN' => $postData['nothi_no_bn'],
                'p_APPROVE_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];//dd($params);
            DB::executeProcedure('EQMS.EQUIP_REQUEST_APPROVAL', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
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
        return redirect('/equip-request-approval/' . $request->get('dtl_eqr_id'));
    }

    private function dtl_ins(Request $request)
    {
        try {
            if ($request->get('tab_erd_id')) {
                foreach ($request->get('tab_erd_id') as $indx => $value) {
                    $supplied_date = isset($request->get('tab_supplied_date')[$indx]) ? date('Y-m-d', strtotime($request->get('tab_supplied_date')[$indx])) : '';
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        "p_ERD_ID" => $request->get('tab_erd_id')[$indx],
                        "p_SUPPLIED_EQUIP" => $request->get('tab_supplied_equip')[$indx],
                        "p_SUPPLIED_DATE" => $supplied_date,
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];//dd($params_dtl);

                    DB::executeProcedure("EQMS.EQUIP_REQUEST_DETAIL_UPDATE", $params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                    //DB::commit();
                }
            }


        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params_dtl;
    }

    public function getDtlData($eqr_id, $erm_id)
    {
        $process = [];
        $b = DB::table("equip_request_mst")
            ->select("erm_id", "eqr_id", "location_type_id", "location_type", "location_id", "location", "location_bn", "container_20", "container_40")
            ->orderBy("insert_date", "DESC")
            ->where("eqr_id", "=", $eqr_id)
            ->where("erm_id", "=", $erm_id)
            ->first();

        /*$sql = "SELECT rd.erd_id,
       rd.erm_id,
       rd.eqr_id,
       rd.equip_type,
       eqp.EQUIP_NAME,
       rd.load_capacity,
       rd.requested_equip,
       rd.equip_type_id,
       rd.load_capacity_id,
       rd.supplied_equip,
       rd.supplied_date,
       dd.operator_id     roster_operator,
       dd.OPERATOR_NAME,
       dd.equip_id        roster_eqp_id,
       mst.location_id
  FROM equip_request_dtl rd, eqms.roster_dtl dd, eqms.equip_request_mst mst, EQMS.EQUIPMENT eqp
 WHERE     rd.erd_id = dd.erd_id(+)
       AND mst.erm_id = rd.erm_id
       and dd.EQUIP_ID = eqp.EQUIP_ID(+)
       AND rd.eqr_id = NVL ( :eqr_id, rd.eqr_id)
       AND rd.erm_id = NVL ( :erm_id, rd.erm_id)";*/
        $sql = "SELECT rd.erd_id,
       rd.erm_id,
       rd.eqr_id,
       rd.equip_type,
       eqp.EQUIP_NAME,
       rd.load_capacity,
       rd.requested_equip,
       rd.equip_type_id,
       rd.load_capacity_id,
       rd.supplied_equip,
       rd.supplied_date,
       dd.operator_id     roster_operator,
       dd.OPERATOR_NAME,
       dd.equip_id        roster_eqp_id,
       mst.location_id,
       er.REQ_FOR_DATE
  FROM equip_request_dtl rd, eqms.roster_dtl dd, eqms.equip_request_mst mst, EQMS.EQUIPMENT eqp, EQMS.EQUIP_REQUEST er
 WHERE     rd.erd_id = dd.erd_id(+)
       AND mst.erm_id = rd.erm_id
       AND er.EQR_ID = rd.EQR_ID
       and dd.EQUIP_ID = eqp.EQUIP_ID(+)
       AND rd.eqr_id = NVL ( :eqr_id, rd.eqr_id)
       AND rd.erm_id = NVL ( :erm_id, rd.erm_id)";
        $a = db::select($sql, ['eqr_id' => $eqr_id, 'erm_id' => $erm_id]);

        $msg1 = '<option value="">Please select One</option>';
        /*$sql1 = " SELECT    dd.operator_name
         || ' - '
         || TO_CHAR (rm.R_DATE, 'DD-MM-YYYY')
         || ' - '
         || dd.R_NAME    op_name,
         operator_id,
         r_d_id
    FROM EQMS.ROSTER_MST rm, EQMS.ROSTER_DTL dd
   WHERE     rm.R_M_ID = dd.R_M_ID
         AND dd.LOCATION_ID IN (SELECT LOCATION_ID FROM EQUIP_REQUEST_DTL)
ORDER BY rm.R_DATE DESC";*/
        $sql1 = "select rd.*,rd.OPERATOR_NAME || ' - ' || rd.R_NAME op_name from EQMS.ROSTER_DTL rd
where EQUIP_ID is null";
        $datas1 = db::select($sql1);
        foreach ($datas1 as $data) {
            $msg1 .= '<option value="' . $data->r_d_id . '">' . $data->op_name . '</option>';
        }
        //dd($msg1);
        foreach ($a as $indx => $value) {
            $a[$indx]->emp_dropdown = $msg1;
        }

        foreach ($a as $indx => $value) {
            /*$sql = "SELECT EQUIP_ID,
       EQUIP_NAME || ' - ' || EQUIP_TYPE equipment_name
  FROM EQMS.EQUIPMENT
 WHERE EQUIP_TYPE_ID IN (SELECT EQUIP_TYPE_ID
                           FROM EQUIP_REQUEST_DTL
                          WHERE EQUIP_TYPE_ID = :EQUIP_TYPE_ID)";*/
            $sql = "SELECT e.*, e.EQUIP_NAME || ' - ' || e.EQUIP_TYPE equipment_name
  FROM EQMS.EQUIPMENT e, EQMS.ROSTER_DTL d, EQMS.SERVICE_MST sm
 WHERE     e.EQUIP_ID = d.EQUIP_ID(+)
       AND e.EQUIP_ID = sm.EQUIP_ID(+)
       AND d.EQUIP_ID IS NULL
       AND e.EQUIP_TYPE_ID = :EQUIP_TYPE_ID";
            $datas = db::select($sql, ['EQUIP_TYPE_ID' => $a[$indx]->equip_type_id]);

            if (count($datas) == 0) {
                $msg = '<option value="">Please select One</option>';
                $a[$indx]->eqip_dropdown = $msg;
            } else {

                $msg = '<option value="">Please select One</option>';
                foreach ($datas as $data) {
                    $msg .= '<option value="' . $data->equip_id . '" >' . $data->equipment_name . '</option>';
                }
                $a[$indx]->eqip_dropdown = $msg;
            }

        }

        /*$msg1 = '<option value="">Please select One</option>';

        foreach ($a as $indx => $value) {
            $sql = "SELECT EQUIP_ID,
       EQUIP_NAME || ' - ' || EQUIP_TYPE equipment_name
  FROM EQMS.EQUIPMENT
 WHERE EQUIP_TYPE_ID IN (SELECT EQUIP_TYPE_ID
                           FROM EQUIP_REQUEST_DTL
                          WHERE EQUIP_TYPE_ID = :EQUIP_TYPE_ID)";
            $datas = db::select($sql, ['EQUIP_TYPE_ID' => $a[$indx]->equip_type_id]);

            if(count($datas)==0){
                $msg = '<option value="">Please select One</option>';
                $a[$indx]->eqip_dropdown = $msg;
            }else{

                $msg = '<option value="">Please select One</option>';
                foreach ($datas as $data) {
                    $msg .= '<option value="' . $data->equip_id . '" ' . ($data->equip_id === $a[$indx]->roster_eqp_id ? ' selected' : "") . '>' . $data->equipment_name . '</option>';
                }
                $a[$indx]->eqip_dropdown = $msg;
            }

        }
        //dd($a);
        $sql1 = "SELECT dd.OPERATOR_EMP_CODE || ' - ' || dd.operator_name op_name, operator_id, r_d_id
  FROM EQMS.ROSTER_DTL dd
 WHERE dd.LOCATION_ID IN (SELECT LOCATION_ID
                            FROM EQUIP_REQUEST_DTL
                           WHERE LOCATION_ID = :LOCATION_ID)";
        $datas1 = db::select($sql1,['LOCATION_ID' => $b->location_id]);
        foreach ($datas1 as $data){
            $msg1 .= '<option value="'.$data->r_d_id.'">'.$data->op_name.'</option>';
        }
        //dd($msg1);
        foreach ($a as $indx => $value) {
            $a[$indx]->emp_dropdown = $msg1;
        }*/
        //dd($a);
        return response(
            [
                'equip_request_dtl' => $a,
                'equip_request_mst' => $b,
            ]
        );
    }

    public function subChk($eqr_id)
    {
        $a = DB::table("equip_request_dtl")
            ->select("erd_id", "erm_id", "eqr_id", "equip_type", "load_capacity", "requested_equip", "equip_type_id", "load_capacity_id", "supplied_equip", "supplied_date")
            ->orderBy("insert_date", "DESC")
            ->where("eqr_id", "=", $eqr_id)
            ->whereNotNull('supplied_equip')
            ->whereNotNull('supplied_date')
            ->get();
        $b = DB::table("equip_request_dtl")
            ->select("erd_id", "erm_id", "eqr_id", "equip_type", "load_capacity", "requested_equip", "equip_type_id", "load_capacity_id", "supplied_equip", "supplied_date")
            ->orderBy("insert_date", "DESC")
            ->where("eqr_id", "=", $eqr_id)
            ->get();
        if (count($a) == count($b)) {
            return count($a);
        } else {
            return 0;
        }
    }

    public function approveReject(Request $request)
    {
        //dd($request->all());
        $today = date("Y-m-d H:i:s");
        $stat = 0;
        if ($request->get('apprv_status') == 'F') {
            $stat = 1;
        } else if ($request->get('apprv_status') == 'R') {
            $stat = 3;
        } else if ($request->get('apprv_status') == 'B') {
            $stat = 2;
        }//dd($stat);
        if($stat == 1 || $stat== 3){
            DB::beginTransaction();
            $response = DB::table('approval_info')
                ->where('approval_info_id', $request->get('approval_info_id'))
                ->update(['APPROVAL_STATUS_ID' => $stat, 'APPROVE_DATE' => $today, 'CURRENT_YN' => 'N',
                    'APPROVE_REJECT_NOTES' => $request->get('comments'), 'APPLICATION_RECEIVE_DATE' => $today]);
            $current_step_data = DB::table('approval_info')->where('approval_info_id', $request->get('approval_info_id'))->first();

            $nxt_step_data = DB::table('approval_info')
                ->where('approval_ref_seq', ($current_step_data->approval_ref_seq + 1))
                ->where('reference_id', $current_step_data->reference_id)->update(['CURRENT_YN' => 'Y']);

            if ($stat == 3) {
                $reject_data = DB::table('EQUIP_REQUEST')
                    ->where('eqr_id', $request->get('app_eqr_id'))->update(['REQ_STATUS_ID' => 3]);
            }
            $nxt_step_chk = DB::table('approval_info')
                ->where('approval_ref_seq', ($current_step_data->approval_ref_seq + 1))
                ->where('reference_id', $current_step_data->reference_id)->first();

            if($nxt_step_chk==null && $stat = 1){
                $update_data = DB::table('EQUIP_REQUEST')
                    ->where('eqr_id', $request->get('app_eqr_id'))->update(['REQ_STATUS_ID' => 2]);
            }
            DB::commit();
        }

        if($stat == 2){
            DB::beginTransaction();
            $current_step_data = DB::table('approval_info')
                ->where('approval_info_id', $request->get('approval_info_id'))
                ->first();

            $prev_step = DB::table('approval_info')
                ->where('approval_ref_seq', ($current_step_data->approval_ref_seq - 1))
                ->where('reference_id', $current_step_data->reference_id)->first();
            $prev_step_update = DB::table('approval_info')
                ->where('approval_ref_seq', ($current_step_data->approval_ref_seq - 1))
                ->where('reference_id', $current_step_data->reference_id)->update(['APPLICATION_RECEIVE_DATE' => '',
                    'APPROVE_DATE'=>'','HISTORY_YN'=>'Y']);
            $current_step__update = DB::table('approval_info')
                ->where('approval_info_id', $request->get('approval_info_id'))
                ->update(['BACK_YN'=>'Y','CURRENT_YN'=>'N',]);
            $response = DB::table('APPROVAL_INFO')->insert(
                ['APPROVAL_INFO_ID' => DB::selectOne('select gen_unique_id  as unique_id from dual')->unique_id,
                    'APPROVAL_REF_SEQ' => $prev_step->approval_ref_seq, 'APPROVAL_SEQ_NUMBER' => '', 'APPROVAL_STATUS_ID' => 2,
                    'DEPARTMENT_HEAD_YN' => $prev_step->department_head_yn, 'EDIT_PERMISSION_YN' => $prev_step->edit_permission_yn,
                    'EMP_CODE' => $prev_step->emp_code, 'EMP_NAME' => $prev_step->emp_name,
                    'RECIPIENT_DEPT_ID' => $prev_step->recipient_dept_id, 'RECIPIENT_EMP_ID' => $prev_step->recipient_emp_id,
                    'REFERENCE_ID' => $prev_step->reference_id, 'RULE_ID' => 1, 'STEP_TITLE_ID' => $prev_step->step_title_id,
                    'WORKFLOW_RECIPIENT_ID' => $prev_step->workflow_recipient_id, 'CURRENT_YN' => 'Y','HISTORY_YN'=>'N',
                    'NOTE' => $request->get('comments')]
            );
            DB::commit();
        }


        if ($response != true) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . 'An Error Occurred!!!');
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', 'Approved Successfully.');

        return redirect()->route('equip-request-approval-index');
    }
}
