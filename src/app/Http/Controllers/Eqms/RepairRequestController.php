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
use App\Entities\Eqms\ApprovalInfo;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;

class RepairRequestController extends Controller
{
    public function index()
    {
        $eqList = HelperClass::equipment_list_section_wise();

        return view('eqms.repairrequest.index', [
            'eqList' => $eqList,
            'mfList' => L_Malfunction::all(),
            'gen_uniq_id' => DB::selectOne('select eqms.YMD_SEQUENCE  as unique_id from dual')->unique_id,
        ]);
    }

    public function dataTableList()
    {
        $userSection = Employee::select('SECTION_ID', 'EMP_ID')->where('EMP_ID', '=', Auth::user()->emp_id)->first(); // So that logged in user can see only his Assigned Sections data.

        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));

        if (strpos($user_role, "SUPER_ADMIN") !== FALSE) {
            $queryResult = RepairRequestMst::orderBy('repair_request_mst.insert_date', 'desc')
                ->leftjoin('equipment', 'repair_request_mst.equip_id', '=', 'equipment.equip_id')
                ->select('repair_request_mst.*', 'equipment.workshop_name', 'Equipment.WORKSHOP_ID')
                ->get();
        } else {

            $queryResult = RepairRequestMst::orderBy('repair_request_mst.insert_date', 'desc')
                ->leftjoin('equipment', 'repair_request_mst.equip_id', '=', 'equipment.equip_id')
                ->where('Equipment.WORKSHOP_ID', '=', $userSection->section_id)
                ->select('repair_request_mst.*', 'equipment.workshop_name', 'Equipment.WORKSHOP_ID')
                ->get();

        }

        return datatables()->of($queryResult)
            ->addColumn('workshop_name', function ($query) {
                if ($query->workshop_name == null) {
                    return '--';
                } else {
                    return $query->workshop_name;
                }
            })
            ->addColumn('r_r_date', function ($query) {
                if ($query->r_r_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->r_r_date)->format('d-m-Y');
                }
            })
            ->addColumn('equip_no', function ($query) {
                return $query->equip_no . '-' . $query->equip_name;
            })
            ->addColumn('status', function ($query) {
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
                if ($query->req_status_id == 2 && $query->submit_approval == 'N') {
                    $actionBtn = '<a title="Edit" href="' . route('repair-request-edit', [$query->r_r_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                    return $actionBtn;
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
 WHERE EQUIP_ID NOT IN (SELECT EQUIP_ID FROM EQMS.SERVICE_MST)";

        $eqList = db::select($querys);

        $mData = RepairRequestMst::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->first();
        // dd( $mData);  repair-request new ta show kore na, approval layer e 3 jon dile 2 jon thaktese.

        $operator_emp_info = DB::table('pmis.employee')->select('emp_name', 'emp_id', 'emp_code')->where('emp_id', '=', $mData->emp_id)->first();;
//        dd($operator_emp_info);
        $dData = RepairRequestDtl::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->orderBy('insert_date', 'desc')
            ->get();

        return view('eqms.repairrequest.index', [
            'mData' => $mData,
            'dData' => $dData,
            'eqList' => $eqList,
            'operator_emp_info' => $operator_emp_info,
            'mfList' => L_Malfunction::all(),
        ]);
    }

    public function post(Request $request)
    {
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
    {
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
    {       //dd('ok', $request->all());
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
                'p_CPA_YN' => $postData['cpa_yn'],
                'p_OPERATOR_EMP_ID' => isset($postData['operator_emp_id']) ? $postData['operator_emp_id'] : '',
                'p_OPERATOR_NAME' => isset($postData['operator_name']) ? $postData['operator_name'] : '',
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
//             dd($params);
            DB::executeProcedure('EQMS.REPAIR_REQ_MST_INS_UPD', $params);
//             dd($params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
            if ($request->get('tab_malfunction_id')) {

                foreach ($request->get('tab_malfunction_id') as $indx => $value) {
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'p_R_R_D_ID' => $request->get('tab_r_r_d_id')[$indx],
                        "p_R_R_MST_ID" => $params['p_R_R_MST_ID']['value'],
                        "p_MALFUNCTION_ID" => $request->get('tab_malfunction_id')[$indx],
                        "p_MALFUNCTION_OTHER" => $request->get('tab_malfunction_other')[$indx],
                        "p_REPAIR_WORKSHOP_ID" => null,
                        "p_REPAIR_WORKSHOP_NAME" => null,
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];
                    //dd($params_dtl);

                    DB::executeProcedure("EQMS.REPAIR_REQ_DTL_INS_UPD", $params_dtl);
                    //dd($params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                }
            }

        } catch (\Exception $e) {
            //dd($e);
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
