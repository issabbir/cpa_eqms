<?php

namespace App\Http\Controllers\Eqms;
use App\Entities\Eqms\Equipment;
use App\Entities\Eqms\L_Equipment_Type;
use App\Entities\Eqms\L_Workshop;
use App\Entities\Eqms\L_Parts;
use App\Entities\Eqms\L_Unit;
use App\Entities\Eqms\L_Procure_Methode;
use App\Entities\Eqms\Spare_parts_request;
use App\Entities\Eqms\spare_part_req_dtl;
use App\Entities\Pmis\Employee\Employee;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class SparePartsController extends Controller
{
    public function index()
    {
        return view('eqms.sparePars.index', [
            'equip'=>Equipment::all(),
            'equipType'=>L_Equipment_Type::all(),
            'workshop'=>L_Workshop::all(),
            'parts'=>L_Parts::all(),
            'unit'=>L_Unit::all(),
            'pro_method'=>L_Procure_Methode::all(),
            'locationList' => '',
            'shiftList' => '',
        ]);
    }

    public function stockAjax (Request $request, $id){


        $stock = DB::select('SELECT stock_qty  FROM SPARE_PARTS_STOCK WHERE part_id = '.$id.'');

        return $stock;


    }

    public function dataTableList()
    {

        $queryResult = Spare_parts_request::orderBy('insert_date', 'DESC')->get();


        return datatables()->of($queryResult)
            ->addColumn('req_date', function ($query) {
                if ($query->req_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->req_date)->format('d-m-Y');
                }
            })
            ->addColumn('emp_name', function ($query) {
                if ($query->empInfo->emp_name == null) {
                    return '--';
                } else {
                    return $query->empInfo->emp_name;
                }
            })
            ->addColumn('workshop_name', function ($query) {
                if ($query->Workshop->workshop_name == null) {
                    return '--';
                } else {
                    return $query->Workshop->workshop_name;
                }
            })->addColumn('equip_type', function ($query) {
                if ($query->equipType->equip_type == null) {
                    return '--';
                } else {
                    return $query->equipType->equip_type;
                }
            })
            ->addColumn('action', function ($query) {
                /*$date_now = new DateTime();
                $date2    = new DateTime($query->r_date);
                if($date_now <= $date2){*/
                    $actionBtn = '<a title="Edit" href="' . route('spare-parts-request-edit', [$query->s_p_req_mst_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                    return $actionBtn;
                /*}else{
                    return 'ROSTER EXPIRED';
                }*/
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }
//
    public function edit(Request $request, $id)
    {
        $masterData = Spare_parts_request::select('*')->where('s_p_req_mst_id', '=', $id)->first();
//
        $detailsData = spare_part_req_dtl::select('*')->where('s_p_req_mst_id', '=', $id)->get();

//        $operatorCount = count($dData);
//        $allTrainee = RosterDetail::where('r_m_id', $id)->get(['operator_id'])->pluck('operator_id')->toArray();

        return view('eqms.sparePars.index', [
            'masterData' => $masterData,
            'detailsData' => $detailsData,
            'equip'=>Equipment::all(),
            'equipType'=>L_Equipment_Type::all(),
            'workshop'=>L_Workshop::all(),
            'parts'=>L_Parts::all(),
            'unit'=>L_Unit::all(),
            'pro_method'=>L_Procure_Methode::all(),
            'locationList' => '',
            'shiftList' => '',
        ]);
    }
//
    public function store(Request $request)
    {
        $response = $this->ins_upd($request);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('spare-parts-request');
    }
    private function ins_upd(Request $request)
    {
        $postData = $request->post();

        if (isset($postData['s_p_req_mst_id'])) {
            $s_p_req_mst_id = $postData['s_p_req_mst_id'];
        } else {
            $s_p_req_mst_id = '';
        }



        $request_date = $postData['request_date'];
        $request_date = isset($request_date) ? date('Y-m-d', strtotime($request_date)) : '';
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_S_P_REQ_MST_ID' => [
                    'value' => &$s_p_req_mst_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_ESTIMATE_NO' => $postData['estimate_no'],
                'p_EQUIP_TYPE_ID' => $postData['equip_type'],
                'p_NO_OF_EQUIP' => $postData['no_of_eqip'],
                'p_WORKSHOP_ID' => $postData['workshop_name'],
                'p_PROCURMENT_YEAR' => $postData['procuremant_year'],
                'p_REQ_DATE' => $request_date,
                'p_REQ_BY_EMP_ID' => $postData['employee_info'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.SPARE_PART_REQ_MST_INS_UPD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if ($request->get('tab_part_id')) {

//                if ($request->get('r_m_id')) {
//                    RosterDetail::where('r_m_id', $r_m_id)->delete();
//                }

                foreach ($request->get('tab_part_id') as $indx => $value) {
                    $id =$request->get('tab_spare_id')[$indx];
                    if($id==null){
                        $id = '';
                    }

                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'p_S_P_REQ_DTL_ID' => $id,
                        "p_S_P_REQ_MST_ID" => $params['p_S_P_REQ_MST_ID'],
                        "p_PART_ID" => $request->get('tab_part_id')[$indx],
                        "p_STOCK_QTY" => $request->get('tab_stock_qty')[$indx],
                        "p_REQ_QTY" => $request->get('tab_required_qty')[$indx],
                        "p_UNIT_ID" => $request->get('tab_unit_id')[$indx],
                        "p_FOREIGN_PRICE_FOB" => $request->get('tab_fob_gpb')[$indx],
                        "p_FOREIGN_PRICE_YEAR" => $request->get('tab_fob_year')[$indx],
                        "p_LAST_PURCHASE_YEAR" => $request->get('tab_last_purchase_year')[$indx],
                        "p_LAST_PURCHASE_PRICE" => $request->get('tab_last_purchase_value')[$indx],
                        "p_EST_UNIT_PRICE" => $request->get('tab_unit_price')[$indx],
                        "p_TOTAL_RATE" => $request->get('tab_total_rate')[$indx],
                        "p_LAST_PROCURE_METHODE_ID" => $request->get('tab_Pro_method_id')[$indx],
                        "p_REMARKS" => $request->get('tab_p_remarks')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];

                    DB::executeProcedure("EQMS.SPARE_PART_REQ_DTL_INS_UPD", $params_dtl);

                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                }
            }

            DB::commit();

        } catch (\Exception $e)  { dd($e);
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
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

        return redirect()->route('spare-parts-request');
    }


//
    public function removeData(Request $request)
    {

        try {
            foreach ($request->get('s_p_req_dtl_id') as $indx => $value) {
                spare_part_req_dtl::where('s_p_req_dtl_id', $request->get("s_p_req_dtl_id")[$indx])->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }
}
