<?php

namespace App\Http\Controllers\Eqms\Setup;

use App\Entities\Admin\LGeoCountry;
use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\L_Parts;
use App\Entities\Eqms\L_WorkType;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class WorkTypeController extends Controller
{
    public function index()
    {
        return view('eqms.worktypesetup.index');
    }

    public function dataTableList()
    {
        $queryResult = L_WorkType::orderBy('insert_date','DESC')->get();
        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('work-type-edit', [$query->work_type_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = L_WorkType::select('*')
            ->where('work_type_id', '=', $id)
            ->first();

        return view('eqms.worktypesetup.index', [
            'data' => $data,
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

        return redirect()->route('work-type-index');
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

        return redirect()->route('work-type-index');
    }

    private function ins_upd(Request $request)
    {
        $postData = $request->post();
        if(isset($postData['work_type_id'])){
            $work_type_id = $postData['work_type_id'];
        }else{
            $work_type_id = '';
        }
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_WORK_TYPE_ID' => $work_type_id,
                'p_WORK_TYPE' => $postData['work_type'],
                'p_WORK_TYPE_BN' => $postData['work_type_bn'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.EQUIPMENT_PKG.WORK_TYPE_IU', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }
}
