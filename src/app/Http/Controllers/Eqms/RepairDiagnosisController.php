<?php


namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\ApprovalInfo;
use App\Entities\Eqms\L_Malfunction;
use App\Entities\Eqms\L_Workshop;
use App\Entities\Eqms\L_WorkshopTeam;
use App\Entities\Eqms\L_WorkshopType;
use App\Entities\Eqms\RepairDiagnosisEmp;
use App\Entities\Eqms\RepairDiagnosisMst;
use App\Entities\Eqms\RepairPartRequestTeam;
use App\Entities\Eqms\RepairRequestDtl;
use App\Entities\Eqms\RepairRequestMst;
use App\Entities\Eqms\Workflow;
use App\Entities\Eqms\WorkflowTeam;
use App\Entities\Eqms\WSDiagTeam;
use App\Entities\Pmis\Employee\Employee;
use App\Entities\Security\Menu;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;
use PhpParser\Node\Stmt\DeclareDeclare;

class RepairDiagnosisController extends Controller
{
    public function index()
    {
//        dd( Auth()->user()->employee->emp_id)
        return view('eqms.repairdiagnosis.index');
    }

    public function dataTableList()
    {
        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));
        $userSection = Employee::select('SECTION_ID', 'EMP_ID')->where('EMP_ID', '=', Auth::user()->emp_id)->first(); // So that logged in user can see only his Assigned Sections data.
//         dd($userSection);

        /*if (strpos($user_role, "SUPER_ADMIN") !== FALSE) {
            $querys = "SELECT DISTINCT rrm.r_r_mst_id,
                rrm.r_r_no,
                rrm.equip_name,
                rrm.EQUIP_NO,
                rrm.r_r_by_emp_name,
                rrm.r_r_date,
                rrm.equip_id,
                rrm.resolve_yn,
                eq.equip_model,
                eq.WORKSHOP_NAME,
                rrm.REQ_STATUS_ID,rrm.submit_approval,
                rrm.INSERT_DATE,
       CASE
           WHEN rrm.REQ_STATUS_ID = 1 THEN 'APPROVED'
           WHEN rrm.REQ_STATUS_ID = 2 THEN 'PENDING'
           WHEN rrm.REQ_STATUS_ID = 3 THEN 'REJECTED'
           WHEN rrm.REQ_STATUS_ID = 4 THEN 'RESOLVED'
           WHEN rrm.REQ_STATUS_ID = 5 THEN 'NOT RESOLVED'
       END
           AS STATUS
  FROM repair_request_mst  rrm
       LEFT JOIN approval_info ai ON ai.reference_id = rrm.r_r_mst_id
       LEFT JOIN equipment eq ON eq.equip_id = rrm.equip_id
       ORDER BY rrm.INSERT_DATE DESC";
            $queryResult = db::select($querys);
        } else {
            $querys = "SELECT DISTINCT
         rrm.r_r_mst_id,
         rrm.r_r_no,
         rrm.equip_name,
         rrm.equip_id,
         rrm.EQUIP_NO,
         rrm.r_r_by_emp_name,
         rrm.r_r_date,
         rrm.resolve_yn,
         eq.equip_model,
         eq.WORKSHOP_NAME,
         rrm.req_status_id,
         rrm.submit_approval,
         rrm.INSERT_DATE,
         CASE
             WHEN rrm.REQ_STATUS_ID = 1 THEN 'APPROVED'
             WHEN rrm.REQ_STATUS_ID = 2 THEN 'PENDING'
             WHEN rrm.REQ_STATUS_ID = 3 THEN 'REJECTED'
             WHEN rrm.REQ_STATUS_ID = 4 THEN 'RESOLVED'
             WHEN rrm.REQ_STATUS_ID = 5 THEN 'NOT RESOLVED'
         END
             AS STATUS
    FROM repair_request_mst rrm
         LEFT JOIN equipment eq ON eq.equip_id = rrm.equip_id
   WHERE rrm.INSERT_BY = :insert_by
ORDER BY rrm.INSERT_DATE DESC";
            $queryResult = db::select($querys, ['insert_by' => auth()->id()]);
        }*/
         if (strpos($user_role, "SUPER_ADMIN") !== FALSE) {
        $querys = "SELECT DISTINCT rrm.r_r_mst_id,
                rrm.r_r_no,
                rrm.equip_name,
                rrm.EQUIP_NO,
                rrm.r_r_by_emp_name,
                rrm.r_r_date,
                rrm.equip_id,
                rrm.resolve_yn,
                eq.equip_model,
                eq.WORKSHOP_NAME,
                rrm.REQ_STATUS_ID,rrm.submit_approval,
                rrm.INSERT_DATE,
                rrm.item_demand_id,
       CASE
           WHEN rrm.REQ_STATUS_ID = 1 THEN 'APPROVED'
           WHEN rrm.REQ_STATUS_ID = 2 THEN 'PENDING'
           WHEN rrm.REQ_STATUS_ID = 3 THEN 'REJECTED'
           WHEN rrm.REQ_STATUS_ID = 4 THEN 'RESOLVED'
           WHEN rrm.REQ_STATUS_ID = 5 THEN 'NOT RESOLVED'
       END
           AS STATUS
  FROM repair_request_mst  rrm
       LEFT JOIN approval_info ai ON ai.reference_id = rrm.r_r_mst_id
       LEFT JOIN equipment eq ON eq.equip_id = rrm.equip_id
       ORDER BY rrm.INSERT_DATE DESC";
        $queryResult = db::select($querys);
    }else{
        $querys = "SELECT DISTINCT rrm.r_r_mst_id,
                rrm.r_r_no,
                rrm.equip_name,
                rrm.EQUIP_NO,
                rrm.r_r_by_emp_name,
                rrm.r_r_date,
                rrm.equip_id,
                rrm.resolve_yn,
                eq.equip_model,
                eq.WORKSHOP_NAME,
                eq.WORKSHOP_ID,
                rrm.REQ_STATUS_ID,rrm.submit_approval,
                rrm.INSERT_DATE,
                rrm.item_demand_id,
       CASE
           WHEN rrm.REQ_STATUS_ID = 1 THEN 'APPROVED'
           WHEN rrm.REQ_STATUS_ID = 2 THEN 'PENDING'
           WHEN rrm.REQ_STATUS_ID = 3 THEN 'REJECTED'
           WHEN rrm.REQ_STATUS_ID = 4 THEN 'RESOLVED'
           WHEN rrm.REQ_STATUS_ID = 5 THEN 'NOT RESOLVED'
       END
           AS STATUS
  FROM repair_request_mst  rrm
       LEFT JOIN approval_info ai ON ai.reference_id = rrm.r_r_mst_id
       LEFT JOIN equipment eq ON eq.equip_id = rrm.equip_id
       where eq.WORKSHOP_ID = $userSection->section_id

       ORDER BY rrm.INSERT_DATE DESC";
        $queryResult = db::select($querys);
    }

        return datatables()->of($queryResult)
            ->addColumn('r_r_no', function ($query) {
                if ($query->r_r_no == null) {
                    return '--';
                } else {
                    return $query->r_r_no;
                }
            })
            
            ->addColumn('workshop_name', function ($query) {
                if ($query->workshop_name == null) {
                    return '--';
                } else {
                    return $query->workshop_name;
                }
            })
            ->addColumn('equip_name', function ($query) {
                return $query->equip_no . '-' . $query->equip_name;
            })
            ->addColumn('r_r_date', function ($query) {
                if ($query->r_r_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->r_r_date)->format('d-m-Y');
                }
            })
            ->addColumn('resolve_yn', function ($query) {

//                if ($query->status == "APPROVED") {
//                    $html = <<<HTML
//<span class="badge badge-success"> APPROVED</span>
//HTML;
//                    return $html;
//                } else if ($query->status == "PENDING") {
//                    $html = <<<HTML
//<span class="badge badge-warning"> PENDING</span>
//HTML;
//                    return $html;
//                } else {
//                    $html = <<<HTML
//<span class="badge badge-danger"> REJECTED</span>
//HTML;
//                    return $html;
//                }
//
//            })

                if ($query->req_status_id == 2 && $query->submit_approval == 'N') {
                    $html = '<span class="badge badge-info">Unassigned </span>';
                    return $html;
                } else if ($query->req_status_id == 2 && $query->submit_approval == 'Y') {
                    $html = '<span class="badge badge-warning">Pending Approval </span>';
                    return $html;
                } else if ($query->req_status_id == 3) {
                    $html = <<<HTML
                    <span class="badge badge-danger">Rejected</span>
HTML;
                    return $html;
                } else if ($query->req_status_id == 4) {
                    $html = <<<HTML
                    <span class="badge badge-success">Resolved</span>
HTML;
                    return $html;
                } else if ($query->req_status_id == 1) {
                    $html = <<<HTML
<span class="badge badge-success">Approved</span>
HTML;
                    return $html;
                }
            })


            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('repair-diagnosis-edit', [$query->r_r_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';

                // item_demand_id null means its ready for a new demand. item_demand_id not null means new demand is already created.
                if ($query->req_status_id == 1 && $query->item_demand_id == null) {
                    $url = Menu::where('menu_id', 42)
                        ->where('module_id', 45)
                        ->first()
                        ->base_url;
//                    $route = externalLoginUrl($url, '/create-item-demand?module_id=59&ref=' . $query->r_r_mst_id . '&equip_id=' . $query->equip_id);
                    $route = externalLoginUrl($url, '/create-item-demand?module_id=59&refcode=ED&ref=' . $query->r_r_mst_id . '&equip_id=' . $query->equip_id);

                    $actionBtn .= '   <a class="btn btn-sm p-2 btn-dark" role="button" target="_blank" title="New Demand" href="' . $route . '">New Demand</a>';
//                    $actionBtn .= '   <a target="_blank" title="Item Demand" href="' . $route . '"><i class="bx bx-customize cursor-pointer"></i></a>';
                }

                if ($query->req_status_id == 1 && $query->item_demand_id != null) {
                    $url = Menu::where('menu_id', 42)
                        ->where('module_id', 45)
                        ->first()
                        ->base_url;
//                if ($query->item_demand_id) {
//                  //$reporturl = '<a class="" data-toggle="tooltip" data-placement="top" title="Click to Print" data-original-title="Click to Print" target="_blank" href="' . externalLoginUrl($url, '/item-demand-issue-ems-rpt/' . $query->item_demand_id) . '"><i class="bx bx-printer cursor-pointer"></i></a>';
//                    $reporturl = '<a class="btn btn-sm p-2 btn-info" role="button" target="_blank" href="' . externalLoginUrl($url, '/item-demand-issue-ems-rpt/' . $query->item_demand_id) . '">Report</a>';
//                } else {
//                    $reporturl = '';
//                }

                    $reporturl = '<a class="btn btn-sm p-2 btn-primary" role="button" target="_blank" href="' . externalLoginUrl($url, '/item-demand-issue-ems-rpt/' . $query->item_demand_id) . '">Report</a>';
                    $actionBtn = '<a title="Edit" href="' . route('repair-diagnosis-edit', [$query->r_r_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>' . $reporturl;
                }

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

        $qr = "select * from CIMS.ITEM_DEMAND_MST idm
where idm.MODULE_REQUISITION_ID = :r_r_mst_id";

        $inventoryData = db::select($qr, ['r_r_mst_id' => $id]);

        $querys = "SELECT DISTINCT
       m.MALFUNCTION,
       m.r_r_mst_id,
       m.r_r_d_id,
       m.MALFUNCTION_OTHER,
       d.MALFUNCTION_RESOLVE_YN,
       d.SEND_SERVICE_YN,
       d.MALFUNCTION_RESOLVE_DATE,
       d.ASSIGNED_WS_NAME,
       d.ASSIGNED_WS_TYPE,
       CASE WHEN d.R_R_DTL_ID IS NOT NULL THEN 'Y' ELSE 'N' END     FINDINGS
  FROM EQMS.REPAIR_REQUEST_DTL m, REPAIR_DIAGNOSIS_DTL d
 WHERE     m.R_R_MST_ID = d.R_R_MST_ID(+)
       AND m.R_R_D_ID = d.R_R_DTL_ID(+)
       AND m.R_R_MST_ID = NVL ( :R_R_MST_ID, m.R_R_MST_ID)";

        $dData = db::select($querys, ['R_R_MST_ID' => $id]);

        $rdMst = RepairDiagnosisMst::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->first();

        $whom_ids = WSDiagTeam::where('r_p_req_mst_id', $id)->get(['workshop_team_id'])->pluck('workshop_team_id')->toArray();

        $querys2 = "SELECT * FROM EQUIPMENT WHERE EQUIP_ID NOT IN (SELECT EQUIP_ID FROM EQMS.SERVICE_MST)";
        $eqList = db::select($querys2);

        $dData2 = RepairRequestDtl::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->orderBy('insert_date', 'desc')
            ->get();

        $approvalData = ApprovalInfo::with(['employee', 'status'])
            ->where('reference_id', $id)
            //->where('current_yn', 'Y')
            ->orderBy('APPROVAL_REF_SEQ', 'ASC')
            ->pluck('approval_ref_seq')->toArray();
        if (count($approvalData) > 0) {
            $max_seq = max($approvalData);
            $min_seq = min($approvalData);
        } else {
            $max_seq = null;
            $min_seq = null;
        }

        $curr_data = DB::table('approval_info')
            ->where('reference_id', $id)
            ->where('current_yn', 'Y')
            ->where('recipient_emp_id', Auth()->user()->employee->emp_id)
            ->first();//dd(Auth()->user()->employee->emp_id);
        if ($curr_data != null) {
            $next_data = DB::table('approval_info')
                ->where('reference_id', $id)
                ->where('approval_ref_seq', ($curr_data->approval_ref_seq + 1))
                ->first();
        } else {
            $next_data = null;
        }//dd($curr_data);

        return view('eqms.repairdiagnosis.index', [
            'mData' => $mData,
            'dData' => $dData,
            'dData2' => $dData2,
            'eqList' => $eqList,
            'mfList' => L_Malfunction::all(),
            'next_data' => $next_data,
            'curr_data' => $curr_data,
            'workflows' => HelperClass::workflow(1, $id),
            'approvalData' => $approvalData,
            'rdMst' => $rdMst,
            'wtList' => L_WorkshopType::all(),
            'teams2' => L_WorkshopTeam::all(),
            'whom_ids2' => $whom_ids,
            'max_seq' => $max_seq,
            'min_seq' => $min_seq,
            'inventoryData' => count($inventoryData),
            'lWorkshopList' => L_Workshop::orderBy('workshop_id', 'asc')->get(),
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
        return redirect('/repair-diagnosis');
    }

    private function ins_upd(Request $request)
    {
//         dd($request->all());

        $postData = $request->post();
        if (isset($postData['r_d_id'])) {
            $r_d_id = $postData['r_d_id'];
        } else {
            $r_d_id = '';
        }
        $r_d_date = $postData['r_d_date'];
        $r_d_date = isset($r_d_date) ? date('Y-m-d', strtotime($r_d_date)) : '';

//        $repDiagNo= 'RD-'.preg_replace("/[^0-9.]/", "",  $postData['r_d_no']); // regex to remove existing text from r_d_no

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_R_D_ID' => [
                    'value' => &$r_d_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
//              'p_R_D_NO' => isset($postData['r_d_no']) ? $repDiagNo: '',
                'p_R_D_NO' => $postData['r_d_no'],
                'p_R_R_MST_ID' => $postData['r_r_mst_id'],
                'p_R_D_DATE' => $r_d_date,
                'p_R_R_DESCRIPTION' => $postData['description'],
                'p_SUBMIT_APPROVAL' => isset($postData['update']) ? 'N' : 'Y',
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];//dd($params);
            DB::executeProcedure('EQMS.REPAIR_DIAGNOS_MST_INS_UPD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if ($request->get('workshop_team_id')) {

                RepairPartRequestTeam::where('r_p_req_mst_id', $postData['r_r_mst_id'])
                    ->delete();

                foreach ($request->get('workshop_team_id') as $indx => $value) {
                    //if(count($array_data)==0){
                    $r_p_r_team_id = '';
                    /*}else{
                        $r_p_r_team_id = $array_data[$indx];
                    }*/

                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'p_R_P_R_TEAM_ID' => [
                            'value' => &$r_p_r_team_id,
                            'type' => \PDO::PARAM_INPUT_OUTPUT,
                            'length' => 255
                        ],
                        "p_R_P_REQ_MST_ID" => $postData['r_r_mst_id'],
                        "p_WORKSHOP_TEAM_ID" => $request->get('workshop_team_id')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ]; //dd($params_dtl);

                    DB::executeProcedure("EQMS.WORKSHOP_ACTIVITIES_TEAM_IU", $params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                }
            }

            if ($request->get('tab_malfunction_id')) {

                foreach ($request->get('tab_malfunction_id') as $indx => $value) {
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $w_name = L_Workshop::where('workshop_id', $request->get('tab_workshop_id')[$indx])->first();//dd($w_name->workshop_name);
//                    $w_name = $w_name->workshop_name;
                    $w_name = $w_name['workshop_name'];
                    $params_dtl = [
                        'p_R_R_D_ID' => $request->get('tab_r_r_d_id')[$indx],
                        "p_R_R_MST_ID" => $postData['r_r_mst_id'],
                        "p_MALFUNCTION_ID" => $request->get('tab_malfunction_id')[$indx],
                        "p_MALFUNCTION_OTHER" => $request->get('tab_malfunction_other')[$indx],
                        "p_REPAIR_WORKSHOP_ID" => $request->get('tab_workshop_id')[$indx],
                        "p_REPAIR_WORKSHOP_NAME" => $w_name,
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];

                    DB::executeProcedure("EQMS.REPAIR_REQ_DTL_INS_UPD", $params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                }
            }

            if (isset($postData['update']) && $postData['update'] == 1) {
                $data = Workflow::where('reference_id', $postData['r_r_mst_id'])->delete();//dd($data);
                $myString = $postData['sequenceData'];
                $personList = explode(',', $myString);


                try {
                    $workflow_recipient_id = null;
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");

                    foreach ($personList as $key => $team) {
                        $teamInfo = WorkflowTeam::with('employee')->where('emp_code', $team)->first();
                        //dd($teamInfo);
                        $params2 = [
                            'p_workflow_recipient_id' => $workflow_recipient_id,
                            'p_rule_id' => 1,
                            'p_active_yn' => 'Y',
                            'p_employee_id' => $teamInfo->emp_id,
                            'p_department_id' => $teamInfo->emp_department_id,
                            'p_department_head_yn' => 'N',
                            'p_edit_permission_yn' => 'Y',
                            'p_designation_id' => 0,//isset($teamInfo->employee->charge_designation_id) ? $teamInfo->employee->charge_designation_id : $teamInfo->employee->designation_id,
                            'p_designation_name' => 'NONE',//isset($teamInfo->employee->charge_designation_id) ? $teamInfo->employee->addi_designation->designation : $teamInfo->employee->designation->designation,
                            'p_reference_id' => $postData['r_r_mst_id'],
                            'p_employee_code' => $teamInfo->emp_code,
                            'p_employee_name' => $teamInfo->employee->emp_name,
                            'p_insert_by' => auth()->id(),
                            'o_status_code' => &$status_code,
                            'o_status_message' => &$status_message,
                        ];//dd($params);
                        // print_r($params);

                        DB::executeProcedure("workflow_recipient_save", $params2);//dd($params);
                        //Log::info($params2);
                    }

                    // dd( $params);
                } catch (\Exception $e) {
                    //Log::info($e->getMessage());
                    //exit;
                    return ["exception" => true, "o_status_code" => 99, "o_status_message" => 'Something went wrong.'];

                }
            }

            if (isset($postData['approve']) && $postData['approve'] == 1) {
                $update = RepairRequestMst::where('r_r_mst_id', $postData['r_r_mst_id'])->update(['submit_approval' => 'Y']);
                $data = Workflow::where('reference_id', $postData['r_r_mst_id'])->delete();
                $myString = $postData['sequenceData'];
                $personList = explode(',', $myString);

                $workflow_recipient_id = null;
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");

                foreach ($personList as $key => $team) {
                    $teamInfo = WorkflowTeam::with('employee')->where('emp_code', $team)->first();//dd($teamInfo);
                    //dd($teamInfo);
                    $params2 = [
                        'p_workflow_recipient_id' => $workflow_recipient_id,
                        'p_rule_id' => 1,
                        'p_active_yn' => 'Y',
                        'p_employee_id' => $teamInfo->emp_id,
                        'p_department_id' => $teamInfo->emp_department_id,
                        'p_department_head_yn' => 'N',
                        'p_edit_permission_yn' => 'Y',
                        'p_designation_id' => 0,//isset($teamInfo->employee->charge_designation_id) ? $teamInfo->employee->charge_designation_id : $teamInfo->employee->designation_id,
                        'p_designation_name' => 'NONE',//isset($teamInfo->employee->charge_designation_id) ? $teamInfo->employee->addi_designation->designation : $teamInfo->employee->designation->designation,
                        'p_reference_id' => $postData['r_r_mst_id'],
                        'p_employee_code' => $teamInfo->emp_code,
                        'p_employee_name' => $teamInfo->employee->emp_name,
                        'p_insert_by' => auth()->id(),
                        'o_status_code' => &$status_code,
                        'o_status_message' => &$status_message,
                    ];//dd($params);
                    // print_r($params);

                    DB::executeProcedure("workflow_recipient_save", $params2);//dd($params);
                    //Log::info($params2);
                }

                $datas = Workflow::where('reference_id', $postData['r_r_mst_id'])->orderBy('APPROVAL_SEQ_NO', 'asc')->get();
                if (count($datas) > 0) {
                    $data = 0;
                    foreach ($datas as $indx => $value) {
                        $current = 'N';
                        if ($data == 0) {
                            $current = 'Y';
                            $data++;
                        }

                        $ins = DB::table('APPROVAL_INFO')->insert(
                            ['APPROVAL_INFO_ID' => DB::selectOne('select gen_unique_id  as unique_id from dual')->unique_id, 'APPROVAL_REF_SEQ' => $datas[$indx]['approval_seq_no'],
                                'APPROVAL_SEQ_NUMBER' => '', 'APPROVAL_STATUS_ID' => 2, 'DEPARTMENT_HEAD_YN' => $datas[$indx]['department_head_yn'],
                                'EDIT_PERMISSION_YN' => $datas[$indx]['edit_permission_yn'], 'EMP_CODE' => $datas[$indx]['emp_code'],
                                'EMP_NAME' => $datas[$indx]['emp_name'], 'RECIPIENT_DEPT_ID' => $datas[$indx]['department_id'],
                                'RECIPIENT_EMP_ID' => $datas[$indx]['employee_id'], 'REFERENCE_ID' => $postData['r_r_mst_id'],
                                'RULE_ID' => 1, 'STEP_TITLE_ID' => $datas[$indx]['recipient_designation_id'],
                                'WORKFLOW_RECIPIENT_ID' => $datas[$indx]['workflow_recipient_id'], 'CURRENT_YN' => $current]
                        );
                    }
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            //dd($e);
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function approveReject(Request $request)
    {
        //dd($request->all());
        $r_r_mst_app_ref_id = ApprovalInfo::select('reference_id')
            ->where('approval_info_id', '=', $request->get('approval_info_id'))
            ->first();
        $r_r_mst_app_ref_id = $r_r_mst_app_ref_id->reference_id;
//        dd( $r_r_mst_app_ref_id);
        $today = date("Y-m-d H:i:s");
        $stat = 0;
        if ($request->get('apprv_status') == 'F') {
            $stat = 1;
        } else if ($request->get('apprv_status') == 'R') {
            $stat = 3;
        } else if ($request->get('apprv_status') == 'B') {
            $stat = 2;
        }//dd($stat);
        if ($stat == 1 || $stat == 3) {
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
                    ->where('r_r_mst_id', $r_r_mst_app_ref_id)->update(['REQ_STATUS_ID' => 3]);
            }
            $nxt_step_chk = DB::table('approval_info')
                ->where('approval_ref_seq', ($current_step_data->approval_ref_seq + 1))
                ->where('reference_id', $current_step_data->reference_id)->first();

            if ($nxt_step_chk == null && $stat = 1) {
                $update_data = DB::table('REPAIR_REQUEST_MST')
                    ->where('r_r_mst_id', $r_r_mst_app_ref_id)->update(['REQ_STATUS_ID' => 1]);
            }
            DB::commit();
        }

        if ($stat == 2) {
            DB::beginTransaction();
            $response = DB::table('approval_info')
                ->where('approval_info_id', $request->get('approval_info_id'))
                ->update(['CURRENT_YN' => 'N', 'BACK_YN' => 'Y', 'APPROVE_REJECT_NOTES' => $request->get('comments')]);
            $current_step_data = DB::table('approval_info')
                ->where('approval_info_id', $request->get('approval_info_id'))
                ->first();
            $prev_step_data = DB::table('approval_info')
                ->where('reference_id', $current_step_data->reference_id)
                ->where('approval_ref_seq', $current_step_data->approval_ref_seq - 1)
                ->update(['CURRENT_YN' => 'Y', 'BACK_YN' => 'N', 'APPROVAL_STATUS_ID' => 2]);
            DB::commit();
        }

        if ($request->get('tab_malfunction_id')) {

            foreach ($request->get('tab_malfunction_id') as $indx => $value) {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $w_name = L_Workshop::where('workshop_id', $request->get('tab_workshop_id')[$indx])->first();//dd($w_name->workshop_name);
//                $w_name = $w_name->workshop_name;
                $w_name = $w_name['workshop_name'];
                $params_dtl = [
                    'p_R_R_D_ID' => $request->get('tab_r_r_d_id')[$indx],
                    "p_R_R_MST_ID" => $r_r_mst_app_ref_id,
                    "p_MALFUNCTION_ID" => $request->get('tab_malfunction_id')[$indx],
                    "p_MALFUNCTION_OTHER" => $request->get('tab_malfunction_other')[$indx],
                    "p_REPAIR_WORKSHOP_ID" => $request->get('tab_workshop_id')[$indx],
                    "p_REPAIR_WORKSHOP_NAME" => $w_name,
                    "P_INSERT_BY" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message
                ];

                DB::executeProcedure("EQMS.REPAIR_REQ_DTL_INS_UPD", $params_dtl);
                if ($params_dtl['o_status_code'] != 1) {
                    DB::rollBack();
                    return $params_dtl;
                }
            }
        }

        if ($request->get('r_r_d_id')) {
            $r_r_d_id = explode(',', $request->get('r_r_d_id'));
            $malfunction_id = explode(',', $request->get('malfunction_id'));
            $malfunction_other = explode(',', $request->get('malfunction_other'));
            $workshop_id = explode(',', $request->get('workshop_id'));//dd($workshop_id);

            foreach ($r_r_d_id as $indx => $value) {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $w_name = L_Workshop::where('workshop_id', $workshop_id[$indx])->first();//dd($workshop_id[$indx]);
                $w_name = $w_name->workshop_name;
                $params_dtl = [
                    'p_R_R_D_ID' => $r_r_d_id[$indx],
                    "p_R_R_MST_ID" => $r_r_mst_app_ref_id,
                    "p_MALFUNCTION_ID" => $malfunction_id[$indx],
                    "p_MALFUNCTION_OTHER" => $malfunction_other[$indx],
                    "p_REPAIR_WORKSHOP_ID" => $workshop_id[$indx],
                    "p_REPAIR_WORKSHOP_NAME" => $w_name,
                    "P_INSERT_BY" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message
                ];

                DB::executeProcedure("EQMS.REPAIR_REQ_DTL_INS_UPD", $params_dtl);
                if ($params_dtl['o_status_code'] != 1) {
                    DB::rollBack();
                    return $params_dtl;
                }
            }
        }


        if ($response != true) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . 'An Error Occurred!!!');
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', 'Approved Successfully.');

        //return redirect()->route('repair-request-approval-index');
        //return redirect('/repair-diagnosis/' . number_format($request->get('app_r_r_mst_id'))+1);
        return redirect('/repair-request-approval');
    }
}
