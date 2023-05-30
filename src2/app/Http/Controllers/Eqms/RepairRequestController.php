<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\Equipment;
use App\Entities\Eqms\EquipmentRequest;
use App\Entities\Eqms\EquipmentRequestDtl;
use App\Entities\Eqms\EquipmentRequestMst;
use App\Entities\Eqms\L_Equipment_Type;
use App\Entities\Eqms\L_EquipmentRequester;
use App\Entities\Eqms\L_Load_Capacity;
use App\Entities\Eqms\L_LocationType;
use App\Entities\Eqms\L_Malfunction;
use App\Entities\Eqms\L_RequestStatus;
use App\Entities\Eqms\L_RosterShift;
use App\Entities\Eqms\L_RosterYear;
use App\Entities\Eqms\L_WorkType;
use App\Entities\Eqms\Location;
use App\Entities\Eqms\RepairRequestDtl;
use App\Entities\Eqms\RepairRequestMst;
use App\Entities\Eqms\RosterDetail;
use App\Entities\Eqms\RosterMaster;
use App\Entities\Eqms\Workflow;
use App\Entities\Eqms\WorkflowTeam;
use App\Entities\Pmis\Employee\Employee;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;

class RepairRequestController extends Controller
{
    public function index()
    {

        $querys = "SELECT *
  FROM EQUIPMENT
 WHERE EQUIP_ID NOT IN (SELECT EQUIP_ID FROM EQMS.SERVICE_MST)" ;

        $eqList = db::select($querys);
        return view('eqms.repairrequest.index', [
            'eqList' => $eqList,
            'mfList' => L_Malfunction::all(),
            'gen_uniq_id' => DB::selectOne('select eqms.YMD_SEQUENCE  as unique_id from dual')->unique_id,
        ]);
    }

    public function dataTableList()
    {
        $queryResult = RepairRequestMst::orderBy('insert_date', 'desc')->get();
        return datatables()->of($queryResult)
            ->addColumn('r_r_date', function ($query) {
                if ($query->r_r_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->r_r_date)->format('d-m-Y');
                }
            })
            ->addColumn('action', function ($query) {
                if ($query->req_status_id == 1 && $query->submit_approval == 'N') {
                    $actionBtn = '<a title="Edit" href="' . route('repair-request-edit', [$query->r_r_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                    return $actionBtn;
                } else if ($query->req_status_id == 1 && $query->submit_approval == 'Y') {
                    $html = <<<HTML
<span class="badge badge-warning">Pending For Approval</span>
HTML;
                    return $html;
                }else if ($query->req_status_id == 3) {
                    $html = <<<HTML
<span class="badge badge-danger">Rejected</span>
HTML;
                    return $html;
                }else if ($query->req_status_id == 2) {
                    $html = <<<HTML
<span class="badge badge-success">Approved</span>
HTML;
                    return $html;
                }
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit($id)
    {
        $querys = "SELECT *
  FROM EQUIPMENT
 WHERE EQUIP_ID NOT IN (SELECT EQUIP_ID FROM EQMS.SERVICE_MST)" ;

        $eqList = db::select($querys);

        $mData = RepairRequestMst::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->first();
        $dData = RepairRequestDtl::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->orderBy('insert_date', 'desc')
            ->get();

        return view('eqms.repairrequest.index', [
            'mData' => $mData,
            'dData' => $dData,
            'eqList' => $eqList,
            'mfList' => L_Malfunction::all(),
        ]);
    }

    public function post(Request $request)
    {//dd($request);
        $response = $this->ins_upd($request);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('repair-request-index');
    }

    public function update(Request $request, $id)
    {//dd($request);
        $response = $this->ins_upd($request, $id);

        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('repair-request-index');
    }

    private function ins_upd(Request $request)
    {//dd($request->all());
        $postData = $request->post();
        if (isset($postData['r_r_mst_id'])) {
            $r_r_mst_id = $postData['r_r_mst_id'];
        } else {
            $r_r_mst_id = '';
        }
        $r_r_date = $postData['r_r_date'];
        $r_r_date = isset($r_r_date) ? date('Y-m-d', strtotime($r_r_date)) : '';
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_R_R_MST_ID' => [
                    'value' => &$r_r_mst_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_R_R_NO' => $postData['r_r_no'],
                'p_R_R_BY_EMP_ID' => $postData['r_r_by_emp_id'],
                'p_EQUIP_ID' => $postData['equip_id'],
                'p_R_R_DATE' => $r_r_date,
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.REPAIR_REQ_MST_INS_UPD', $params);//dd($params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if ($request->get('tab_malfunction_id')) {
                /*if ($r_r_mst_id!='') {
                    RepairRequestDtl::where('r_r_mst_id', $r_r_mst_id)->delete();
                }*/

                foreach ($request->get('tab_malfunction_id') as $indx => $value) {
                    //$r_r_d_id = null;
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'p_R_R_D_ID' => $request->get('tab_r_r_d_id')[$indx],
                        "p_R_R_MST_ID" => $params['p_R_R_MST_ID']['value'],
                        "p_MALFUNCTION_ID" => $request->get('tab_malfunction_id')[$indx],
                        "p_MALFUNCTION_OTHER" => $request->get('tab_malfunction_other')[$indx],
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

            if ($r_r_mst_id != null && isset($postData['update']) && $postData['update'] == '1') {
                $data = Workflow::where('reference_id', $r_r_mst_id)->delete();

                try {
                    $workflow_recipient_id = null;
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");

                    foreach (array_filter($postData['workflow_team']) as $key => $team) {
                        $teamInfo = WorkflowTeam::with('employee')->where('emp_code', $team)->first();//dd($teamInfo);
                        $params2 = [
                            'p_workflow_recipient_id' => $workflow_recipient_id,
                            'p_rule_id' => 1,
                            'p_active_yn' => 'Y',
                            'p_employee_id' => $teamInfo->emp_id,
                            'p_department_id' => $teamInfo->emp_department_id,
                            'p_department_head_yn' => 'N',
                            'p_edit_permission_yn' => 'Y',
                            'p_designation_id' => $teamInfo->employee->charge_designation_id ? $teamInfo->employee->charge_designation_id : $teamInfo->employee->designation_id,
                            'p_designation_name' => $teamInfo->employee->charge_designation_id ? $teamInfo->employee->addi_designation->designation : $teamInfo->employee->designation->designation,
                            'p_reference_id' => $r_r_mst_id,
                            'p_employee_code' => $teamInfo->emp_code,
                            'p_employee_name' => $teamInfo->employee->emp_name,
                            'p_insert_by' => auth()->id(),
                            'o_status_code' => &$status_code,
                            'o_status_message' => &$status_message,
                        ];
                        // print_r($params);

                        DB::executeProcedure("workflow_recipient_save", $params2);//dd($params);
                    }

                    // dd( $params);
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                    exit;
                    return ["exception" => true, "o_status_code" => 99, "o_status_message" => 'Something went wrong.'];

                }
            }

            if (isset($postData['approve']) && $postData['approve'] == 1) {
                $update = RepairRequestMst::where('r_r_mst_id', $r_r_mst_id)->update(['submit_approval' => 'Y']);
                $data = Workflow::where('reference_id', $r_r_mst_id)->delete();
                $workflow_recipient_id = null;
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");

                foreach (array_filter($postData['workflow_team']) as $key => $team) {
                    $teamInfo = WorkflowTeam::with('employee')->where('emp_code', $team)->first();//dd($teamInfo);
                    $params2 = [
                        'p_workflow_recipient_id' => $workflow_recipient_id,
                        'p_rule_id' => 1,
                        'p_active_yn' => 'Y',
                        'p_employee_id' => $teamInfo->emp_id,
                        'p_department_id' => $teamInfo->emp_department_id,
                        'p_department_head_yn' => 'N',
                        'p_edit_permission_yn' => 'Y',
                        'p_designation_id' => $teamInfo->employee->charge_designation_id ? $teamInfo->employee->charge_designation_id : $teamInfo->employee->designation_id,
                        'p_designation_name' => $teamInfo->employee->charge_designation_id ? $teamInfo->employee->addi_designation->designation : $teamInfo->employee->designation->designation,
                        'p_reference_id' => $r_r_mst_id,
                        'p_employee_code' => $teamInfo->emp_code,
                        'p_employee_name' => $teamInfo->employee->emp_name,
                        'p_insert_by' => auth()->id(),
                        'o_status_code' => &$status_code,
                        'o_status_message' => &$status_message,
                    ];
                    // print_r($params);

                    DB::executeProcedure("workflow_recipient_save", $params2);//dd($params);
                }

                $datas = Workflow::where('reference_id', $r_r_mst_id)->orderBy('APPROVAL_SEQ_NO','asc')->get();
                if (count($datas) > 0) {
                    $data = 0;
                    foreach ($datas as $indx => $value) {
                        $current = 'N';
                        if($data==0){
                            $current = 'Y';
                            $data++;
                        }

                        $ins = DB::table('APPROVAL_INFO')->insert(
                            ['APPROVAL_INFO_ID' => DB::selectOne('select gen_unique_id  as unique_id from dual')->unique_id, 'APPROVAL_REF_SEQ' => $datas[$indx]['approval_seq_no'],
                                'APPROVAL_SEQ_NUMBER' => '', 'APPROVAL_STATUS_ID' => 2, 'DEPARTMENT_HEAD_YN' => $datas[$indx]['department_head_yn'],
                                'EDIT_PERMISSION_YN' => $datas[$indx]['edit_permission_yn'], 'EMP_CODE' => $datas[$indx]['emp_code'],
                                'EMP_NAME' => $datas[$indx]['emp_name'], 'RECIPIENT_DEPT_ID' => $datas[$indx]['department_id'],
                                'RECIPIENT_EMP_ID' => $datas[$indx]['employee_id'], 'REFERENCE_ID' => $r_r_mst_id,
                                'RULE_ID' => 1, 'STEP_TITLE_ID' => $datas[$indx]['recipient_designation_id'],
                                'WORKFLOW_RECIPIENT_ID' => $datas[$indx]['workflow_recipient_id'], 'CURRENT_YN' => $current]
                        );
                    }
                }/* else {
                    $data = Workflow::where('reference_id', $r_r_mst_id)->delete();
                    $workflow_recipient_id = null;
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");

                    foreach (array_filter($postData['workflow_team']) as $key => $team) {
                        $teamInfo = WorkflowTeam::with('employee')->where('emp_code', $team)->first();//dd($teamInfo);
                        $params2 = [
                            'p_workflow_recipient_id' => $workflow_recipient_id,
                            'p_rule_id' => 1,
                            'p_active_yn' => 'Y',
                            'p_employee_id' => $teamInfo->emp_id,
                            'p_department_id' => $teamInfo->emp_department_id,
                            'p_department_head_yn' => 'N',
                            'p_edit_permission_yn' => 'Y',
                            'p_designation_id' => $teamInfo->employee->charge_designation_id ? $teamInfo->employee->charge_designation_id : $teamInfo->employee->designation_id,
                            'p_designation_name' => $teamInfo->employee->charge_designation_id ? $teamInfo->employee->addi_designation->designation : $teamInfo->employee->designation->designation,
                            'p_reference_id' => $r_r_mst_id,
                            'p_employee_code' => $teamInfo->emp_code,
                            'p_employee_name' => $teamInfo->employee->emp_name,
                            'p_insert_by' => auth()->id(),
                            'o_status_code' => &$status_code,
                            'o_status_message' => &$status_message,
                        ];
                        // print_r($params);

                        DB::executeProcedure("workflow_recipient_save", $params2);//dd($params);
                    }
                    $datas = Workflow::where('reference_id', $r_r_mst_id)->orderBy('workflow_recipient_id','asc')->get();
                    if (count($datas) > 0) {
                        foreach ($datas as $indx => $value) {
                            $current = 'N';
                            if ($datas[$indx] == 0) {dd($datas[$indx]);
                                $current = 'Y';
                            }
                            $ins = DB::table('APPROVAL_INFO')->insert(
                                ['APPROVAL_INFO_ID' => DB::selectOne('select gen_unique_id  as unique_id from dual')->unique_id, 'APPROVAL_REF_SEQ' => $datas[$indx]['approval_seq_no'],
                                    'APPROVAL_SEQ_NUMBER' => '', 'APPROVAL_STATUS_ID' => 2, 'DEPARTMENT_HEAD_YN' => $datas[$indx]['department_head_yn'],
                                    'EDIT_PERMISSION_YN' => $datas[$indx]['edit_permission_yn'], 'EMP_CODE' => $datas[$indx]['emp_code'],
                                    'EMP_NAME' => $datas[$indx]['emp_name'], 'RECIPIENT_DEPT_ID' => $datas[$indx]['department_id'],
                                    'RECIPIENT_EMP_ID' => $datas[$indx]['employee_id'], 'REFERENCE_ID' => $r_r_mst_id,
                                    'RULE_ID' => 1, 'STEP_TITLE_ID' => $datas[$indx]['recipient_designation_id'],
                                    'WORKFLOW_RECIPIENT_ID' => $datas[$indx]['workflow_recipient_id'], 'CURRENT_YN' => $current]
                            );
                        }
                    }
                }*/
            }

        } catch (\Exception $e) {//dd($e);
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function removeDtlData(Request $request)
    {
        try {
            foreach ($request->get('r_r_d_id') as $indx => $value) {
                RepairRequestDtl::where('r_r_d_id', $request->get("r_r_d_id")[$indx])->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }
}
