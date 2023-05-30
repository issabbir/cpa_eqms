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
use App\Entities\Eqms\RepairRequestDtl;
use App\Entities\Eqms\RepairRequestMst;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class RepairReqApprovalController extends Controller
{
    public function index()
    {
        return view('eqms.repairreqapproval.index');
    }

    public function dataTableList()
    {

        $querys = "SELECT DISTINCT er.*
    FROM eqms.REPAIR_REQUEST_MST er, APPROVAL_INFO ai
   WHERE     er.R_R_MST_ID = ai.REFERENCE_ID
         AND ai.RECIPIENT_EMP_ID = :RECIPIENT_EMP_ID
         AND EXISTS
                 (SELECT *
                    FROM APPROVAL_INFO ai
                   WHERE     ai.RECIPIENT_EMP_ID = :RECIPIENT_EMP_ID)
ORDER BY er.INSERT_DATE DESC";

        $queryResult = DB::select($querys, ['recipient_emp_id' => Auth()->user()->employee->emp_id]);

        return datatables()->of($queryResult)
            ->addColumn('r_r_date', function ($query) {
                if ($query->r_r_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->r_r_date)->format('d-m-Y');
                }
            })
            ->addColumn('action', function ($query) {
                    $actionBtn = '<a title="Edit" href="' . route('repair-request-approval-edit', [$query->r_r_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                    return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $mData = RepairRequestMst::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->first();
        $mdData = RepairRequestDtl::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->get();
        $approvalData = ApprovalInfo::with(['employee', 'status'])
            ->where('reference_id', $id)
            //->where('current_yn', 'Y')
            ->orderBy('APPROVAL_REF_SEQ','ASC')
            ->pluck('approval_ref_seq')->toArray();
            //dd(array_search("3",$approvalData));
        $curr_data = DB::table('approval_info')
            ->where('reference_id', $id)
            ->where('recipient_emp_id', Auth()->user()->employee->emp_id)
            ->first();//dd($curr_data->approval_ref_seq);
        $next_data = DB::table('approval_info')
            ->where('reference_id', $id)
            ->where('approval_ref_seq', ($curr_data->approval_ref_seq+1))
            ->first();

        return view('eqms.repairreqapproval.index', [
            'mData' => $mData,
            'mdData' => $mdData,
            'next_data' => $next_data,
            'curr_data' => $curr_data,
            'workflows' => HelperClass::workflow(1, $id),
            'approvalData' => $approvalData,
        ]);
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
                $reject_data = DB::table('REPAIR_REQUEST_MST')
                    ->where('r_r_mst_id', $request->get('app_r_r_mst_id'))->update(['REQ_STATUS_ID' => 3]);
            }
            $nxt_step_chk = DB::table('approval_info')
                ->where('approval_ref_seq', ($current_step_data->approval_ref_seq + 1))
                ->where('reference_id', $current_step_data->reference_id)->first();

            if($nxt_step_chk==null && $stat = 1){
                $update_data = DB::table('REPAIR_REQUEST_MST')
                    ->where('r_r_mst_id', $request->get('app_r_r_mst_id'))->update(['REQ_STATUS_ID' => 2]);
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
            $response = DB::table('APPROVAL_INFO')->insert(
                ['APPROVAL_INFO_ID' => DB::selectOne('select gen_unique_id  as unique_id from dual')->unique_id,
                    'APPROVAL_REF_SEQ' => $current_step_data->approval_ref_seq, 'APPROVAL_SEQ_NUMBER' => '', 'APPROVAL_STATUS_ID' => 2,
                    'DEPARTMENT_HEAD_YN' => $prev_step->department_head_yn, 'EDIT_PERMISSION_YN' => $prev_step->edit_permission_yn,
                    'EMP_CODE' => $prev_step->emp_code, 'EMP_NAME' => $prev_step->emp_name,
                    'RECIPIENT_DEPT_ID' => $prev_step->recipient_dept_id, 'RECIPIENT_EMP_ID' => $prev_step->recipient_emp_id,
                    'REFERENCE_ID' => $prev_step->reference_id, 'RULE_ID' => 1, 'STEP_TITLE_ID' => $prev_step->step_title_id,
                    'WORKFLOW_RECIPIENT_ID' => $prev_step->workflow_recipient_id, 'CURRENT_YN' => 'N','HISTORY_YN'=>'Y',
                    'NOTE' => $request->get('comments')]
            );
            $prev_step_update = DB::table('approval_info')
                ->where('approval_ref_seq', ($current_step_data->approval_ref_seq - 1))
                ->where('reference_id', $current_step_data->reference_id)->update(['APPLICATION_RECEIVE_DATE' => '',
                    'APPROVE_DATE'=>'','HISTORY_YN'=>'N','CURRENT_YN'=>'Y','APPROVAL_STATUS_ID'=>'2']);
            $current_step__update = DB::table('approval_info')
                ->where('approval_info_id', $request->get('approval_info_id'))
                ->update(['BACK_YN'=>'Y','CURRENT_YN'=>'N']);
            $repair_req_update = DB::table('REPAIR_REQUEST_MST')
                ->where('R_R_MST_ID', $request->get('app_r_r_mst_id'))->update(['REQ_STATUS_ID' => 2]);

            DB::commit();
        }


        if ($response != true) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . 'An Error Occurred!!!');
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', 'Approved Successfully.');

        return redirect()->route('repair-request-approval-index');
    }
}
