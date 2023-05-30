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
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class EquipmentActivitiesController extends Controller
{
    public function index()
    {
        return view('eqms.equipactivities.index');
    }

    public function dataTableList()
    {
        $querys = "select eq.equip_name, rd.* from roster_dtl rd
left join equipment eq on (eq.equip_id = rd.equip_id)
where rd.equip_id is not null
and rd.equip_no is not null
and rd.erd_id is not null
order by rd.INSERT_DATE desc" ;
        $queryResult = DB::select($querys);

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                if($query->diesel_issue==null){
                    $actionBtn = '<a class="btn btn-info" title="Assign Activities" href="' . route('equip-activities-edit', [$query->r_d_id]) . '"><i class="bx bx-add-to-queue cursor-pointer"></i> Assign </a>';
                    return $actionBtn;
                }else if($query->equip_meter_end!=null){
                    $actionBtn = '<a class="btn btn-success" title="Returned" href="' . route('equip-activities-edit', [$query->r_d_id]) . '"><i class="bx bxs-down-arrow cursor-pointer"></i> Return </a>';
                    return $actionBtn;
                }else{
                    $actionBtn = '<a class="btn btn-warning" title="Return" href="' . route('equip-activities-edit', [$query->r_d_id]) . '"><i class="bx bx-subdirectory-right cursor-pointer"></i> Working </a>';
                    return $actionBtn;
                }

            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $sql = "select eq.equip_name, rd.* from roster_dtl rd
left join equipment eq on (eq.equip_id = rd.equip_id)
where rd.r_d_id = :r_d_id";
        $mData = db::selectOne($sql,['r_d_id' => $id]);

        return view('eqms.equipactivities.index', [
            'mData' => $mData
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

        return redirect()->route('equip-activities-index');
    }

    private function ins_upd(Request $request)
    {
        $postData = $request->post();

        $today = date("Y-m-d");
        $equip_return_time = isset($postData['equip_return_time']) ? $today . ' ' . (date('H:i:s', strtotime($postData['equip_return_time']))) : '';

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_R_D_ID' => $postData['r_d_id'],
                'p_DIESEL_ISSUE' => $postData['diesel_issue'],
                'p_EQUIP_METER_START' => $postData['equip_meter_start'],
                'p_EQUIP_METER_END' => $postData['equip_meter_end'],
                'p_EQUIP_RETURN_TIME' => $equip_return_time,//$postData['equip_return_time'],
                'p_EXTRA_TIME' => $postData['extra_time'],
                'p_HANDLE_CONTAINER_20' => $postData['handle_container_20'],
                'p_HANDLE_CONTAINER_40' => $postData['handle_container_40'],
                'p_HANDLE_RS' => $postData['handle_rs'],
                'p_COMMENTS' => $postData['comments'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.EQUIPMENT_ACTIVITIES_UPD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
            DB::commit();

        } catch (\Exception $e) {dd($e);
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function showExtraTime($time, $equip_id)
    {
        $dateTime = date('m/d/Y').' '.$time;
        $dateOnly = date('m/d/Y');
        $sql = "SELECT m.rs_id
  FROM ROSTER_MST m, ROSTER_DTL d
 WHERE m.R_M_ID = d.R_M_ID AND d.EQUIP_ID = :equip_id";
        $data = db::selectOne($sql,['equip_id' => $equip_id]);


        $sql1 = "SELECT rs_id,
       rs_name,
       time_duration (
           in_time         =>
               TO_DATE (( :p_date || ' ' || rs_end_time),
                        'MM/DD/RRRR HH:MI PM'),
           out_time        => TO_DATE (( :p_date_time ),
                        'MM/DD/RRRR HH:MI PM'),
           p_show_format   => 'T')   AS result,
       :p_date_time,
       rs_end_time
  FROM l_roster_shift
 WHERE rs_id = :p_rs_id";
        $datas = db::selectOne($sql1,['p_rs_id' => $data->rs_id,'p_date_time' => $dateTime,'p_date' => $dateOnly]);
        $datas = json_encode($datas);

        if(!empty($datas)){
            return $datas;
        }else{
            return '';
        }

    }
}
