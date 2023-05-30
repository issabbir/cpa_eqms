<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\Spare_parts_stock;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class PartsStockController extends Controller
{
    public function index()
    {
        return view('eqms.partsstock.index');
    }

    public function dataTableList()
    {
        $queryResult = Spare_parts_stock::orderBy('insert_date','DESC')->get();
        return datatables()->of($queryResult)
            ->addColumn('purchase_date', function ($query) {
                if($query->purchase_date==null){
                    return '--';
                }else{
                    return Carbon::parse($query->purchase_date)->format('d-m-Y');
                }
            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('parts-stock-edit', [$query->s_p_stock_id]) . '"><i class="bx bxs-show cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = Spare_parts_stock::select('*')
            ->where('s_p_stock_id', '=', $id)
            ->first();

        return view('eqms.partsstock.index', [
            'data' => $data,
        ]);
    }
}
