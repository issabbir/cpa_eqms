<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\L_Malfunction;
use App\Entities\Eqms\L_Part;
use App\Entities\Eqms\L_Parts;
use App\Entities\Eqms\L_WorkshopTeam;
use App\Entities\Eqms\L_WorkshopType;
use App\Entities\Eqms\RepairDiagnosisEmp;
use App\Entities\Eqms\RepairDiagnosisMst;
use App\Entities\Eqms\RepairPartRequestMst;
use App\Entities\Eqms\RepairPartRequestTeam;
use App\Entities\Eqms\RepairRequestDtl;
use App\Entities\Eqms\RepairRequestMst;
use App\Entities\Eqms\WSDiagDtl;
use App\Entities\Eqms\WSDiagTeam;
use App\Entities\Pmis\Employee\Employee;
use App\Entities\Security\Menu;
use App\Entities\Security\Submenu;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        /*$querys = "SELECT DISTINCT rrm.r_r_mst_id,
                rrm.r_r_no,
                rrm.equip_name,
                rrm.EQUIP_NO,
                rrm.r_r_by_emp_name,
                rrm.r_r_date,
                rrm.resolve_yn,
                eq.equip_model,
                eq.WORKSHOP_NAME,
                rrm.REQ_STATUS_ID,
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
       LEFT JOIN approval_info ai ON ai.reference_id = rrm.r_r_mst_id
       LEFT JOIN equipment eq ON eq.equip_id = rrm.equip_id
       where rrm.REQ_STATUS_ID = 4 or rrm.REQ_STATUS_ID = 1
       ORDER BY rrm.INSERT_DATE DESC";*/
        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));
        $userSection = Employee::select('SECTION_ID', 'EMP_ID')->where('EMP_ID', '=', Auth::user()->emp_id)->first(); // So that logged in user can see only his Assigned Sections data.
        if (strpos($user_role, "SUPER_ADMIN") !== FALSE) {
            $querys = "SELECT DISTINCT
         rrm.r_r_mst_id,
         rrm.r_r_no,
         rrm.equip_name,
         rrm.EQUIP_NO,
         rrm.r_r_by_emp_name,
         rrm.r_r_date,
         rrm.resolve_yn,
         eq.equip_model,
         eq.WORKSHOP_NAME,
         rrm.REQ_STATUS_ID,
         rrm.ITEM_DEMAND_ID,
         rrm.INSERT_DATE,
         (SELECT COUNT (*)
            FROM repair_request_dtl
           WHERE R_R_MST_ID = rrm.R_R_MST_ID)
             AS dtl_count,
         CASE
             WHEN rrm.REQ_STATUS_ID = 1 THEN 'APPROVED'
             WHEN rrm.REQ_STATUS_ID = 2 THEN 'PENDING'
             WHEN rrm.REQ_STATUS_ID = 3 THEN 'REJECTED'
             WHEN rrm.REQ_STATUS_ID = 4 THEN 'RESOLVED'
             WHEN rrm.REQ_STATUS_ID = 5 THEN 'NOT RESOLVED'
         END
             AS STATUS,
         CASE
             WHEN ( rrm.resolve_yn='N' and  rrm.force_resolve_yn='Y' )
             THEN 'FORCEFULLY RESOLVED'

             WHEN (SELECT COUNT (*)
                     FROM repair_request_dtl
                    WHERE R_R_MST_ID = rrm.R_R_MST_ID AND RESOLVE_YN = 'Y') =
                  (SELECT COUNT (*)
                     FROM repair_request_dtl
                    WHERE R_R_MST_ID = rrm.R_R_MST_ID)
             THEN
                 'RESOLVED'
             WHEN (SELECT COUNT (*)
                     FROM repair_request_dtl
                    WHERE R_R_MST_ID = rrm.R_R_MST_ID AND RESOLVE_YN = 'N') =
                  (SELECT COUNT (*)
                     FROM repair_request_dtl
                    WHERE R_R_MST_ID = rrm.R_R_MST_ID)
             THEN
                 'NOT RESOLVED'
             ELSE
                 'PARTIAL'
         END
             AS DETAIL_STATUS,
             rrd.repair_workshop_name,
             rrd.resolve_date,
             rrd.resolve_yn resolve_yn_rrd

    FROM repair_request_mst rrm
         LEFT JOIN approval_info ai ON ai.reference_id = rrm.r_r_mst_id
         LEFT JOIN approval_info ai ON ai.reference_id = rrm.r_r_mst_id
         LEFT JOIN equipment eq ON eq.equip_id = rrm.equip_id
         LEFT JOIN repair_request_dtl rrd ON rrd.R_R_MST_ID = rrm.R_R_MST_ID
   WHERE rrm.REQ_STATUS_ID = 4 OR rrm.REQ_STATUS_ID = 1
ORDER BY rrm.INSERT_DATE DESC";
        } else {
            $querys = "SELECT DISTINCT
         rrm.r_r_mst_id,
         rrm.r_r_no,
         rrm.equip_name,
         rrm.EQUIP_NO,
         rrm.r_r_by_emp_name,
         rrm.r_r_date,
         rrm.resolve_yn,
         eq.equip_model,
         eq.WORKSHOP_NAME,
         eq.WORKSHOP_ID,
         rrm.REQ_STATUS_ID,
         rrm.ITEM_DEMAND_ID,
         rrm.INSERT_DATE,
         (SELECT COUNT (*)
            FROM repair_request_dtl
           WHERE R_R_MST_ID = rrm.R_R_MST_ID)
             AS dtl_count,
         CASE
             WHEN rrm.REQ_STATUS_ID = 1 THEN 'APPROVED'
             WHEN rrm.REQ_STATUS_ID = 2 THEN 'PENDING'
             WHEN rrm.REQ_STATUS_ID = 3 THEN 'REJECTED'
             WHEN rrm.REQ_STATUS_ID = 4 THEN 'RESOLVED'
             WHEN rrm.REQ_STATUS_ID = 5 THEN 'NOT RESOLVED'
         END
             AS STATUS,
         CASE
             WHEN ( rrm.resolve_yn='N' and  rrm.force_resolve_yn='Y' )
             THEN 'FORCEFULLY RESOLVED'

             WHEN (SELECT COUNT (*)
                     FROM repair_request_dtl
                    WHERE R_R_MST_ID = rrm.R_R_MST_ID AND RESOLVE_YN = 'Y') =
                  (SELECT COUNT (*)
                     FROM repair_request_dtl
                    WHERE R_R_MST_ID = rrm.R_R_MST_ID)
             THEN
                 'RESOLVED'
             WHEN (SELECT COUNT (*)
                     FROM repair_request_dtl
                    WHERE R_R_MST_ID = rrm.R_R_MST_ID AND RESOLVE_YN = 'N') =
                  (SELECT COUNT (*)
                     FROM repair_request_dtl
                    WHERE R_R_MST_ID = rrm.R_R_MST_ID)
             THEN
                 'NOT RESOLVED'
             ELSE
                 'PARTIAL'
         END
             AS DETAIL_STATUS,
             rrd.repair_workshop_name,
             rrd.resolve_date,
             rrd.resolve_yn resolve_yn_rrd

    FROM repair_request_mst rrm
         LEFT JOIN approval_info ai ON ai.reference_id = rrm.r_r_mst_id
         LEFT JOIN approval_info ai ON ai.reference_id = rrm.r_r_mst_id
         LEFT JOIN equipment eq ON eq.equip_id = rrm.equip_id
         LEFT JOIN repair_request_dtl rrd ON rrd.R_R_MST_ID = rrm.R_R_MST_ID
   WHERE rrm.REQ_STATUS_ID = 4 OR rrm.REQ_STATUS_ID = 1
   and eq.WORKSHOP_ID = $userSection->section_id
ORDER BY rrm.INSERT_DATE DESC";
        }

        $queryResult = db::select($querys);
        //dd($queryResult[0]);
        return datatables()->of($queryResult)
            ->addColumn('workshop_name', function ($query) {
                if ($query->workshop_name == null) {
                    return '--';
                } else {
                    return $query->workshop_name;
                }
            })
            ->addColumn('equip_name', function ($query) {
                return $query->equip_no . ' - ' . $query->equip_name;
            })
            ->addColumn('resolve_date', function ($query) {
                if ($query->resolve_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->resolve_date)->format('d-m-Y');
                }
            })
            ->addColumn('r_r_date', function ($query) {
                if ($query->r_r_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->r_r_date)->format('d-m-Y');
                }
            })
            /*->addColumn('status', function ($query) {
                if ($query->req_status_id == 4) {
                    $html = <<<HTML
<span class="badge badge-success"> Resolved</span>
HTML;
                    return $html;
                } else {
                    $html = <<<HTML
<span class="badge badge-danger"> Not Resolved</span>
HTML;
                    return $html;
                }

            })*/

            ->addColumn('detail_status', function ($query) {

                if ($query->detail_status == "FORCEFULLY RESOLVED") {
                    $html = <<<HTML
<span class="badge badge-success"> FORCEFULLY RESOLVED</span>
HTML;
                    return $html;
                } else if ($query->detail_status == "RESOLVED") {
                    $html = <<<HTML
<span class="badge badge-success"> Resolved</span>
HTML;
                    return $html;
                } else if ($query->detail_status == "NOT RESOLVED") {
                    $html = <<<HTML
<!--<span class="badge badge-danger"> Not Resolved</span>-->
<span class="badge badge-danger"> Pending</span>
HTML;
                    return $html;
                } else {
                    $html = <<<HTML
<span class="badge badge-warning"> Partial</span>
HTML;
                    return $html;
                }

            })
            ->addColumn('action', function ($query) {

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
                if ($query->item_demand_id) {
                    $reporturl = '<a class="btn btn-sm p-2 btn-info" role="button" target="_blank" href="' . externalLoginUrl($url, '/item-demand-issue-ems-rpt/' . $query->item_demand_id) . '">Report</a>';
                    $actionBtn = '<a title="Edit" href="' . route('repair-part-request-edit', [$query->r_r_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>&nbsp&nbsp&nbsp' . $reporturl;
                    return $actionBtn;
                } else {
                    $actionBtn = '<a title="Edit" href="' . route('repair-part-request-edit', [$query->r_r_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>&nbsp&nbsp&nbsp';
                    return $actionBtn;
                }
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit($id)
    {
        $mData = RepairRequestMst::select('*')
            ->where('r_r_mst_id', '=', $id)
            ->first();

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
            ->orderBy('insert_date', 'asc')
            ->get();

        return view('eqms.repairpartrequest.index', [
            'mData' => $mData,
            'dData' => $dData,
            'dData2' => $dData2,
            'eqList' => $eqList,
            'mfList' => L_Malfunction::all(),
            'rdMst' => $rdMst,
            'teams2' => L_WorkshopTeam::all(),
            'whom_ids2' => $whom_ids,
        ]);
    }

    public function update(Request $request)
    {
//        dom1
        $response = $this->ins_upd($request);

        $message = $response['o_status_message'];

        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', ' ' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/repair-part-request');
    }

    private function ins_upd(Request $request)
    {
//        dom2

        if ($request->input('submit') == "Forced Submit") {

            $resolve_date = $request->input('force_resolve_date');
            $forced_resolve_date = isset($resolve_date) ? date('Y-m-d', strtotime($resolve_date)) : '';
            try {
                RepairRequestMst::where('r_r_mst_id', $request->input('r_r_mst_id'))
                    ->update([
                        'force_resolve_date' => $forced_resolve_date,
                        'force_resolve_reason' => $request->input('force_resolve_reason'),
                        'force_resolve_yn' => $request->input('force_resolve_yn'),
                        'force_insert_by' => Auth::id()
                    ]);
            } catch (\Exception $e) {
                session()->flash('m-class', 'alert-danger');
                return redirect()->back()->with('message', 'error|' . $e->getMessage());
            }

            return ["exception" => true, "o_status_code" => 1, "o_status_message" => ' FORCEFULLY RESOLVE SUCCESSFUL'];

        } else {


            $res = RepairRequestMst::find($request->input('r_r_mst_id'));
            $issueyn = 'N';
            if ($res->force_resolve_yn == 'N' && $res->item_demand_id == null) {
                // this if block is for plain resolve without doing any item demand.

                try {
                    if ($request->get('tab_malfunction_id')) {

                        foreach ($request->get('tab_malfunction_id') as $indx => $value) {
                            $resolve_date = $request->get('training_date')[$indx];
                            $resolve_date = isset($resolve_date) ? date('Y-m-d', strtotime($resolve_date)) : '';
                            $status_code = sprintf("%4000s", "");
                            $status_message = sprintf("%4000s", "");
                            $params = [
                                'p_R_R_D_ID' => $request->get('tab_r_r_d_id')[$indx],
                                "p_R_R_MST_ID" => $request->get('r_r_mst_id'),
                                "p_RESOLVE_YN" => $request->get('resolve_yn')[$indx],
                                "p_RESOLVE_DATE" => $resolve_date,
                                "p_RESOLVE_REASON" => $request->get('force_resolve_reason'), // Force resolve column in repair_request_mst tab is also used for 'normal resolve reason' submit.
                                "p_INSERT_BY" => auth()->id(),

                                "o_status_code" => &$status_code,
                                "o_status_message" => &$status_message
                            ];

                            DB::executeProcedure("EQMS.WORKSHOP_ACTIVITIES", $params);

                            if ($params['o_status_code'] != 1) {
                                DB::rollBack();
                                return $params;
                            }

                        }

                    }

                } catch (\Exception $e) {
                    DB::rollback();
                    return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
                }
                return $params;
            } else {
                // this else block is for 'resolve with item demand'.

                if ($res->item_demand_id) {
                    $querys = "SELECT  *
                           FROM CIMS.ITEM_DEMAND_MST IDM
                           WHERE ITEM_DEMAND_MST_ID = $res->item_demand_id";

                    $inventory_issueyn = db::selectOne($querys);
                    $issueyn = $inventory_issueyn->issued_yn;


                }

                if ($issueyn == 'Y') {
                    try {
                        if ($request->get('tab_malfunction_id')) {

                            foreach ($request->get('tab_malfunction_id') as $indx => $value) {
                                $resolve_date = $request->get('training_date')[$indx];
                                $resolve_date = isset($resolve_date) ? date('Y-m-d', strtotime($resolve_date)) : '';
                                $force_resolve_reason = $request->get('force_resolve_reason');

                                $status_code = sprintf("%4000s", "");
                                $status_message = sprintf("%4000s", "");
                                $params = [
                                    'p_R_R_D_ID' => $request->get('tab_r_r_d_id')[$indx],
                                    "p_R_R_MST_ID" => $request->get('r_r_mst_id'),
                                    "p_RESOLVE_YN" => $request->get('resolve_yn')[$indx],
                                    "p_RESOLVE_DATE" => $resolve_date,
                                    "p_RESOLVE_REASON" => $force_resolve_reason,

                                    "P_INSERT_BY" => auth()->id(),
                                    "o_status_code" => &$status_code,
                                    "o_status_message" => &$status_message
                                ];


                                DB::executeProcedure("EQMS.WORKSHOP_ACTIVITIES", $params);

                                if ($params['o_status_code'] != 1) {
                                    DB::rollBack();
                                    return $params;
                                }

                            }

                        }

                    } catch (\Exception $e) {
                        DB::rollback();
//                        dd($e->getMessage());
                        return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
                    }
                    return $params;
                } else {
                    return ["exception" => true, "o_status_code" => 98, "o_status_message" => ' INVENTORY PROCESS NOT CLEARED YET. YOU CAN FORCELY RESOLVE IT'];
                }

            }
        }
    }
}

