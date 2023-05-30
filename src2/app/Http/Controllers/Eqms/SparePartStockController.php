<?php

namespace App\Http\Controllers\Eqms;
use App\Entities\Eqms\Equipment;
use App\Entities\Eqms\L_Equipment_Type;
use App\Entities\Eqms\L_Workshop;
use App\Entities\Eqms\L_Parts;
use App\Entities\Eqms\L_Unit;
use App\Entities\Eqms\L_Procure_Methode;
use App\Entities\Eqms\Spare_parts_request;
use App\Entities\Eqms\Spare_parts_stock;
use App\Entities\Pmis\Employee\Employee;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class SparePartStockController extends Controller
{
    public function index()
    {
        return view('eqms.spareParsStock.index', [
            'estimateNo'=>Spare_parts_request::all()->where('active_yn', 'Y'),
        ]);
    }

    public function estimateParts(Request $request, $id)
    {
        $querys = "SELECT P.PART_ID ,P.PART_NO, P.PART_NAME
  FROM SPARE_PART_REQ_MST m, SPARE_PART_REQ_DTL d, l_part p
 WHERE     D.PART_ID = p.part_id
 AND D.ACTIVE_YN = 'Y'
        AND M.S_P_REQ_MST_ID = D.S_P_REQ_MST_ID
        AND D.S_P_REQ_MST_ID  = :S_P_REQ_MST_ID" ;
        $parts = db::select($querys,['S_P_REQ_MST_ID' => $id]);

        $partdata = '';
        if (!empty($parts)) {
            $partdata .= '<option value="">--- Choose ---</option>';
            foreach ($parts as $data) {
                $partdata .= '<option value="' . $data->part_id . '">'.$data->part_no.'-' . $data->part_name . '</option>';
            }
            echo $partdata;
            die;
        } else {
            echo '<option value="">--- Choose ---</option>';
        }

    }

    public function requestStock(Request $request,$estimate_no,$partId){

        $stockData = DB::select('SELECT S.STOCK_QTY, D.REQ_QTY ,M.S_P_REQ_MST_ID
  FROM SPARE_PART_REQ_DTL d, SPARE_PART_REQ_MST M, SPARE_PARTS_STOCK s
  where D.S_P_REQ_MST_ID = M.S_P_REQ_MST_ID(+)
  and D.PART_ID = S.PART_ID(+)
    and M.S_P_REQ_MST_ID = '.$estimate_no.'
    and D.PART_ID = '.$partId.'');
        return $stockData;
    }

    public function dataTableList()
    {

        $queryResult = Spare_parts_stock::orderBy('insert_date', 'DESC')->get();


        return datatables()->of($queryResult)
            ->addColumn('parts_no', function ($query) {
                if ($query->parts->part_no == null) {
                    return '--';
                } else {
                    return $query->parts->part_no;
                }
            })

            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }
//

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

        return redirect()->route('spare-part-request-index');
    }
    private function ins_upd(Request $request)
    {
//dd($request);
        try {
            //DB::beginTransaction();
            if ($request->get('tab_estimate_no')) {

                foreach ($request->get('tab_parts_id') as $indx => $value) {
                    /*$id =$request->get('tab_spare_id')[$indx];
                    if($id==null){*/
                        $id = '';
                    //}

                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params = [
                        'p_S_P_STOCK_ID' => $id,
                        "p_S_P_REQ_MST_ID" => $request->get('tab_estimate_no')[$indx],
                        "p_PART_ID" => $request->get('tab_parts_id')[$indx],
                        "p_STOCK_QTY" => $request->get('tab_receiveQty')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];//dd($params);

                    DB::executeProcedure("EQMS.SPARE_PARTS_STOCK_IU", $params);

                    if ($params['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params;
                    }
                }
            }

            //DB::commit();

        } catch (\Exception $e)  { dd($e);
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }



}
