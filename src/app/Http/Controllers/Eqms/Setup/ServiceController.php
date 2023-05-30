<?php

namespace App\Http\Controllers\Eqms\Setup;

use App\Entities\Admin\LGeoCountry;
use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\L_Parts;
use App\Entities\Eqms\L_Service;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ServiceController extends Controller
{
    public function index()
    {
        return view('eqms.servicesetup.index');
    }

    public function dataTableList()
    {
        $queryResult = L_Service::orderBy('insert_date','DESC')->get();
        return datatables()->of($queryResult)
            ->addColumn('active_yn', function ($query) {
                if($query->active_yn=="Y"){
                    $html = <<<HTML
<span class="badge badge-success"> Active</span>
HTML;
                    return $html;
                }else{
                    $html = <<<HTML
<span class="badge badge-danger"> In Active</span>
HTML;
                    return $html;
                }

            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('service-entry-edit', [$query->service_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = L_Service::select('*')
            ->where('service_id', '=', $id)
            ->first();

        return view('eqms.servicesetup.index', [
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

        return redirect()->route('service-entry-index');
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

        return redirect()->route('service-entry-index');
    }

    private function ins_upd(Request $request)
    {//dd($request);
        $postData = $request->post();
        if(isset($postData['service_id'])){
            $service_id = $postData['service_id'];
        }else{
            $service_id = '';
        }
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_SERVICE_ID' => $service_id,
                'p_SERVICE' => $postData['service'],
                'p_SERVICE_BN' => $postData['service_bn'],
                'p_ACTIVE_YN' => $postData['active_yn'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.EQUIPMENT_PKG.L_SERVICE_IU', $params);

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
