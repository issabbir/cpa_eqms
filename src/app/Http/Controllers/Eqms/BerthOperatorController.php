<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\BerthOperator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class BerthOperatorController extends Controller
{
    public function index()
    {
        return view('eqms.berthoperator.index');
    }

    public function dataTableList()
    {
        $queryResult = BerthOperator::orderBy('insert_date','DESC')->get();
        return datatables()->of($queryResult)
            ->addColumn('service_start_date', function ($query) {
                if($query->service_start_date==null){
                    return '--';
                }else{
                    return Carbon::parse($query->service_start_date)->format('d-m-Y');
                }
            })
            ->addColumn('service_end_date', function ($query) {
                if($query->service_end_date==null){
                    return '--';
                }else{
                    return Carbon::parse($query->service_end_date)->format('d-m-Y');
                }
            })
            ->addColumn('active_yn', function ($query) {
                if($query->active_yn=="Y"){
                    $html = <<<HTML
<span class="badge badge-success"> Active</span>
HTML;
                    return $html;
                }else{
                    $html = <<<HTML
<span class="badge badge-danger"> In-Active</span>
HTML;
                    return $html;
                }

            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('berth-operator-edit', [$query->bo_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = BerthOperator::select('*')
            ->where('bo_id', '=', $id)
            ->first();

        return view('eqms.berthoperator.index', [
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

        return redirect()->route('berth-operator-index');
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

        return redirect()->route('berth-operator-index');
    }

    private function ins_upd(Request $request)
    {
        $postData = $request->post();
        if(isset($postData['bo_id'])){
            $bo_id = $postData['bo_id'];
        }else{
            $bo_id = '';
        }
        $service_start_date = $postData['service_start_date'];
        $service_start_date = isset($service_start_date) ? date('Y-m-d', strtotime($service_start_date)) : '';
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_BO_ID' => $bo_id,
                'p_BO_NAME' => $postData['bo_name'],
                'p_BO_NAME_BN' => $postData['bo_name_bn'],
                'p_BO_ADDRESS' => $postData['bo_address'],
                'p_BO_TEL_NO' => $postData['bo_tel_no'],
                'p_BO_MOBILE' => $postData['bo_mobile'],
                'p_BO_EMAIL' => $postData['bo_email'],
                'p_BO_CONTACT_PERSON' => $postData['bo_contact_person'],
                'p_BO_CP_DESIGNATION' => $postData['bo_cp_designation'],
                'p_CP_MOBILE' => $postData['cp_mobile'],
                'p_CP_EMAIL' => $postData['cp_email'],
                'p_SERVICE_START_DATE' => $service_start_date,
                'p_ACTIVE_YN' => $postData['active_yn'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.BERTH_OPERATOR_ENTRY', $params);

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
