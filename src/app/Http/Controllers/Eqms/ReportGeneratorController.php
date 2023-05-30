<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 4/20/20
 * Time: 04:54 AM
 */

namespace App\Http\Controllers\Eqms;

use App\Entities\Eqms\Spare_parts_request;
use App\Entities\Security\Report;
use App\Entities\Eqms\Equipment;
use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\EquipmentNothiNumber;
use App\Entities\Eqms\L_EquipmentRequester;
use App\Entities\Eqms\L_WorkshopType;
use App\Entities\Eqms\L_RosterShift;
use App\Entities\Eqms\L_Equipment_Type;
use App\Entities\Eqms\Location;
use App\Entities\Eqms\Service_MST;
use App\Enums\ModuleInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Security\HasPermission;
use Illuminate\Support\Facades\DB;


class ReportGeneratorController extends Controller
{
    use HasPermission;


    /**
     * ReportGeneratorController constructor.
     * @param
     */

    public function index(Request $request)
    {
        $module = ModuleInfo::MODULE_ID;
        $reportObject = new Report();

        if (auth()->user()->hasGrantAll()) {
            $reports = $reportObject->where('module_id', $module)->orderBy('report_name', 'ASC')->get();
//            print_r($reports);die;
        } else {
            $roles = auth()->user()->getRoles();
            $reports = array();
            foreach ($roles as $role) {
                if (count($role->reports)) {
                    $rpts = $role->reports->where('module_id', $module);
                    foreach ($rpts as $report) {
                        $reports[$report->report_id] = $report;
                    }
                }
            }
        }

        return view('eqms.reportgenerator.index', compact('reports'));
    }

    public function reportParams(Request $request, $id)
    {
        $report = Report::find($id);
        $nothiNumber = DB::select('SELECT DISTINCT nothi_no  FROM EQUIP_SUPPLY_NOTHI');
        $equipment = Equipment::all();
        $berthoperator = BerthOperator::all();
        $requester = L_EquipmentRequester::all();
        $estimate_no = Spare_parts_request::orderBy('s_p_req_mst_id', 'desc')->get('estimate_no');
        $requestShift = L_RosterShift::all();
        $operator = DB::select('SELECT emp_id, emp_name, emp_code FROM pmis.l_department d, PMIS.EMPLOYEE E WHERE E.DPT_DEPARTMENT_ID = D.DEPARTMENT_ID');
        $workShopType = L_WorkshopType::all();
        $equipmentType = L_Equipment_Type::all();
        $service = Service_MST::all();
        $location = Location::all();
        $reportForm = view('eqms.reportgenerator.report-params', compact('report', 'equipment', 'berthoperator', 'nothiNumber', 'requester', 'estimate_no', 'requestShift', 'operator', 'workShopType', 'equipmentType', 'service', 'location'))->render();
        return $reportForm;

    }

}
