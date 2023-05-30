<?php

namespace App\Http\Controllers\Eqms\Setup;

use App\Entities\Admin\LGeoCountry;
use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\L_Parts;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class PartsController extends Controller
{
    public function index()
    {
        return view('eqms.partssetup.index', [
            'countryList' => LGeoCountry::all(),
        ]);
    }

    public function dataTableList()
    {
        $queryResult = L_Parts::orderBy('insert_date','DESC')->get();
        return datatables()->of($queryResult)
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('parts-entry-edit', [$query->part_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = L_Parts::select('*')
            ->where('part_id', '=', $id)
            ->first();

        return view('eqms.partssetup.index', [
            'countryList' => LGeoCountry::all(),
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

        return redirect()->route('parts-entry-index');
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

        return redirect()->route('parts-entry-index');
    }

    private function ins_upd(Request $request)
    {
        $postData = $request->post();
        if(isset($postData['part_id'])){
            $part_id = $postData['part_id'];
        }else{
            $part_id = '';
        }
        $purchase_date = $postData['purchase_date'];
        $purchase_date = isset($purchase_date) ? date('Y-m-d', strtotime($purchase_date)) : '';
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_PART_ID' => $part_id,
                'p_PART_NO' => $postData['part_no'],
                'p_PART_NAME' => $postData['part_name'],
                'p_PART_BRAND' => $postData['part_brand'],
                'p_COUNTRY_ID' => $postData['origin_country_id'],
                'p_PURCHASE_DATE' => $purchase_date,
                'p_SUPPLIER' => $postData['supplier'],
                'p_VARIENT' => $postData['varient'],
                'p_PART_CATEGORY' => $postData['part_category'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.EQUIPMENT_PKG.L_PARTS_IU', $params);

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
