<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\InventoryDemand;
use App\Entities\Eqms\Equipment;
use App\Entities\Pmis\Employee\Employee;
use App\Entities\Security\Menu;
use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;


class InventoryDemandController extends Controller
{

    public function index()
    {
        $eqList = HelperClass::equipment_list_section_wise();

        return view('eqms.inventory_demand.index', [
            'equipment_dropdown' => $eqList,
            'inventory_demand_data' => InventoryDemand::all(),
            'edit_data' => ['inventory_demand_id' => '', 'requisition_date' => '', 'base_price' => '', 'base_price' => '', 'data' => '', 'is_demand_yn' => ''],
            'dData' => '',
            'editMode' => 0
        ]);


    }

//------------------------------NEW DATATABLE STARTS
    public function DemandedItemsList(Request $request)
    {

        // $r_r_mst_id = $request->get("r_r_mst_id");
        //$inventory_demand_id = $request->get("inventory_demand_id");
        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));
        $userSection = Employee::select('SECTION_ID', 'EMP_ID')->where('EMP_ID', '=', Auth::user()->emp_id)->first(); // So that logged in user can see only his Assigned Sections data.
        if (strpos($user_role, "SUPER_ADMIN") !== FALSE) {
            $querys = "SELECT
       nvl(IDM.ITEM_DEMAND_MST_ID,0) AS ITEM_DEMAND_MST_ID,
       nvl(IDM.ISSUED_YN,'N') AS ISSUED_YN,
       nvl(IDM.DEMAND_NO,null) AS DEMAND_NO,
       nvl(IDM.APPROVED_YN,'N') AS APPROVED_YN,
       nvl(IDM.APPROVAL_STATUS_ID,0) AS APPROVAL_STATUS_ID,
       nvl(IDM.APPROVED_DATE,null) AS APPROVED_DATE,
       EQ.EQUIP_NAME,
       EQ.WORKSHOP_NAME,
       EQ.EQUIP_ID,
       EQ.EQUIP_NO,
       RRM.INVENTORY_DEMAND_ID,RRM.requisition_date,

       RRM.IS_DEMAND_YN,  rrm.ITEM_DEMAND_ID
  FROM EQMS.INVENTORY_DEMAND RRM
       LEFT JOIN EQMS.EQUIPMENT EQ ON EQ.EQUIP_ID = RRM.EQUIPMENT_ID
       LEFT JOIN CIMS.ITEM_DEMAND_MST IDM
          ON RRM.ITEM_DEMAND_ID = IDM.ITEM_DEMAND_MST_ID AND IDM.REFCODE = 'ID'
          order by RRM.inventory_demand_id desc";
//     and rrm.inventory_demand_id = nvl (:inventory_demand_id, rrm.inventory_demand_id)

            $queryResult = db::select($querys);
        } else {
            $querys = "SELECT
       nvl(IDM.ITEM_DEMAND_MST_ID,0) AS ITEM_DEMAND_MST_ID,
       nvl(IDM.ISSUED_YN,'N') AS ISSUED_YN,
       nvl(IDM.DEMAND_NO,null) AS DEMAND_NO,
       nvl(IDM.APPROVED_YN,'N') AS APPROVED_YN,
       nvl(IDM.APPROVAL_STATUS_ID,0) AS APPROVAL_STATUS_ID,
       nvl(IDM.APPROVED_DATE,null) AS APPROVED_DATE,
       EQ.EQUIP_NAME,
       EQ.WORKSHOP_NAME,
       EQ.EQUIP_ID,
       EQ.EQUIP_NO,
       RRM.INVENTORY_DEMAND_ID,RRM.requisition_date,

       RRM.IS_DEMAND_YN,  rrm.ITEM_DEMAND_ID
  FROM EQMS.INVENTORY_DEMAND RRM
       LEFT JOIN EQMS.EQUIPMENT EQ ON EQ.EQUIP_ID = RRM.EQUIPMENT_ID
       LEFT JOIN CIMS.ITEM_DEMAND_MST IDM ON RRM.ITEM_DEMAND_ID = IDM.ITEM_DEMAND_MST_ID AND IDM.REFCODE = 'ID'
       where eq.WORKSHOP_ID = $userSection->section_id

          order by RRM.inventory_demand_id desc";
//     and rrm.inventory_demand_id = nvl (:inventory_demand_id, rrm.inventory_demand_id)

            $queryResult = db::select($querys);
        }


        return datatables()->of($queryResult)
            ->addColumn('equip_name', function ($query) {
                if ($query->equip_name == null) {
                    return '--';
                } else {

                    return $query->equip_no . ' - ' . $query->equip_name;
                }
            })
            ->addColumn('requisition_date', function ($query) {
                if ($query->requisition_date == null) {
                    return '--';
                } else {
                    return \Carbon\Carbon::parse($query->requisition_date)->format('d-m-Y');
                }
            })
            ->addColumn('approved_date', function ($query) {
                if ($query->approved_date == null) {
                    return '--';
                } else {
                    return \Carbon\Carbon::parse($query->approved_date)->format('d-m-Y');
                }
            })
            ->addColumn('workshop_name', function ($query) {
                if ($query->workshop_name == null) {
                    return '--';
                } else {
                    return $query->workshop_name;
                }
            })
            ->addColumn('demand_no', function ($query) {
                if ($query->demand_no == null) {
                    return '--';
                } else {
                    return $query->demand_no;
                }
            })
            ->addColumn('approval_status', function ($query) {
                if ($query->approval_status_id == 1) {
                    $html = <<<HTML
<span class="badge badge-success">Approved</span>
HTML;
                    return $html;
                } else if ($query->approval_status_id == 2) {
                    $html = <<<HTML
<span class="badge badge-info">Pending</span>
HTML;
                    return $html;
                } else if ($query->approval_status_id == 3) {
                    $html = <<<HTML
<span class="badge badge-danger">Rejected</span>
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
                if ($query->item_demand_id) {
                    $reporturl = '<a class="" data-toggle="tooltip" data-placement="top" title="Click to Print" data-original-title="Click to Print" target="_blank" href="' . externalLoginUrl($url, '/item-demand-issue-ems-rpt/' . $query->item_demand_id) . '"><i class="bx bx-printer cursor-pointer"></i></a>';
                } else {
                    $reporturl = '';
                }
                $actionBtn = '<a title="Edit"  href="' . route('inventory-demand-edit', $query->inventory_demand_id) . '"><i class="bx bxs-show cursor-pointer"></i></a>&nbsp&nbsp&nbsp&nbsp' . $reporturl;
                return $actionBtn;
            })

//            ->addColumn('action', function ($query) {
//                $actionBtn = '<a title="Edit"  href="' . route('inventory-demand-edit', $query->inventory_demand_id) . '"><i class="bx bxs-show cursor-pointer"></i></a>';
//                return $actionBtn;
//            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);

    }

//------------------------------NEW DATATABLE ENDS


    public function store(Request $request)
    {
        // dd($request->post(),  date('Y-m-d', strtotime($request->requisition_date)));
        try {
            $workshop = Equipment::find($request->equipment_id, 'workshop_name');
            $equipment_no = Equipment::find($request->equipment_id, 'equip_no');
            $requisition_date = isset($request->requisition_date) ? date('Y-m-d', strtotime($request->requisition_date)) : '';
            $insert_date = date('Y-m-d', strtotime(now()));

            $res = new InventoryDemand();
            $res->equipment = Equipment::find($request->equipment_id)->equip_name;
            $res->equipment_id = $request->equipment_id;
            $res->requisition_date = $requisition_date;
            $res->workshop = $workshop->workshop_name;
            $res->description = $request->description;
            $res->equipment_no = $equipment_no->equip_no;
            $res->active_yn = 'Y';
            $res->insert_by = Auth::id();
            $res->insert_date = $insert_date;
            $res->update_by = '';
            $res->update_date = '';
            $res->save();

        } catch (\Exception $e) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $e->getMessage());
        }


        session()->flash('m-class', 'alert-success');
        session()->flash('message', 'INVENTORY DEMAND ENTRY SUCCESSFUL.');

//        $this->edit($res->inventory_demand_id);

        return redirect()->route('inventory-demand-index');

    }


    public function edit($id)
    {
        $edit_data = InventoryDemand::find($id);

        if (isset($edit_data->item_demand_id)) {
            //dd(isset($edit_data->item_demand_id),1);

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
                        WHERE idd.item_demand_mst_id = :item_demand_id";
            $dData = db::select($query2, ['item_demand_id' => $edit_data->item_demand_id]);

        } else {
            $dData = '';
        }


        $userSection = Employee::select('SECTION_ID', 'EMP_ID')->where('EMP_ID', '=', Auth::user()->emp_id)->first(); // So that logged in user can see only his Assigned Sections data.

        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));

        if (strpos($user_role, "SUPER_ADMIN") !== FALSE) {
            $querys = "SELECT * FROM EQUIPMENT WHERE EQUIP_ID NOT IN (SELECT EQUIP_ID FROM EQMS.SERVICE_MST)"; // admin gets full equipment list
        } else {
            $querys = "SELECT * FROM EQUIPMENT WHERE EQUIP_ID NOT IN (SELECT EQUIP_ID FROM EQMS.SERVICE_MST) and Equipment.WORKSHOP_ID = $userSection->section_id";
            //random user will get only his section wise equipment list
        }


        $eqList = db::select($querys);

        //dd(HelperClass::equipment_list_section_wise());


        return view('eqms.inventory_demand.index', [
            'edit_data' => InventoryDemand::find($id),
            'equipment_dropdown' => $eqList,//Equipment::orderBy('EQUIP_NAME', 'asc')->get(),
            'editMode' => 1,
            'dData' => $dData
        ]);
    }


    public function update(Request $request, $id)
    {
        try {

            $requisition_date = isset($request->requisition_date) ? date('Y-m-d', strtotime($request->requisition_date)) : '';
            $update_date = date('Y-m-d', strtotime(now()));
            $equipment_name = Equipment::find($request->equipment_id)->equip_name;
            $equipment_no = Equipment::find($request->equipment_id)->equip_no;

            InventoryDemand::where('inventory_demand_id', $id)
                ->update([
                    'equipment' => $equipment_name,
                    'equipment_id' => $request->input('equipment_id'),
                    'requisition_date' => $requisition_date,
                    'workshop' => $request->input('workshop_name'),
                    'description' => $request->input('description'),
                    'equipment_no' => $equipment_no,
                    'active_yn' => 'Y',
                    'update_by' => Auth::id(),
                    'update_date' => $update_date,
                ]);

        } catch (\Exception $e) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $e->getMessage());
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', 'INVENTORY DEMAND UPDATE SUCCESSFUL.');

        return redirect()->route('inventory-demand-index');
    }

}
