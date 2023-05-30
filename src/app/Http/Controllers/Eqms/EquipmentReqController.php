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
use App\Entities\Eqms\Workflow;
use App\Entities\Eqms\WorkflowTeam;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;

class EquipmentReqController extends Controller
{
    public function index()
    {
        return view('eqms.equiprequest.index', [
            'reqList' => L_EquipmentRequester::all(),
            'boList' => BerthOperator::where('active_yn', 'Y')->get(),
            'wtList' => L_WorkType::all(),
            'reqstList' => L_RequestStatus::all(),
            'locationList' => Location::all(),
            'loctypList' => L_LocationType::all(),
            'eqptypList' => L_Equipment_Type::all(),
            'ldcpctList' => L_Load_Capacity::all(),
            'gen_uniq_id' => DB::selectOne('select gen_unique_id  as unique_id from dual')->unique_id,
        ]);
    }

    public function dataTableList()
    {
        $queryResult = EquipmentRequest::orderBy('insert_date', 'desc')->get();
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
            ->addColumn('action', function ($query) {
                if ($query->req_status_id == 1 && $query->submit_approval == 'N') {
                    $actionBtn = '<a title="Edit" href="' . route('equipment-request-edit', [$query->eqr_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
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

    public function edit(Request $request, $id)
    {
        $mData = EquipmentRequest::select('*')
            ->where('eqr_id', '=', $id)
            ->first();

        $querys = "SELECT DISTINCT
       m.*, CASE WHEN d.EQR_ID IS NOT NULL THEN 'Y' ELSE 'N' END FINDINGS
  FROM EQUIP_REQUEST_MST m, EQMS.EQUIP_REQUEST_DTL d
 WHERE m.ERM_ID = d.ERM_ID(+) AND m.eqr_id = NVL (:eqr_id, m.eqr_id)";
        $mmData = db::select($querys, ['eqr_id' => $id]);

        $mdData = EquipmentRequestDtl::select('*')
            ->where('eqr_id', '=', $id)
            ->get();

        $pending = 0;
        foreach ($mmData as $indx => $value) {
            if ($mmData[$indx]->findings == 'N') {
                $pending++;
            }
        }

        return view('eqms.equiprequest.index', [
            'mData' => $mData,
            'mmData' => $mmData,
            'mdData' => $mdData,
            'pending' => $pending,
            'reqList' => L_EquipmentRequester::all(),
            'boList' => BerthOperator::where('active_yn', 'Y')->get(),
            'wtList' => L_WorkType::all(),
            'reqstList' => L_RequestStatus::all(),
            'locationList' => Location::all(),
            'loctypList' => L_LocationType::all(),
            'eqptypList' => L_Equipment_Type::all(),
            'ldcpctList' => L_Load_Capacity::all(),
        ]);
    }

    public function post(Request $request)
    {
        $response = $this->ins_upd($request, '');
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);
        return redirect('/equipment-request/' . $response['p_EQR_ID']['value']);
        //return redirect()->route('equipment-request-index');
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

        return redirect()->route('equipment-request-index');
    }

    private function ins_upd(Request $request, $id)
    {//dd($request);
        $postData = $request->post();
        if (isset($postData['eqr_id'])) {
            $eqr_id = $postData['eqr_id'];
        } else {
            $eqr_id = '';
        }
        $req_date = $postData['req_date'];
        $req_for_date = $postData['req_for_date'];
        //$supply_date = $postData['supply_date'];
        $req_date = isset($req_date) ? date('Y-m-d', strtotime($req_date)) : '';
        $req_for_date = isset($req_for_date) ? date('Y-m-d', strtotime($req_for_date)) : '';
        //$supply_date = isset($supply_date) ? date('Y-m-d', strtotime($supply_date)) : '';
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_EQR_ID' => [
                    'value' => &$eqr_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_REQUESTER_ID' => $postData['requester_id'],
                'p_BO_ID' => isset($postData['bo_id']) ? $postData['bo_id'] : '',
                'p_REQ_EMP_ID' => isset($postData['req_emp_id']) ? $postData['req_emp_id'] : '',
                'p_REQ_DATE' => $req_date,
                'p_REQ_FOR_DATE' => $req_for_date,
                'p_REQ_WORK_ID' => $postData['req_work_id'],
                'p_SHIP_NAME' => isset($postData['ship_name']) ? $postData['ship_name'] : '',
                'p_A_P_NO' => $postData['a_p_no'],
                //'p_REQ_STATUS_ID' => $postData['req_status_id'],
                //'p_SUPPLY_DATE' => $supply_date,
                //'p_NOTHI_NO' => $postData['nothi_no'],
                //'p_NOTHI_NO_BN' => $postData['nothi_no_bn'],
                'p_equip_req_no' => $postData['equip_req_no'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];//dd($params);
            DB::executeProcedure('EQMS.EQUIP_REQUEST_INS_UPD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
            DB::commit();

            if ($request->get('tab_location_type_id')) {
                DB::beginTransaction();
                foreach ($request->get('tab_location_type_id') as $indx => $value) {

                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'p_ERM_ID' => [
                            'value' => &$request->get('tab_erm_id')[$indx],
                            'type' => \PDO::PARAM_INPUT_OUTPUT,
                            'length' => 255
                        ],
                        "p_EQR_ID" => $params['p_EQR_ID']['value'],
                        "p_LOCATION_TYPE_ID" => $request->get('tab_location_type_id')[$indx],
                        "p_LOCATION_ID" => $request->get('tab_location_id')[$indx],
                        "p_CONTAINER_20" => $request->get('tab_container_20')[$indx],
                        "p_CONTAINER_40" => $request->get('tab_container_40')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];

                    DB::executeProcedure("EQMS.EQUIP_REQUEST_MST_INS_UPD", $params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                }
                DB::commit();
            }

            if ($eqr_id != null && isset($postData['update']) && $postData['update'] == '1') {
                $data = Workflow::where('reference_id', $eqr_id)->delete();

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
                            'p_reference_id' => $eqr_id,
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
                $update = EquipmentRequest::where('eqr_id', $eqr_id)->update(['submit_approval' => 'Y']);
                $datas = Workflow::where('reference_id', $eqr_id)->orderBy('workflow_recipient_id','asc')->get();
                if (count($datas) > 0) {
                    foreach ($datas as $indx => $value) {
                        $current = 'N';
                        if ($datas[0]) {
                            $current = 'Y';
                        }

                        $ins = DB::table('APPROVAL_INFO')->insert(
                            ['APPROVAL_INFO_ID' => DB::selectOne('select gen_unique_id  as unique_id from dual')->unique_id, 'APPROVAL_REF_SEQ' => $datas[$indx]['approval_seq_no'],
                                'APPROVAL_SEQ_NUMBER' => '', 'APPROVAL_STATUS_ID' => 2, 'DEPARTMENT_HEAD_YN' => $datas[$indx]['department_head_yn'],
                                'EDIT_PERMISSION_YN' => $datas[$indx]['edit_permission_yn'], 'EMP_CODE' => $datas[$indx]['emp_code'],
                                'EMP_NAME' => $datas[$indx]['emp_name'], 'RECIPIENT_DEPT_ID' => $datas[$indx]['department_id'],
                                'RECIPIENT_EMP_ID' => $datas[$indx]['employee_id'], 'REFERENCE_ID' => $eqr_id,
                                'RULE_ID' => 1, 'STEP_TITLE_ID' => $datas[$indx]['recipient_designation_id'],
                                'WORKFLOW_RECIPIENT_ID' => $datas[$indx]['workflow_recipient_id'], 'CURRENT_YN' => $current]
                        );
                    }
                } else {
                    $data = Workflow::where('reference_id', $eqr_id)->delete();
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
                            'p_reference_id' => $eqr_id,
                            'p_employee_code' => $teamInfo->emp_code,
                            'p_employee_name' => $teamInfo->employee->emp_name,
                            'p_insert_by' => auth()->id(),
                            'o_status_code' => &$status_code,
                            'o_status_message' => &$status_message,
                        ];
                        // print_r($params);

                        DB::executeProcedure("workflow_recipient_save", $params2);//dd($params);
                    }
                    $datas = Workflow::where('reference_id', $eqr_id)->orderBy('workflow_recipient_id','asc')->get();
                    if (count($datas) > 0) {
                        foreach ($datas as $indx => $value) {
                            $current = 'N';
                            if ($datas[$indx]== $datas[0]) {
                                $current = 'Y';
                            }
                            $ins = DB::table('APPROVAL_INFO')->insert(
                                ['APPROVAL_INFO_ID' => DB::selectOne('select gen_unique_id  as unique_id from dual')->unique_id, 'APPROVAL_REF_SEQ' => $datas[$indx]['approval_seq_no'],
                                    'APPROVAL_SEQ_NUMBER' => '', 'APPROVAL_STATUS_ID' => 2, 'DEPARTMENT_HEAD_YN' => $datas[$indx]['department_head_yn'],
                                    'EDIT_PERMISSION_YN' => $datas[$indx]['edit_permission_yn'], 'EMP_CODE' => $datas[$indx]['emp_code'],
                                    'EMP_NAME' => $datas[$indx]['emp_name'], 'RECIPIENT_DEPT_ID' => $datas[$indx]['department_id'],
                                    'RECIPIENT_EMP_ID' => $datas[$indx]['employee_id'], 'REFERENCE_ID' => $eqr_id,
                                    'RULE_ID' => 1, 'STEP_TITLE_ID' => $datas[$indx]['recipient_designation_id'],
                                    'WORKFLOW_RECIPIENT_ID' => $datas[$indx]['workflow_recipient_id'], 'CURRENT_YN' => $current]
                            );
                        }
                    }
                }
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
//dd($request);
        return redirect('/equipment-request/' . $request->get('dtl_eqr_id'));

        //return redirect()->route('equipment-request-index');
    }

    private function dtl_ins(Request $request)
    {//dd($request);
        try {
            if ($request->get('tab_equip_type_id')) {
                if ($request->get('dtl_erm_id') != '') {
                    EquipmentRequestDtl::where('eqr_id', $request->get('dtl_eqr_id'))->where('erm_id', $request->get('dtl_erm_id'))->delete();
                }

                foreach ($request->get('tab_equip_type_id') as $indx => $value) {
                    //DB::beginTransaction();
                    $r_d_id = null;
                    $load = $request->get('tab_load_capacity_id')[$indx];
                    if ($load == 'null') {
                        $load = null;
                    }
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        "p_ERD_ID" => $r_d_id,
                        "p_ERM_ID" => $request->get('dtl_erm_id'),
                        "p_EQR_ID" => $request->get('dtl_eqr_id'),
                        "p_EQUIP_TYPE_ID" => $request->get('tab_equip_type_id')[$indx],
                        "p_LOAD_CAPACITY_ID" => $load,
                        "p_REQUESTED_EQUIP" => $request->get('tab_requested_equip')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];//dd($params_dtl);

                    DB::executeProcedure("EQMS.EQUIP_REQUEST_DTL_INS_UPD", $params_dtl);
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
        return response()->json(DB::table("equip_request_dtl")
            ->select("erd_id", "erm_id", "eqr_id", "equip_type", "load_capacity", "requested_equip", "equip_type_id", "load_capacity_id", "supplied_equip", "supplied_date")
            ->orderBy("insert_date", "DESC")
            ->where("eqr_id", "=", $eqr_id)
            ->where("erm_id", "=", $erm_id)
            ->get());
    }

    public function removeDtlData(Request $request)
    {
        try {
            foreach ($request->get('dtl_id') as $indx => $value) {
                EquipmentRequestDtl::where('erd_id', $request->get("dtl_id")[$indx])->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }

    public function removeMstData(Request $request)
    {//dd($request->all());
        try {
            foreach ($request->get('erm_id') as $indx => $value) {
                EquipmentRequestDtl::where('erm_id', $request->get("erm_id")[$indx])->where('eqr_id', $request->get("eqr_id")[$indx])->delete();
            }
            foreach ($request->get('erm_id') as $indx => $value) {
                EquipmentRequestMst::where('erm_id', $request->get("erm_id")[$indx])->where('eqr_id', $request->get("eqr_id")[$indx])->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }

    function getLocation(Request $request)
    {
        $location_type_id = $request->input('location_type_id');
        $list = DB::table('LOCATION')->where('location_type_id', '=', $location_type_id)->get();

        $msg = '<option value="">Select One</option>';
        foreach ($list as $data) {
            $msg .= '<option value="' . $data->location_id . '">' . $data->location . '</option>';
        }
        return $msg;
    }
}
