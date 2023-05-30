<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\Spare_parts_stock;
use App\Entities\Pmis\Employee\Employee;
use App\Entities\Security\Menu;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class InventoryInfoController extends Controller
{
    public function index()
    {
        $userSection = Employee::select('SECTION_ID', 'EMP_ID')->where('EMP_ID', '=', Auth::user()->emp_id)->first(); // So that logged in user can filter only his Assigned Sections data.

        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));

        if (strpos($user_role, "SUPER_ADMIN") !== FALSE) {
            $querys = "SELECT eq.equip_no, eq.equip_name, eq.equip_id , idm.equip_id idm_equip_id
                        FROM  cims.item_demand_mst  idm LEFT JOIN eqms.equipment eq ON eq.equip_id = idm.equip_id
                        WHERE idm.module_id <> 0
                        AND idm.module_requisition_id IS NOT NULL
                        --AND eq.equip_id IS NOT NULL
                        AND idm.equip_id IS NOT NULL";
        } else {
            // logged in user will only see his section's equipment data.
            $querys = "select eq.equip_no, eq.equip_name, eq.equip_id , idm.equip_id idm_equip_id, eq.WORKSHOP_ID
                        from cims.item_demand_mst  idm left join eqms.equipment eq on eq.equip_id = idm.equip_id
                        where idm.module_id <> 0 and idm.module_requisition_id is not null and idm.equip_id is not null
                        and eq.WORKSHOP_ID = $userSection->section_id";
        }

        $equipList = db::select($querys);

        $query = "select rrm.r_r_no, rrm.r_r_mst_id
                    from cims.item_demand_mst  idm left join eqms.repair_request_mst rrm on rrm.r_r_mst_id = idm.module_requisition_id
                    where idm.module_id <> 0
                    and idm.module_requisition_id is not null
                    and idm.equip_id is not null";

        $repreqList = db::select($query);

        return view('eqms.inventoryinfo.index', [
            'equipList' => $equipList,
            'repreqList' => $repreqList,
        ]);
    }

    public function dataTableList(Request $request)
    {
        $r_r_mst_id = $request->get("r_r_mst_id");
        $equip_id = $request->get("equip_id");

        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));
        $userSection = Employee::select('SECTION_ID', 'EMP_ID')->where('EMP_ID', '=', Auth::user()->emp_id)->first(); // So that logged in user can see only his Assigned Sections data.

        if(strpos($user_role, "SUPER_ADMIN") !== FALSE) {

            $querys = "select idm.item_demand_mst_id,
       idm.issued_yn,
       idm.demand_no,
       idm.approved_yn,
       eq.equip_name,
       eq.workshop_name,
       eq.equip_id,
       rrm.r_r_no,
       rrm.equip_no,
       rrm.r_r_mst_id,
       rrm.ITEM_DEMAND_ID,
       idm.approval_status_id,
       idm.approved_date
  from cims.item_demand_mst  idm
       left join eqms.repair_request_mst rrm
           on rrm.r_r_mst_id = idm.module_requisition_id
       left join eqms.equipment eq on eq.equip_id = idm.equip_id
 where     idm.module_id <> 0
       and idm.module_requisition_id is not null
       and idm.equip_id is not null
       and rrm.r_r_mst_id = nvl (:r_r_mst_id, rrm.r_r_mst_id)
       and eq.equip_id = nvl (:equip_id, eq.equip_id)
        and idm.refcode = nvl (:refcode, idm.refcode)";
            $queryResult = db::select($querys, ['r_r_mst_id' => $r_r_mst_id, 'equip_id' => $equip_id, 'refcode' => 'ED']);
        }

        else{ // logged in user's section wise data filtering with $userSection->section_id

            $querys = "select idm.item_demand_mst_id,
       idm.issued_yn,
       idm.demand_no,
       idm.approved_yn,
       eq.equip_name,
       eq.workshop_name,
       eq.WORKSHOP_ID,
       eq.equip_id,
       rrm.r_r_no,
       rrm.equip_no,
       rrm.r_r_mst_id,
       rrm.ITEM_DEMAND_ID,
       idm.approval_status_id,
       idm.approved_date
  from cims.item_demand_mst  idm
       left join eqms.repair_request_mst rrm
           on rrm.r_r_mst_id = idm.module_requisition_id
       left join eqms.equipment eq on eq.equip_id = idm.equip_id
 where     idm.module_id <> 0
       and idm.module_requisition_id is not null
       and idm.equip_id is not null
       and eq.WORKSHOP_ID = $userSection->section_id
       and rrm.r_r_mst_id = nvl (:r_r_mst_id, rrm.r_r_mst_id)
       and eq.equip_id = nvl (:equip_id, eq.equip_id)
        and idm.refcode = nvl (:refcode, idm.refcode)";
            $queryResult = db::select($querys, ['r_r_mst_id' => $r_r_mst_id, 'equip_id' => $equip_id, 'refcode' => 'ED']);

        }

        return datatables()->of($queryResult)
            ->addColumn('approved_date', function ($query) {
                if ($query->approved_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->approved_date)->format('d-m-Y');
                }
            })
            ->addColumn('equip_name', function ($query) {
                return $query->equip_name . '(' . $query->equip_no . ')';
            })
            ->addColumn('workshop_name', function ($query) {
                if ($query->workshop_name == null) {
                    return '--';
                } else {
                    return $query->workshop_name;
                }
            })
            ->addColumn('approval_status', function ($query) {
                if ($query->approval_status_id == 2) {
                    $html = <<<HTML
<span class="badge badge-warning">Pending</span>
HTML;
                    return $html;
                } else if ($query->approval_status_id == 3) {
                    $html = <<<HTML
<span class="badge badge-danger">Rejected</span>
HTML;
                    return $html;
                } else if ($query->approval_status_id == 1) {
                    $html = <<<HTML
<span class="badge badge-success">Approved</span>
HTML;
                    return $html;
                }
            })
            ->addColumn('issued_yn', function ($query) {
                if ($query->issued_yn == "N") {
                    $html = <<<HTML
<span class="badge badge-warning">NOT ISSUED YET</span>
HTML;
                    return $html;
                } else {
                    $html = <<<HTML
<span class="badge badge-success">ISSUED</span>
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

                $reporturl = '<a class="btn btn-sm p-2 btn-info" role="button" target="_blank" href="' . externalLoginUrl($url, '/item-demand-issue-ems-rpt/' . $query->item_demand_id) . '">Report</a>';
                $actionBtn = '<a title="Edit" href="' . route('inventory-info-edit', [$query->item_demand_mst_id]) . '"><i class="bx bxs-show cursor-pointer"></i></a> '. $reporturl;

                return $actionBtn;
            })

//            ->addColumn('action', function ($query) {
//                $actionBtn = '<a title="Edit" href="' . route('inventory-info-edit', [$query->item_demand_mst_id]) . '"><i class="bx bxs-show cursor-pointer"></i></a>';
//                return $actionBtn;
//            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $query1 = "select idm.item_demand_mst_id,
       eq.equip_name,
       eq.workshop_name,
       rrm.r_r_no,
       rrm.equip_no,
       rrm.r_r_mst_id,
       idm.demand_no,
       case
           when idm.approval_status_id = 2 then 'PENDING'
           when idm.approval_status_id = 1 then 'APPROVED'
           when idm.approval_status_id = 3 then 'REJECTED'
       end
           as approval_status,
       case when idm.issued_yn = 'Y' then 'ISSUED' else 'NOT ISSUED YET' end
           as issued_yn,
       idm.approved_date
  from cims.item_demand_mst  idm
       left join eqms.repair_request_mst rrm
           on rrm.r_r_mst_id = idm.module_requisition_id
       left join eqms.equipment eq on eq.equip_id = idm.equip_id
 where idm.item_demand_mst_id = :item_demand_mst_id";
        $data = db::selectOne($query1, ['item_demand_mst_id' => $id]);

        $qry = "select * from APPROVAL_INFO
where REFERENCE_ID =  :REFERENCE_ID
Order by APPROVAL_REF_SEQ desc";
        $approve_date = db::selectOne($qry, ['REFERENCE_ID' => $data->r_r_mst_id]);

        $query2 = "SELECT idd.ITEM_DEMAND_DTL_ID,
       idd.ITEM_DEMAND_MST_ID,
       it.ITEM_NAME,
       it.ITEM_CODE,
       idd.ITEM_ID,
       idd.DEMAND_QTY,
       idd.APPROVED_QTY,
       idd.ISSUED_QTY
  FROM CIMS.ITEM_DEMAND_DTL  idd
       LEFT JOIN cims.L_ITEM it ON it.ITEM_ID = idd.ITEM_ID
 WHERE idd.item_demand_mst_id = :item_demand_mst_id";
        $dData = db::select($query2, ['item_demand_mst_id' => $id]);
//
//        dd($dData);
        $querys = "select eq.equip_no, eq.equip_name, eq.equip_id
  from cims.item_demand_mst  idm
       left join eqms.equipment eq on eq.equip_id = idm.equip_id
 where     idm.module_id <> 0
       and idm.module_requisition_id is not null
       and idm.equip_id is not null";
        $equipList = db::select($querys);

        $query = "select rrm.r_r_no, rrm.r_r_mst_id
  from cims.item_demand_mst  idm
       left join eqms.repair_request_mst rrm
           on rrm.r_r_mst_id = idm.module_requisition_id
 where     idm.module_id <> 0
       and idm.module_requisition_id is not null
       and idm.equip_id is not null";
        $repreqList = db::select($query);

        return view('eqms.inventoryinfo.index', [
            'data' => $data,
            'dData' => $dData,
            'equipList' => $equipList,
            'repreqList' => $repreqList,
            'approve_date' => $approve_date->approve_date,
        ]);
    }
}
