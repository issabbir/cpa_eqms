<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\L_Malfunction;
use App\Entities\Eqms\L_Part;
use App\Entities\Eqms\L_Parts;
use App\Entities\Eqms\L_WorkshopTeam;
use App\Entities\Eqms\RepairDiagnosisEmp;
use App\Entities\Eqms\RepairPartRequestMst;
use App\Entities\Eqms\RepairPartRequestTeam;
use App\Entities\Eqms\RepairRequestDtl;
use App\Entities\Eqms\WSDiagDtl;
use App\Entities\Eqms\WSDiagTeam;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class RepairPartRequestController extends Controller
{
    public function index()
    {
        return view('eqms.repairpartrequest.index');
    }

    public function dataTableList()
    {
        $sql = "SELECT DISTINCT
         RRM.INSERT_DATE,
         RRM.R_R_MST_ID,
         RRM.EQUIP_NAME,
         RRM.R_R_NO,
         CASE WHEN RPRM.ACTIVE_YN IS NULL THEN 'N' ELSE RPRM.ACTIVE_YN END
             STATUS,
         RPRM.R_P_REQ_TICKET_NO
    FROM REPAIR_REQUEST_MST       RRM,
         REPAIR_REQUEST_DTL       rrd,
         REPAIR_PART_REQUEST_MST  RPRM,
         EQMS.REPAIR_DIAGNOSIS_DTL rdd
   WHERE     RRM.R_R_MST_ID = rrd.R_R_MST_ID
         AND rrd.R_R_MST_ID = rdd.R_R_MST_ID
         AND RRM.R_R_MST_ID = RPRM.R_R_MST_ID(+)
         AND RDD.SEND_SERVICE_YN = 'Y'
ORDER BY 1 DESC";
        $queryResult = db::select($sql);
        return datatables()->of($queryResult)
            ->addColumn('status', function ($query) {
                if($query->status=="Y"){
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
                $actionBtn = '<a title="Edit" href="' . route('repair-part-request-edit', [$query->r_r_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit($id)
    {
        $sql = "SELECT RRRM.R_P_REQ_TICKET_NO,
       RRRM.R_P_REQ_MST_ID,
       RRRM.REQ_DATE,
       CASE WHEN RRRM.ACTIVE_YN IS NULL THEN 'N' ELSE RRRM.ACTIVE_YN END     STATUS,
       RRM.EQUIP_NAME,
       RRM.EQUIP_ID,
       RRM.R_R_NO,
       RRM.R_R_MST_ID
  FROM REPAIR_PART_REQUEST_MST RRRM, REPAIR_REQUEST_MST RRM
 WHERE RRRM.R_R_MST_ID(+) = RRM.R_R_MST_ID
 AND RRM.R_R_MST_ID = :R_R_MST_ID";
        $mData = db::selectOne($sql,['R_R_MST_ID' => $id]);

        $sql = "SELECT *
  FROM WS_DIAG_DTL
 WHERE R_P_REQ_MST_ID = :R_P_REQ_MST_ID";
        $dData = db::select($sql,['R_P_REQ_MST_ID' => $mData->r_p_req_mst_id]);

        $whom_ids = WSDiagTeam::where('r_p_req_mst_id', $mData->r_p_req_mst_id)->get(['workshop_team_id'])->pluck('workshop_team_id')->toArray();

        $sql = "select * from REPAIR_REQUEST_DTL
where R_R_MST_ID = :R_R_MST_ID";
        $diagTeamDtl = db::select($sql,['R_R_MST_ID' => $id]);

        $r_r_mst_id = $id;

        return view('eqms.repairpartrequest.index', [
            'mData' => $mData,
            'dData' => $dData,
            'teams' => L_WorkshopTeam::all(),
            'whom_ids' => $whom_ids,
            'diagTeamDtl' => $diagTeamDtl,
            'mfList' => L_Malfunction::all(),
            'r_r_mst_id' => $r_r_mst_id,
            'allPart' => L_Parts::all(),
        ]);
    }

    public function update(Request $request, $id)
    {//dd($id);
        $response = $this->ins_upd($request, $id);

        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/repair-part-request/' . $request->get('r_r_mst_id'));
    }

    private function ins_upd(Request $request)
    {//dd($request);
        $postData = $request->post();
        if (isset($postData['r_p_req_mst_id'])) {
            $r_p_req_mst_id = $postData['r_p_req_mst_id'];
        } else {
            $r_p_req_mst_id = '';
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_R_P_REQ_MST_ID' => [
                    'value' => &$r_p_req_mst_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_R_R_MST_ID' => $postData['r_r_mst_id'],
                'p_EQUIP_ID' => $postData['equip_id'],
                'p_R_P_REQ_TICKET_NO' => $postData['r_p_req_ticket_no'],
                'p_ACTIVE_YN' => $postData['status'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];//dd($params);
            DB::executeProcedure('EQMS.WORKSHOP_ACTIVITIES_MST_IU', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if ($request->get('workshop_team_id')) {
                /*$array_data = array();
                foreach ($request->get('workshop_team_id') as $indx => $value) {
                    $sql = "select R_P_R_TEAM_ID from REPAIR_PART_REQ_TEAM
where R_P_REQ_MST_ID = :R_P_REQ_MST_ID
and WORKSHOP_TEAM_ID = :WORKSHOP_TEAM_ID";
                    $item = db::selectOne($sql,['R_P_REQ_MST_ID' => $params['p_R_P_REQ_MST_ID']['value'], 'WORKSHOP_TEAM_ID' => $request->get('workshop_team_id')[$indx]]);
                    array_push($array_data,$item->r_p_r_team_id);
                }*/

                    RepairPartRequestTeam::where('r_p_req_mst_id', $params['p_R_P_REQ_MST_ID']['value'])
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
                        "p_R_P_REQ_MST_ID" => $params['p_R_P_REQ_MST_ID']['value'],
                        "p_WORKSHOP_TEAM_ID" => $request->get('workshop_team_id')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];//dd($params_dtl);

                    DB::executeProcedure("EQMS.WORKSHOP_ACTIVITIES_TEAM_IU", $params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                }
            }

            if ($request->get('tab_malfunction_id')) {
                foreach ($request->get('tab_malfunction_id') as $indx => $value) {
                    if(isset($request->get('tab_r_p_req_dtl_id')[$indx])){
                        $r_p_req_dtl_id = $request->get('tab_r_p_req_dtl_id')[$indx];
                    }else{
                        $r_p_req_dtl_id = '';
                    }
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'p_R_P_REQ_DTL_ID' => [
                            'value' => &$r_p_req_dtl_id,
                            'type' => \PDO::PARAM_INPUT_OUTPUT,
                            'length' => 255
                        ],
                        "p_R_P_REQ_MST_ID" => $params['p_R_P_REQ_MST_ID']['value'],
                        "p_DIAG_BY_ID" => $request->get('tab_diag_by_id')[$indx],
                        "p_PART_ID" => $request->get('tab_part_id')[$indx],
                        "p_REQ_QTY" => $request->get('tab_quantity')[$indx],
                        "p_REMARKS" => $request->get('tab_description')[$indx],
                        "p_MALFUNCTION_TYPE_ID" => $request->get('tab_malfunction_id')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];//dd($params_dtl);

                    DB::executeProcedure("EQMS.WORKSHOP_DIAGNOSIS_IU", $params_dtl);
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

    public function removeData(Request $request)
    {
        try {
            foreach ($request->get('r_p_req_dtl_id') as $indx => $value) {
                WSDiagDtl::where('r_p_req_dtl_id', $request->get("r_p_req_dtl_id")[$indx])->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }

    /*public function getDtlData($r_p_req_mst_id, $r_r_d_id, $r_r_mst_id)
    {
        $repair_request_dtl = DB::table("repair_request_dtl")
            ->select("r_r_d_id","r_r_mst_id","malfunction_id","malfunction","malfunction_other")
            ->orderBy("insert_date","DESC")
            ->where("r_r_d_id", "=", $r_r_d_id)
            ->first();
        $repair_part_request_dtl = DB::table("repair_part_request_dtl")
            ->select("r_p_req_dtl_id", "r_p_req_mst_id", "r_r_dtl_id", "req_date",
                "req_by_id", "req_by_emp_code", "part_id", "part_name", "part_no", "req_qty",
                "send_qty", "send_by_emp_id", "send_by_emp_code", "send_date", "rcv_by_emp_id",
                "rcv_by_emp_code", "remarks","req_by_emp_name", "send_by_emp_name","rcv_by_emp_name","rcv_date")
            ->orderBy("insert_date","DESC")
            ->where("r_r_dtl_id", "=", $r_r_d_id)
            ->first();
        $repair_part_request_mst = DB::table("repair_part_request_mst")
            ->select("r_p_req_mst_id", "r_p_req_ticket_no", "req_date", "r_r_mst_id", "equip_id", "equip_no", "active_yn")
            ->orderBy("insert_date","DESC")
            ->where("r_r_mst_id", "=", $r_r_mst_id)
            ->first();


        return  response(
            [
                'repair_request_dtl' => $repair_request_dtl,
                'repair_part_request_dtl' => $repair_part_request_dtl,
                'repair_part_request_mst' => $repair_part_request_mst,
            ]
        );
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

        return redirect('/repair-part-request/' . $request->get('dtl_r_r_mst_id'));
    }

    private function dtl_ins(Request $request)
    {//dd($request);
        $postData = $request->post();
        if (isset($postData['req_date'])) {
            $req_date = $postData['req_date'];
            $req_date = isset($req_date) ? date('Y-m-d', strtotime($req_date)) : '';
        } else {
            $req_date = '';
        }
        if (isset($postData['r_p_req_dtl_id'])) {
            $r_p_req_dtl_id = $postData['r_p_req_dtl_id'];
        } else {
            $r_p_req_dtl_id = '';
        }

        try {
            $params = [];
            if($r_p_req_dtl_id==''){
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");

                $params = [
                    'p_R_P_REQ_DTL_ID' => $r_p_req_dtl_id,
                    'p_R_P_REQ_MST_ID' => $postData['r_p_req_mst_id'],
                    'p_R_R_DTL_ID' => $postData['r_r_d_id'],
                    'p_REQ_DATE' => $req_date,
                    'p_REQ_BY_ID' => $postData['req_by_emp_code'],
                    'p_PART_ID' => $postData['part_id'],
                    'p_REQ_QTY' => $postData['req_qty'],
                    'P_INSERT_BY' => auth()->id(),
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message,
                ];
                DB::executeProcedure('EQMS.REPAIR_PART_REQ_DTL_INS_UPD', $params);//dd($params);

                if ($params['o_status_code'] != 1) {
                    DB::rollBack();
                    return $params;
                }
                return $params;
            }

            if ($request->get('send_qty')!=null) {
                $send_date = $request->get('send_date');
                $send_date = isset($send_date) ? date('Y-m-d', strtotime($send_date)) : '';
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params_dtl = [
                    "p_R_P_REQ_DTL_ID" => $r_p_req_dtl_id,
                    "p_SEND_QTY" => $request->get('send_qty'),
                    "p_SEND_DATE" => $send_date,
                    "p_APPROVE_BY" => $request->get('send_by_emp_code'),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message
                ];
                DB::executeProcedure("EQMS.REPAIR_PART_REQ_APPROVAL", $params_dtl);//dd($params_dtl);
                if ($params_dtl['o_status_code'] != 1) {
                    DB::rollBack();
                    return $params_dtl;
                }
                return $params_dtl;
            }

            if($request->get('rcv_date')!=null){
                $rcv_date = $request->get('rcv_date');
                $rcv_date = isset($rcv_date) ? date('Y-m-d', strtotime($rcv_date)) : '';
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params_dtl1 = [
                    "p_R_P_REQ_DTL_ID" => $r_p_req_dtl_id,
                    "p_RCV_BY" => $request->get('rcv_by_emp_code'),
                    "p_RCV_DATE" => $rcv_date,
                    "p_REMARKS" => $request->get('remarks'),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message
                ];
                DB::executeProcedure("EQMS.REPAIR_PART_RCV", $params_dtl1);
                if ($params_dtl1['o_status_code'] != 1) {
                    DB::rollBack();
                    return $params_dtl1;
                }
                return $params_dtl1;
            }
            return $params;
        } catch (\Exception $e) {dd($e);
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }


    }*/
}
