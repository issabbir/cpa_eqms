<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\L_WorkshopTeam;
use App\Entities\Eqms\L_WorkshopType;
use App\Entities\Eqms\RepairDiagnosisEmp;
use App\Entities\Eqms\RepairDiagnosisMst;
use App\Entities\Eqms\RepairPartRequestTeam;
use App\Entities\Eqms\RepairRequestMst;
use App\Entities\Eqms\WSDiagTeam;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class RepairDiagnosisController extends Controller
{
    public function index()
    {
        return view('eqms.repairdiagnosis.index');
    }

    public function dataTableList()
    {
        $queryResult = RepairRequestMst::with(['equipment'])->where('REQ_STATUS_ID', 2)->orderBy('insert_date', 'desc')->get();
        return datatables()->of($queryResult)
            ->addColumn('r_r_date', function ($query) {
                if ($query->r_r_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->r_r_date)->format('d-m-Y');
                }
            })
            ->addColumn('resolve_yn', function ($query) {
                if($query->resolve_yn=="Y"){
                    $html = <<<HTML
<span class="badge badge-success"> Resolved</span>
HTML;
                    return $html;
                }else{
                    $html = <<<HTML
<span class="badge badge-danger"> Not Resolved</span>
HTML;
                    return $html;
                }

            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('repair-diagnosis-edit', [$query->r_r_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
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
        /*$dData = RepairRequestDtl::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->get();*/
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
       AND m.R_R_MST_ID = NVL ( :R_R_MST_ID, m.R_R_MST_ID)" ;
        $dData = db::select($querys,['R_R_MST_ID' => $id]);
        $rdMst = RepairDiagnosisMst::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->first();
        $whom_ids = WSDiagTeam::where('r_p_req_mst_id', $id)->get(['workshop_team_id'])->pluck('workshop_team_id')->toArray();

        return view('eqms.repairdiagnosis.index', [
            'mData' => $mData,
            'dData' => $dData,
            'rdMst' => $rdMst,
            'wtList' => L_WorkshopType::all(),
            'teams2' => L_WorkshopTeam::all(),
            'whom_ids2' => $whom_ids,
        ]);
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
        return redirect('/repair-diagnosis/' . $response['p_R_R_MST_ID']);
    }

    private function ins_upd(Request $request)
    {//dd($request->all());
        $postData = $request->post();
        if (isset($postData['r_d_id'])) {
            $r_d_id = $postData['r_d_id'];
        } else {
            $r_d_id = '';
        }
        $r_d_date = $postData['r_d_date'];
        $r_d_date = isset($r_d_date) ? date('Y-m-d', strtotime($r_d_date)) : '';
        try {
            //DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_R_D_ID' => [
                    'value' => &$r_d_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_R_D_NO' => $postData['r_d_no'],
                'p_R_R_MST_ID' => $postData['r_r_mst_id'],
                'p_R_D_DATE' => $r_d_date,
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];//dd($params);
            DB::executeProcedure('EQMS.REPAIR_DIAGNOS_MST_INS_UPD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
            //DB::commit();

            /*if ($request->get('tab_location_type_id')) {

                foreach ($request->get('tab_location_type_id') as $indx => $value) {
                    //DB::beginTransaction();
                    //$r_d_id = null;
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
                    ];//dd($params_dtl);

                    DB::executeProcedure("EQMS.EQUIP_REQUEST_MST_INS_UPD", $params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                    //DB::commit();
                }
            }*/
            if ($request->get('workshop_team_id'))
            {
                /*$array_data = array();
                foreach ($request->get('workshop_team_id') as $indx => $value) {
                    $sql = "select R_P_R_TEAM_ID from REPAIR_PART_REQ_TEAM
                  where R_P_REQ_MST_ID = :R_P_REQ_MST_ID
                    and WORKSHOP_TEAM_ID = :WORKSHOP_TEAM_ID";
                    $item = db::selectOne($sql,['R_P_REQ_MST_ID' => $params['p_R_P_REQ_MST_ID']['value'], 'WORKSHOP_TEAM_ID' => $request->get('workshop_team_id')[$indx]]);
                    array_push($array_data,$item->r_p_r_team_id);
                }*/

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


        } catch (\Exception $e) {//dd($e);
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

        return redirect('/repair-diagnosis/' . $request->get('r_r_mst_id'));
    }

    private function dtl_ins(Request $request)
    {//dd($request);
        $postData = $request->post();
        if (isset($postData['r_d_dtl_id'])) {
            $r_d_dtl_id = $postData['r_d_dtl_id'];
        } else {
            $r_d_dtl_id = '';
        }
        if (isset($postData['malfunction_resolve_date'])) {
            $malfunction_resolve_date = $postData['malfunction_resolve_date'];
            $malfunction_resolve_date = isset($malfunction_resolve_date) ? date('Y-m-d', strtotime($malfunction_resolve_date)) : '';
        } else {
            $malfunction_resolve_date = '';
        }
        if (isset($postData['w_t_id'])) {
            $w_t_id = $postData['w_t_id'];
        } else {
            $w_t_id = '';
        }
        if (isset($postData['workshop_id'])) {
            $workshop_id = $postData['workshop_id'];
        } else {
            $workshop_id = '';
        }
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_R_D_DTL_ID' => [
                    'value' => &$r_d_dtl_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_R_D_MST_ID' => $postData['r_d_mst_id'],
                'p_R_R_MST_ID' => $postData['r_r_mst_id'],
                'p_R_R_DTL_ID' => $postData['r_r_d_id'],
                'p_MALFUNCTION_ID' => $postData['malfunction_id'],
                'p_MALFUNCTION_RESOLVE_YN' => $postData['malfunction_resolve_yn'],
                'p_MALFUNCTION_RESOLVE_DATE' => $malfunction_resolve_date,
                'p_SEND_SERVICE_YN' => isset($postData['send_service_yn']) ? $postData['send_service_yn'] : 'N',
                'p_ASSIGNED_WS_ID' => $workshop_id,
                'p_ASSIGNED_WS_TYPE_ID' => $w_t_id,
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];//dd($params);
            DB::executeProcedure('EQMS.REPAIR_DIAGNOS_DTL_INS_UPD', $params);//dd($params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if($postData['malfunction_resolve_yn']=='N'){
                if ($r_d_dtl_id!='') {
                    RepairDiagnosisEmp::where('r_d_dtl_id', $r_d_dtl_id)->delete();
                }
            }else{
                if ($request->get('tab_emp_id')) {
                    if ($r_d_dtl_id!='') {
                        RepairDiagnosisEmp::where('r_d_dtl_id', $r_d_dtl_id)->delete();
                    }
                    foreach ($request->get('tab_emp_id') as $indx => $value) {
                        $r_d_e_id = null;
                        $status_code = sprintf("%4000s", "");
                        $status_message = sprintf("%4000s", "");
                        $params_dtl = [
                            "p_R_D_E_ID" => $r_d_e_id,
                            "p_R_D_DTL_ID" => $params['p_R_D_DTL_ID']['value'],
                            "p_R_D_MST_ID" => $postData['r_d_mst_id'],
                            "p_EMP_ID" => $request->get('tab_emp_id')[$indx],
                            "P_INSERT_BY" => auth()->id(),
                            "o_status_code" => &$status_code,
                            "o_status_message" => &$status_message
                        ];//dd($params_dtl);

                        DB::executeProcedure("EQMS.REPAIR_DIAGNOS_EMP_INS_UPD", $params_dtl);
                        if ($params_dtl['o_status_code'] != 1) {
                            DB::rollBack();
                            return $params_dtl;
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

    public function getDtlData($r_r_mst_id, $r_r_d_id)
    {
        $repair_diagnosis_emp = [];
        $repair_diagnosis_mst = DB::table("repair_diagnosis_mst")
            ->select("r_d_id", "r_d_no", "r_d_date", "r_r_mst_id")
            ->orderBy("insert_date","DESC")
            ->where("r_r_mst_id", "=", $r_r_mst_id)
            ->first();
        $repair_diagnosis_dtl = DB::table("repair_diagnosis_dtl")
            ->select("r_d_dtl_id", "r_d_mst_id", "r_r_mst_id", "r_r_dtl_id", "equip_id", "equip_no", "r_r_date", "malfunction_id", "malfunction", "malfunction_resolve_yn", "malfunction_resolve_date", "send_service_yn", "assigned_ws_id", "assigned_ws_name", "assigned_ws_type_id", "assigned_ws_type")
            ->orderBy("insert_date","DESC")
            ->where("r_r_mst_id", "=", $r_r_mst_id)
            ->where("r_r_dtl_id", "=", $r_r_d_id)
            ->first();
        if($repair_diagnosis_dtl!=null){
            $repair_diagnosis_emp = DB::table("repair_diagnosis_emp")
                ->select("r_d_e_id", "r_d_dtl_id", "r_d_mst_id", "emp_id", "emp_code", "emp_name")
                ->orderBy("insert_date","DESC")
                ->where("r_d_mst_id", "=", $repair_diagnosis_dtl->r_d_mst_id)
                ->where("r_d_dtl_id", "=", $repair_diagnosis_dtl->r_d_dtl_id)
                ->get();
        }
        $repair_request_dtl = DB::table("repair_request_dtl")
            ->select("r_r_d_id","r_r_mst_id","malfunction_id","malfunction","malfunction_other")
            ->orderBy("insert_date","DESC")
            ->where("r_r_mst_id", "=", $r_r_mst_id)
            ->where("r_r_d_id", "=", $r_r_d_id)
            ->first();

        return  response(
            [
                'repair_request_dtl' => $repair_request_dtl,
                'repair_diagnosis_dtl' => $repair_diagnosis_dtl,
                'repair_diagnosis_mst' => $repair_diagnosis_mst,
                'repair_diagnosis_emp' => $repair_diagnosis_emp,
            ]
        );
    }

    public function removeEmpData(Request $request)
    {
        try {
            foreach ($request->get('r_d_e_id') as $indx => $value) {
                RepairDiagnosisEmp::where('r_d_e_id', $request->get("r_d_e_id")[$indx])->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }

    function getWorkshop(Request $request)
    {
        $w_t_id = $request->input('w_t_id');
        $list = DB::table('L_WORKSHOP')->where('wrokshop_type_id', '=', $w_t_id)->get();

        $msg = '<option value="">Select One</option>';
        foreach ($list as $data){
            $msg .= '<option value="'.$data->workshop_id.'">'.$data->workshop_name.'</option>';
        }
        return $msg;
    }

    function getWorkshopDb(Request $request)
    {
        $w_t_id = $request->input('w_t_id');
        $selection_id = $request->input('selection_id');
        $list = DB::table('L_WORKSHOP')->where('wrokshop_type_id', '=', $w_t_id)->get();

        $msg = '<option value="">Select One</option>';
        foreach ($list as $data){
            $msg .= '<option value="'.$data->workshop_id.'" ' . ($data->workshop_id == $selection_id ? ' selected' : "") . '>'.$data->workshop_name.'</option>';
        }
        return $msg;
    }
}
