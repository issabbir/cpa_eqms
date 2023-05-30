<?php

namespace App\Http\Controllers\Eqms\Setup;

use App\Entities\Eqms\L_Malfunction;
use App\Entities\Eqms\L_Workshop;
use App\Entities\Eqms\L_WorkshopTeam;
use App\Entities\Eqms\WorkshopTeamMember;
use App\Entities\Pmis\Employee\Employee;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class WSTeamSetupController extends Controller
{
    public function index()
    {
        return view('eqms.wsteammember.index', [
            'workshop'=>L_Workshop::all(),
        ]);
    }

    public function dataTableList()
    {
        $queryResult = L_WorkshopTeam::with(['workshop'])->orderBy('insert_date','DESC')->get();
        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('workshop-team-edit', [$query->workshop_team_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->addColumn('end_date', function ($query) {
                if($query->end_date==null){
                    return '--';
                }else{
                    return Carbon::parse($query->end_date)->format('d-m-Y');
                }
            })
            ->addColumn('start_date', function ($query) {
                if($query->start_date==null){
                    return '--';
                }else{
                    return Carbon::parse($query->start_date)->format('d-m-Y');
                }
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $mData = L_WorkshopTeam::select('*')
            ->where('workshop_team_id', '=', $id)
            ->first();

        $dData = WorkshopTeamMember::select('*')
            ->where('workshop_team_id', '=', $id)
            ->get();

        $operatorCount = count($dData);
        $allTrainee = WorkshopTeamMember::where('workshop_team_id', $id)->get(['member_id'])->pluck('member_id')->toArray();

        return view('eqms.wsteammember.index', [
            'mData' => $mData,
            'dData' => $dData,
            'workshop'=>L_Workshop::all(),
            'operatorCount' => $operatorCount,
            'allOperator' => json_encode($allTrainee),
        ]);
    }

    public function getEmp(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = Employee::where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(emp_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                ->orWhere('emp_code', 'like', '' . trim($searchTerm) . '%');
            //})->where('emp_status_id','13')->orWhere('emp_status_id','1')->orderBy('emp_id', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name', 'designation_id', 'dpt_department_id']);
        })->orderBy('emp_id', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name', 'designation_id', 'dpt_department_id']);

        return $empId;
    }

    public function removeData(Request $request)
    {
        try {
            foreach ($request->get('w_t_member_id') as $indx => $value) {
                WorkshopTeamMember::where('w_t_member_id', $request->get("w_t_member_id")[$indx])->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

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

        return redirect()->route('workshop-team-entry-index');
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

        return redirect()->route('workshop-team-entry-index');
    }

    private function ins_upd(Request $request)
    {//dd($request);
        $postData = $request->post();
        if(isset($postData['workshop_team_id'])){
            $workshop_team_id = $postData['workshop_team_id'];
        }else{
            $workshop_team_id = '';
        }

        $start_date = $postData['start_date'];
        $end_date = $postData['end_date'];
        $start_date = isset($start_date) ? date('Y-m-d', strtotime($start_date)) : '';
        $end_date = isset($end_date) ? date('Y-m-d', strtotime($end_date)) : '';

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_WORKSHOP_TEAM_ID' => [
                    'value' => &$workshop_team_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_WORKSHOP_ID' => $postData['workshop_id'],
                'p_TEAM_NAME' => $postData['team_name'],
                'p_START_DATE' => $start_date,
                'p_END_DATE' => $end_date,
                'p_DESCRIPTION' => $postData['description'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.L_WORKSHOP_TEAM_INS_UPD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if ($request->get('tab_member_id')) {
                foreach ($request->get('tab_member_id') as $indx => $value) {
                    if(isset($request->get('tab_w_t_member_id')[$indx])){
                        $w_t_member_id = $request->get('tab_w_t_member_id')[$indx];
                    }else{
                        $w_t_member_id = '';
                    }
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'p_W_T_MEMBER_ID' => [
                            'value' => &$w_t_member_id,
                            'type' => \PDO::PARAM_INPUT_OUTPUT,
                            'length' => 255
                        ],
                        "p_WORKSHOP_TEAM_ID" => $params['p_WORKSHOP_TEAM_ID']['value'],
                        "p_MEMBER_ID" => $request->get('tab_member_id')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];//dd($params_dtl);

                    DB::executeProcedure("EQMS.WORKSHOP_TEAM_MEMBER_INS_UPD", $params_dtl);
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
}
