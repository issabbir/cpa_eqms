<?php

namespace App\Http\Controllers\Eqms\Setup;

use App\Entities\Eqms\L_Malfunction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class MalfunctionTypeController extends Controller
{
    public function index()
    {
        return view('eqms.malftypsetup.index');
    }

    public function dataTableList()
    {
        $queryResult = L_Malfunction::orderBy('insert_date','DESC')->get();
        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('malfunction-type-edit', [$query->malfunction_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = L_Malfunction::select('*')
            ->where('malfunction_id', '=', $id)
            ->first();

        return view('eqms.malftypsetup.index', [
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

        return redirect()->route('malfunction-type-entry-index');
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

        return redirect()->route('malfunction-type-entry-index');
    }

    private function ins_upd(Request $request)
    {//dd($request);
        $postData = $request->post();
        if(isset($postData['malfunction_id'])){
            $malfunction_id = $postData['malfunction_id'];
        }else{
            $malfunction_id = '';
        }

        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_MALFUNCTION_ID' => $malfunction_id,
                'p_MALFUNCTION' => $postData['malfunction'],
                'p_MALFUNCTION_BN' => $postData['malfunction_bn'],
                'p_DESCRIPTION' => $postData['description'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.EQUIPMENT_PKG.MALFUNCTION_IU', $params);

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
