<?php

namespace App\Http\Controllers\Eqms;

use App\Entities\Admin\LGeoCountry;
use App\Entities\Eqms\Equipment;
use App\Entities\Eqms\L_Currency;
use App\Entities\Eqms\L_Equipment_Type;
use App\Entities\Eqms\L_Workshop;
use App\Entities\Eqms\L_Load_Capacity;
use App\Entities\Eqms\MediaFile;
use App\Entities\Pmis\Employee\Employee;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use PDF;
use PDO;

class EquipmentController extends Controller
{
    public function index()
    {
        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));

	//if (strpos($user_role, "SUPER_ADMIN") !== FALSE){

        if (strpos($user_role, "SUPER_ADMIN") !== FALSE || strpos($user_role, "M_ADMIN_EQMS") !== FALSE) {
            $adminYes = true;
            $lWorkshopList = DB::select("select DPT_SECTION_ID as workshop_id, dpt_section as workshop_name from pmis.l_dpt_section where department_id = 18 order by DPT_SECTION_ID asc");
        } else {
            $adminYes = false;
            $lWorkshopList = DB::table('pmis.employee')->select('employee.section_id', 'employee.emp_id', 'l_dpt_section.dpt_section_id  as workshop_id', 'l_dpt_section.dpt_section as workshop_name')
                ->leftJoin('pmis.l_dpt_section', 'employee.section_id', '=', 'l_dpt_section.dpt_section_id')
                ->where('l_dpt_section.department_id', '=', 18)
                ->where('employee.emp_id', '=', Auth::user()->emp_id)
                ->first(); // shows logged in user row's Section column.
        }

        return view('eqms.addequipment.index', [
            'countryList' => LGeoCountry::all(),
            'equipmentList' => L_Equipment_Type::all(),
            'currencyList' => L_Currency::all(),
            'lCapacityList' => L_Load_Capacity::orderBy('load_capacity_id', 'asc')->get(),
//          'lWorkshopList' => L_Workshop::orderBy('workshop_id', 'asc')->get(),
            'lWorkshopList' => $lWorkshopList, // from 23 march 2023, equipment workshop names are replaced with PMIS.L_DPT_SECTION tables  DPT_SECTION column names.
            'adminYes' => $adminYes
        ]);
    }

    public function dataTableList()
    {
	if(isset(Auth::user()->emp_id)){
		$userSection = Employee::select('SECTION_ID', 'EMP_ID')->where('EMP_ID', '=', Auth::user()->emp_id)->first(); // So that logged in user can see only his Assigned Sections data.
	}
	

        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));

        //if (strpos($user_role, "SUPER_ADMIN") !== FALSE) {
	if (strpos($user_role, "SUPER_ADMIN") !== FALSE || strpos($user_role, "M_ADMIN_EQMS") !== FALSE) {

            $queryResult = Equipment::with('capacity')->orderByRaw('insert_date DESC NULLS LAST')->get();
        } else {
            $queryResult = Equipment::with('capacity')->where('Equipment.WORKSHOP_ID', '=', $userSection->section_id)->orderByRaw('insert_date DESC NULLS LAST')->get();
        }
//dd($queryResult);
        return datatables()->of($queryResult)
            ->addColumn('operation_date', function ($query) {
                if ($query->operation_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->operation_date)->format('d-m-Y');
                }
            })
            ->addColumn('action', function ($query) {
                $actionBtn = '<a title="Edit" href="' . route('add-equipment-edit', [$query->equip_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                return $actionBtn;
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));

        //if (strpos($user_role, "SUPER_ADMIN") !== FALSE) {
	if (strpos($user_role, "SUPER_ADMIN") !== FALSE || strpos($user_role, "M_ADMIN_EQMS") !== FALSE) {

            $adminYes = true;
            $lWorkshopList = DB::select("select DPT_SECTION_ID as workshop_id, dpt_section as workshop_name from pmis.l_dpt_section where department_id = 18 order by DPT_SECTION_ID asc");
        } else {
            $adminYes = false;
            $lWorkshopList = DB::table('pmis.employee')->select('employee.section_id', 'employee.emp_id', 'l_dpt_section.dpt_section_id  as workshop_id', 'l_dpt_section.dpt_section as workshop_name')
                ->leftJoin('pmis.l_dpt_section', 'employee.section_id', '=', 'l_dpt_section.dpt_section_id')
                ->where('l_dpt_section.department_id', '=', 18)
                ->where('employee.emp_id', '=', Auth::user()->emp_id)
                ->first(); // shows logged in user row's Section column.
        }


        $data = Equipment::select('*')
            ->where('equip_id', '=', $id)
            ->first();

        $docData = MediaFile::select('*')
            ->where('ref_id', '=', $id)
            ->get();

        return view('eqms.addequipment.index', [
            'data' => $data,
            'docData' => $docData,
            'countryList' => LGeoCountry::all(),
            'equipmentList' => L_Equipment_Type::all(),
            'currencyList' => L_Currency::all(),
            'lCapacityList' => L_Load_Capacity::all(),
//          'lWorkshopList' => L_Workshop::all(),
            'lWorkshopList' => $lWorkshopList,
            'adminYes' => $adminYes
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

        return redirect()->route('add-equipment-index');
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

        return redirect()->route('add-equipment-index');
    }

    private function ins_upd(Request $request)
    {

        $postData = $request->post();
        if (isset($postData['equip_id'])) {
            $equip_id = $postData['equip_id'];
        } else {
            $equip_id = '';
        }
        $operation_date = $postData['operation_date'];
        $contract_date = $postData['contract_date'];
        $operation_date = isset($operation_date) ? date('Y-m-d', strtotime($operation_date)) : '';
        $contract_date = isset($contract_date) ? date('Y-m-d', strtotime($contract_date)) : '';
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_EQUIP_ID' => [
                    'value' => &$equip_id,
                    'type' => \PDO::PARAM_INPUT_OUTPUT,
                    'length' => 255
                ],
                'p_EQUIP_NO' => $postData['equip_no'],
                'p_EQUIP_NAME' => $postData['equip_name'],
                'p_EQUIP_SHORT_NAME' => $postData['equip_short_name'],
                'p_MACHINE_NAME' => $postData['machine_name'],
                'p_MANUFACTURER_NAME' => $postData['manufacturer_name'],
                'p_MANUFACTURER_ADDRESS' => $postData['manufacturer_address'],
                'p_SUPPLIER_NAME' => $postData['supplier_name'],
                'p_SUPPLIER_ADDRESS' => $postData['supplier_address'],
                'p_EQUIP_MODEL' => $postData['equip_model'],
                'p_EQUIP_SL_NO' => $postData['equip_sl_no'],
                'p_MANUFACTURE_YEAR' => $postData['manufacture_year'],
                'p_ORIGIN_COUNTRY_ID' => $postData['origin_country_id'],
                'p_OPERATION_DATE' => $operation_date,
                'p_CONTRACT_CURRENCY_ID' => $postData['currency_id'],
                'p_CONTRACT_VALUE' => $postData['contract_value'],
                'p_LOAD_CAPACITY_ID' => $postData['load_capacity_id'],
                'p_ENGINE_MODEL' => $postData['engine_model'],
                'p_ENGINE_SL_NO' => $postData['engine_sl_no'],
                'p_ENGINE_TYPE' => $postData['engine_type'],
                'p_BHP_RPM' => $postData['bhp_rpm'],
                'p_STROKE_NO_LENGTH' => $postData['stroke_no_length'],
                'p_BORE_MM' => $postData['bore_mm'],
                'p_COMPRESSORE_RATIO' => $postData['compressore_ratio'],
                'p_TRANS_MODEL' => $postData['trans_model'],
                'p_SPEDER_MODEL' => $postData['speder_model'],
                'p_FUEL_TANK_CAPACITY' => $postData['fuel_tank_capacity'],
                'p_MAX_LIFT_HEIGHT' => $postData['max_lift_height'],
                'p_LIFT_SPEED_LADEN' => $postData['lift_speed_laden'],
                'p_LIFT_SPEED_UNLADEN' => $postData['lift_speed_unladen'],
                'p_LOWER_SPEED_LADEN' => $postData['lower_speed_laden'],
                'p_LOWER_SPEED_UNLADEN' => $postData['lower_speed_unladen'],
                'p_EQUIP_TYPE_ID' => $postData['equip_type_id'],
                'p_CONTRACT_NO' => $postData['contract_no'],
                'p_CONTRACT_DATE' => $contract_date,
                'p_CHASSIS_NO' => $postData['chassis_no'],
                'p_ECONOMICAL_LIFE' => $postData['economical_life'],
                'p_WORKSHOP_ID' => $postData['l_workshop_id'], // from 23 march 2023 , this $postData['l_workshop_id'] is DPT_SECTION_ID from pmis.l_dpt_section.
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('EQMS.EQUIPMENT_ENTRY', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }

            if (isset($request->doc_name)) {
                foreach ($request->get("doc_name") as $indx => $value) {
                    if ($request->get("doc_id")[$indx] == null) {
                        $data = $request->get("doc")[$indx];
                        $doc = substr($data, strpos($data, ",") + 1);
                        $status_code = sprintf("%4000s", "");
                        $status_message = sprintf("%4000s", "");
                        $params1 = [
                            "P_ID" => '',
                            "P_DOC_NAME" => $request->get("doc_name")[$indx],
                            "P_DOC_TYPE" => $request->get("doc_type")[$indx],
                            "P_DOC_FILE" => ['value' => $doc, 'type' => PDO::PARAM_LOB],
                            "P_SOURCE_TABLE" => 'EQUIPMENT',
                            "P_REF_ID" => $params['p_EQUIP_ID']['value'],
                            "p_insert_by" => auth()->id(),
                            "o_status_code" => &$status_code,
                            "o_status_message" => &$status_message
                        ];
                        DB::executeProcedure("EQMS.MEDIA_FILES_CUD", $params1);
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

    public function downloader($id)
    {
        $docData = MediaFile::find($id);

        if ($docData) {
            if ($docData->files && $docData->doc_name && $docData->doc_type) {
                $content = base64_decode($docData->files);

                return response()->make($content, 200, [
                    'Content-Type' => $docData->doc_type,
                    'Content-Disposition' => 'attachment; filename="' . $docData->doc_name . '-' . $docData->ref_id . '-' . $docData->doc_id . '.' . $docData->doc_type . '"'
                ]);
            }
        }
    }

    public function removeDoc(Request $request)
    {
        if ($request->get('doc_id')) {
            DB::beginTransaction();
            $getReturn = MediaFile::where('doc_id', $request->get('doc_id'))->delete();
        }
        if ($getReturn == '1') {
            $result = 'success';
            DB::commit();
        } else {
            $result = 'failure';
            DB::rollback();
        }
        return $result;
    }
}
