<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Admin\LGeoCountry;
use App\Entities\Eqms\Equipment;
use App\Entities\Eqms\L_Currency;
use App\Entities\Eqms\L_Equipment_Type;
use App\Entities\Eqms\L_Load_Capacity;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class EquipStatusController extends Controller
{
    public function index()
    {
        return view('eqms.equipstatus.index', [
            'loadCapacty'=>L_Load_Capacity::all(),
        ]);
    }

    public function dataTableList(Request $request)
    {
        $status = $request->get("status");
        $load_capacity_id = $request->get("load_capacity_id");

        $sql = "SELECT DISTINCT
       e.equip_id,
       e.equip_no,
       e.equip_name,
       e.manufacturer_name,
       e.equip_model,
       e.equip_sl_no,
       e.manufacture_year,
       lc.load_capacity,
       r.location_id,
       CASE
           WHEN r.equip_id IS NOT NULL THEN 'WORKING'
           WHEN d.equip_id IS NOT NULL THEN 'BREAKDOWN'
           WHEN sm.equip_id IS NOT NULL THEN 'SCHEDULED SERVICE'
           ELSE 'IDLE'
       END    AS status,
       r.location,
       d.assigned_ws_id,
       d.assigned_ws_name
  FROM equipment  e
       LEFT JOIN roster_dtl r ON (e.equip_id = r.equip_id)
       LEFT JOIN repair_diagnosis_dtl d ON (e.equip_id = d.equip_id)
       LEFT JOIN eqms.l_load_capacity lc
           ON (lc.load_capacity_id = e.load_capacity_id)
       LEFT JOIN EQMS.SERVICE_MST sm ON (e.EQUIP_ID = sm.EQUIP_ID)
 WHERE     e.load_capacity_id =
           NVL ( :P_LOAD_CAPACITY_ID, e.load_capacity_id)
       AND CASE
               WHEN r.equip_id IS NOT NULL THEN 'WORKING'
               WHEN d.equip_id IS NOT NULL THEN 'BREAKDOWN'
               WHEN sm.equip_id IS NOT NULL THEN 'SCHEDULED SERVICE'
               ELSE 'IDLE'
           END =
           NVL (
               UPPER ( :P_STATUS),
               CASE
                   WHEN r.equip_id IS NOT NULL THEN 'WORKING'
                   WHEN d.equip_id IS NOT NULL THEN 'BREAKDOWN'
                   WHEN sm.equip_id IS NOT NULL THEN 'SCHEDULED SERVICE'
                   ELSE 'IDLE'
               END)";
        $queryResult = db::select($sql,['P_STATUS' => $status, 'P_LOAD_CAPACITY_ID' => $load_capacity_id]);

        return datatables()->of($queryResult)
            ->addColumn('equip_name', function ($query) {
                return $query->equip_name.' -- '.$query->equip_no;
            })
            ->addColumn('action', function ($query) {
                if($query->status=="IDLE"){
                    $html = <<<HTML
<span class="badge badge-success"> IDLE</span>
HTML;
                    return $html;
                }else if($query->status=="WORKING"){
                    $html = <<<HTML
<span class="badge badge-black"> $query->status ($query->location)</span>
HTML;
                    return $html;
                }else if($query->status=="SCHEDULED SERVICE"){
                    $html = <<<HTML
<span class="badge badge-circle-info">$query->status</span>
HTML;
                    return $html;
                }else{
                    $html = <<<HTML
<span class="badge badge-danger"> $query->status ($query->assigned_ws_name)</span>
HTML;
                    return $html;
                }

            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }
}
