<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\L_RosterShift;
use App\Entities\Eqms\L_RosterYear;
use App\Entities\Eqms\Location;
use App\Entities\Eqms\RosterDetail;
use App\Entities\Eqms\RosterMaster;
use App\Entities\Pmis\Employee\Employee;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class RosterController extends Controller
{
    public function index()
    {
        return view('eqms.dutyroster.index', [
            'yearList' => L_RosterYear::where('active_yn', 'Y')->get(),
            'locationList' => Location::all(),
            'shiftList' => L_RosterShift::all(),
        ]);
    }

    public function getEmpMechanic(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = Employee::where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(emp_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                ->orWhere('emp_code', 'like', '' . trim($searchTerm) . '%');
        })->where('dpt_department_id','18')->where('emp_status_id','1')->orderBy('emp_id', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name', 'designation_id', 'dpt_department_id']);

        return $empId;
    }

    public function getEmpTraffic(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = Employee::where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(emp_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                ->orWhere('emp_code', 'like', '' . trim($searchTerm) . '%');
        })->where('dpt_department_id','15')->where('emp_status_id','1')->orderBy('emp_id', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name', 'designation_id', 'dpt_department_id']);

        return $empId;
    }

    public function dataTableList()
    {
        $queryResult = RosterMaster::orderBy('insert_date', 'DESC')->get();

        return datatables()->of($queryResult)
            ->addColumn('r_date', function ($query) {
                if ($query->r_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->r_date)->format('d-m-Y');
                }
            })
            ->addColumn('action', function ($query) {
                /*$date_now = new DateTime();
                $date2    = new DateTime($query->r_date);
                if($date_now <= $date2){*/
                    $actionBtn = '<a title="Edit" href="' . route('duty-roster-edit', [$query->r_m_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                    return $actionBtn;
                /*}else{
                    return 'ROSTER EXPIRED';
                }*/
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $mData = RosterMaster::select('*')
            ->where('r_m_id', '=', $id)
            ->first();

        $dData = RosterDetail::select('*')
            ->where('r_m_id', '=', $id)
            ->get();

        $operatorCount = count($dData);
        $allTrainee = RosterDetail::where('r_m_id', $id)->get(['operator_id'])->pluck('operator_id')->toArray();

        return view('eqms.dutyroster.index', [
            'mData' => $mData,
            'dData' => $dData,
            'yearList' => L_RosterYear::where('active_yn', 'Y')->get(),
            'locationList' => Location::all(),
            'shiftList' => L_RosterShift::all(),
            'operatorCount' => $operatorCount,
            'allOperator' => json_encode($allTrainee),
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

        return redirect()->route('duty-roster-index');
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

        return redirect()->route('duty-roster-index');
    }

    private function ins_upd(Request $request)
    {//dd($request);
        $postData = $request->post();
        if (isset($postData['r_m_id'])) {
            $r_m_id = $postData['r_m_id'];
        } else {
            $r_m_id = '';
        }
        $r_date = $postData['r_date'];
        $r_date = isset($r_date) ? date('Y-m-d', strtotime($r_date)) : '';
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_R_M_ID' => [
                    'value' => &$r_m_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_R_NAME' => $postData['r_name'],
                'p_R_NAME_BN' => $postData['r_name_bn'],
                'p_R_DATE' => $r_date,
                //'p_R_YEAR_ID' => $postData['r_year_id'],
                'p_DESCRIPTION' => $postData['description'],
                'p_RS_ID' => $postData['rs_id'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.ROSTER_MST_ENTRY', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if ($request->get('tab_operator_id')) {

                if ($request->get('r_m_id')) {
                    RosterDetail::where('r_m_id', $r_m_id)->delete();
                }

                foreach ($request->get('tab_operator_id') as $indx => $value) {

                    $r_d_id = null;
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'p_R_D_ID' => $r_d_id,
                        "p_R_M_ID" => $params['p_R_M_ID'],
                        "p_OPERATOR_ID" => $request->get('tab_operator_id')[$indx],
                        "p_LOCATION_ID" => $request->get('tab_location_id')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];

                    DB::executeProcedure("EQMS.ROSTER_DTL_ENTRY", $params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function removeData(Request $request)
    {
        try {
            foreach ($request->get('r_d_id') as $indx => $value) {
                RosterDetail::where('r_d_id', $request->get("r_d_id")[$indx])->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }
}
