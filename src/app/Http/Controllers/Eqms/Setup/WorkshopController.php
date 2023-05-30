<?php

namespace App\Http\Controllers\Eqms\Setup;

use App\Entities\Admin\LGeoCountry;
use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\L_Parts;
use App\Entities\Eqms\L_Workshop;
use App\Entities\Eqms\L_WorkshopType;
use App\Entities\Eqms\L_WorkType;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class WorkshopController extends Controller
{
    public function index()
    {
        return view('eqms.workshopsetup.index', [
            'workshoptyp' => L_WorkshopType::all(),
        ]);
    }

    public function dataTableList()
    {
        $sql = "select ws.*, wt.W_T_NAME from L_WORKSHOP ws
left join L_WORKSHOP_TYPE wt on wt.W_T_ID = ws.WROKSHOP_TYPE_ID
order by ws.INSERT_DATE desc";
        $queryResult = db::select($sql);

        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('workshop-edit', [$query->workshop_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = L_Workshop::select('*')
            ->where('workshop_id', '=', $id)
            ->first();

        return view('eqms.workshopsetup.index', [
            'data' => $data,
            'workshoptyp' => L_WorkshopType::all(),
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

        return redirect()->route('workshop-index');
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

        return redirect()->route('workshop-index');
    }

    private function ins_upd(Request $request)
    {
        $postData = $request->post();
        if (isset($postData['workshop_id'])) {
            $workshop_id = $postData['workshop_id'];
        } else {
            $workshop_id = '';
        }
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_WORKSHOP_ID' => $workshop_id,
                'p_WORKSHOP_NAME' => $postData['workshop_name'],
                'p_WORKSHOP_NAME_BN' => $postData['workshop_name_bn'],
                'p_WORKSHOP_ADDRESS' => $postData['workshop_address'],
                'p_WROKSHOP_TYPE_ID' => $postData['wrokshop_type_id'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.EQUIPMENT_PKG.WORKSHOP_IU', $params);

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
