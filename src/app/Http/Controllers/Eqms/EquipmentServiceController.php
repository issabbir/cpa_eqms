<?php

namespace App\Http\Controllers\Eqms;


use App\Entities\Eqms\Equipment;
use App\Entities\Eqms\L_Service;
use App\Entities\Eqms\Service_MST;
use App\Entities\Eqms\Service_DTL;
use App\Entities\Eqms\Service_dtl_emp;
use App\Entities\Pmis\Employee\Employee;
use App\Managers\Pmis\Employee\EmployeeManager;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class EquipmentServiceController extends Controller
{
    private $employeeManager;

    public function __construct(EmployeeContract $employeeManager)
    {
        $this->employeeManager = $employeeManager;

    }

    public function index()
    {
        return view('eqms.equipservice.index', [
            'serviceList' => L_Service::all(),
            'equipmentList'=>Equipment::all(),

        ]);
    }
    public function dataTableList()
    {
        $queryResult = Service_MST::with('empInfo')->orderBy('insert_date', 'desc')->get();

        return datatables()->of($queryResult)
            ->addColumn('service_date', function ($query) {
                if ($query->service_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->service_date)->format('d-m-Y');
                }
            })
            ->addColumn('service_end_date', function ($query) {
                if ($query->service_end_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->service_end_date)->format('d-m-Y');
                }
            })
            ->addColumn('emp_name', function ($query) {
                if ($query->empInfo->emp_name == null) {
                    return '--';
                } else {
                    return $query->empInfo->emp_name;
                }
            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('equipment-service-edit', [$query->s_m_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {

        $ServiceMData = Service_MST::select('*')->with('empInfo')
            ->where('s_m_id', '=', $id)
            ->get();
        $querys = "SELECT DISTINCT
       m.*, CASE WHEN d.S_M_ID IS NOT NULL THEN 'Y' ELSE 'N' END FINDINGS
  FROM SERVICE_DTL m, SERVICE_DTL_EMP d
 WHERE m.S_D_ID = d.S_D_ID(+) AND m.s_m_id = NVL (:s_m_id, m.s_m_id)" ;
        $ServiceDData = db::select($querys,['s_m_id' => $id]);
        /*$ServiceDData = Service_DTL::select('*')
            ->where('s_m_id', '=', $id)
            ->get();*/

        return view('eqms.equipservice.index', [
            'serviceMaster' => $ServiceMData,
            'ServiceDetails' => $ServiceDData,
            'serviceList' => L_Service::all(),
            'equipmentList'=> Equipment::all(),
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

//        return redirect()->route('equipment-service-index');

        return redirect('/equipment-service/' .$response['p_S_M_ID']['value']);
    }

    private function ins_upd(Request $request)
    {

        $postData = $request->post();
        if (isset($postData['s_m_id'])) {
            $s_m_id = $postData['s_m_id'];
        } else {
            $s_m_id = '';
        }

        $service_date = $postData['service_date'];
        $service_end_date = $postData['service_end_date'];
        $service_date = isset($service_date) ? date('Y-m-d', strtotime($service_date)) : '';
        $service_end_date = isset($service_end_date) ? date('Y-m-d', strtotime($service_end_date)) : '';

        try {
            //DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_S_M_ID' => [
                    'value' => &$s_m_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_SERVICE_NO' => $postData['service_no'],
                'p_SERVICE_DATE' => $service_date,
                'p_EQUIP_ID' => $postData['eqp_id'],
                'p_OPERATOR_EMP_ID' => $postData['operator_emp'],
                'p_SERVICE_END_DATE' =>$service_end_date,
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];

            DB::executeProcedure('EQMS.SERVICE_MST_INS_UPD', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if ($request->get('tab_service_id')) {


                foreach ($request->get('tab_service_id') as $indx => $value) {
                    //DB::beginTransaction();
                    //$r_d_id = null;
                    $id =$request->get('tab_ser_id')[$indx];
                    if($id==null){
                        $id = '';
                    }
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        'p_S_D_ID' => [
                            'value' => &$id,
                            'type' => \PDO::PARAM_INPUT_OUTPUT,
                            'length' => 255
                        ],
                        "p_S_M_ID" => $params['p_S_M_ID']['value'],
                        "p_SERVICE_ID" => $request->get('tab_service_id')[$indx],
                        "p_SERVICE_END_TIME" =>isset($request->get('tab_end_date')[$indx])? date('Y-m-d',strtotime($request->get('tab_end_date')[$indx])) :'' ,
                        "p_QTY" => $request->get('tab_quantity')[$indx],
                        "p_REMARKS" => $request->get('tab_remarks')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];


                    DB::executeProcedure("EQMS.SERVICE_DTL_INS_UPD", $params_dtl);
                    //dd($params_dtl);
                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }

                }
            }

        } catch (\Exception $e) {dd($e);
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
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

        return redirect()->route('equipment-service-index');
    }
//

//
    public function dtlPost(Request $request)
    {
        $response = $this->dtl_ins($request);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/equipment-service/' . $request->get('dtl_sm_id'));


    }

    private function dtl_ins(Request $request)
    {

       try {
            if ($request->get('tab_emp_id')) {
                if ($request->get('dtl_sm_id')!='') {
                    Service_dtl_emp::where('s_m_id', $request->get('dtl_sm_id'))->where('s_d_id', $request->get('dtl_sd_id'))->delete();
                }

                foreach ($request->get('tab_emp_id') as $indx => $value) {

                    $s_d_e_id = null;


                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    $params_dtl = [
                        "p_S_D_E_ID" => $s_d_e_id,
                        "p_S_D_ID" => $request->get('dtl_sd_id'),
                        "p_S_M_ID" => $request->get('dtl_sm_id'),
                        "p_EMP_ID" => $request->get('tab_emp_id')[$indx],
                        "P_INSERT_BY" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];

                    DB::executeProcedure("EQMS.SERVICE_DTL_EMP_INS_UPD", $params_dtl);


                    if ($params_dtl['o_status_code'] != 1) {
                        DB::rollBack();
                        return $params_dtl;
                    }

                }
            }



        } catch (\Exception $e) {dd($e);
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params_dtl;
    }



    public function removeDtlData(Request $request)
    {

        try {
            foreach ($request->get('dtl_id') as $indx => $value) {
                Service_dtl_emp::where('s_d_e_id', $request->get("dtl_id")[$indx])->delete();
            }
            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }
//
    public function removeMstData(Request $request)
    {
        try {
            foreach ($request->get('esd_id') as $indx => $value) {
                Service_DTL::where('s_d_id', $request->get("esd_id")[$indx])->delete();
            }

            return '1';
        } catch (\Exception $e) {
            DB::rollBack();
            return '0';
        }

    }



    public function getEmpInfo(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = Employee::where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(emp_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                ->orWhere('emp_code', 'like', '' . trim($searchTerm) . '%');
        })->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name']);
        return $empId;
    }

    public function getEmpDetails(Request $request,$id)
    {
        $empId = DB::select('SELECT e.emp_name, L.DEPARTMENT_NAME, DG.DESIGNATION
  FROM pmis.employee e, pmis.l_department l, l_designation dg
 WHERE     E.DPT_DEPARTMENT_ID = L.DEPARTMENT_ID
       AND E.DESIGNATION_ID = DG.DESIGNATION_ID
       AND E.EMP_ID = '.$id.'') ;


        return $empId;
    }

    public function getDtlData($s_m_id, $s_d_id)
    {
        return response()->json(DB::table("service_dtl_emp")
            ->select('*')
            ->orderBy("insert_date","DESC")
            ->where("s_m_id", "=", $s_m_id)
            ->where("s_d_id", "=", $s_d_id)
            ->get());
    }
//
}
