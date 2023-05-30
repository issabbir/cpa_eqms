<?php
//app/Helpers/HelperClass.php
namespace App\Helpers;

use App\Entities\Admin\LGeoDistrict;
use App\Entities\Admin\LGeoThana;
use App\Entities\Ams\LPriorityType;
use App\Entities\Ams\OperatorMapping;
use App\Entities\Eqms\ApprovalInfo;
use App\Entities\Eqms\Workflow;
use App\Entities\Eqms\WorkflowTeam;
use App\Entities\Pmis\Employee\Employee;
use App\Entities\Security\Menu;
use App\Enums\Secdbms\Watchman\AppointmentType;
use App\Managers\Authorization\AuthorizationManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HelperClass
{

    public $id;
    public $links;

    public static function breadCrumbs($routeName)
    {//dd($routeName);
        if (in_array($routeName, ['spare-parts-request'])) {
            return [
                ['submenu_name' => ' Spare Parts Request', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['spare-parts-request-edit'])) {
            return [
                ['submenu_name' => 'Spare Parts Request Edit', 'action_name' => ''],
            ];
        } else if (in_array($routeName, ['equipment-service-index'])) {
            return [
                ['submenu_name' => ' Scheduled Service', 'action_name' => '']
            ];
        } else if (in_array($routeName, ['equipment-service-edit'])) {
            return [
                ['submenu_name' => ' Scheduled Service Edit', 'action_name' => '']
            ];
        } else {
            $breadMenus = [];

            try {
                $authorizationManager = new AuthorizationManager();
                $getRouteMenuId = $authorizationManager->findSubMenuId($routeName);
                if ($getRouteMenuId && !empty($getRouteMenuId)) {
                    $breadMenus[] = $bm = $authorizationManager->findParentMenu($getRouteMenuId);
                    if ($bm && isset($bm['parent_submenu_id']) && !empty($bm['parent_submenu_id'])) {
                        $breadMenus[] = $authorizationManager->findParentMenu($bm['parent_submenu_id']);
                    }
                }
            } catch (\Exception $e) {
                return false;
            }

            return is_array($breadMenus) ? array_reverse($breadMenus) : false;
        }
    }

    public static function findDistrictByDivision($divisionId)
    {
        return LGeoDistrict::where('geo_division_id', $divisionId)->get();
    }

    public static function findDivisionByThana($districtId)
    {
        return LGeoThana::where('geo_district_id', $districtId)->get();
    }

    public static function isNewspaper($typeId)
    {
        return ((AppointmentType::NEWSPAPER_ADVERTISEMENT == $typeId) || ($typeId == null));
    }

    public static function isSupplierAgency($typeId)
    {
        return (AppointmentType::SUPPLIER_AGENCY == $typeId);
    }

    public const REQUIRED = 'required';

    public static function getRequiredForNewsPaper($typeId)
    {
        if (static::isNewspaper($typeId))
            return static::REQUIRED;

        return '';
    }

    public static function getRequiredForSupplierAgency($typeId)
    {
        if (static::isSupplierAgency($typeId))
            return static::REQUIRED;

        return '';
    }

    public static function get_emp_department_id()
    {
        //$empDepartment = Auth::user()->employee->dpt_department_id;
	if(isset(Auth::user()->employee->current_department_id)){
	     $empDepartment = Auth::user()->employee->current_department_id;
	}else{
	     $empDepartment = null;
	}
        
        return $empDepartment;
    }

    public static function getWorkFlowTeam()
    {
        //$team = WorkflowTeam::with('employee')->where('department_id',self::get_emp_department_id())->where('active_yn','Y')->get();
        $team = WorkflowTeam::with('employee')->where('active_yn', 'Y')->get();
        //var_dump($team);exit;
        return $team;
    }

    public static function getWorkFlowStepByObjectId($object_id)
    {

        $steps = Workflow::select('emp_code')->where('reference_id', $object_id)->orderBy('approval_seq_no', 'ASC')->get();

        // dd($steps);
        $data = [];
        foreach ($steps as $step) {
            $emp = Employee::where("emp_code", $step->emp_code)->first();
            if ($emp) {
                $data[$step->emp_code] = $emp->emp_name . ' (' . $step->emp_code . ')';
            }

        }
        return $data;
    }

    public static function workflow_ins($request, $mst_id)
    {
        $postData = $request->post();
        $data = Workflow::where('reference_id', $mst_id)->delete();

        try {
            $workflow_recipient_id = null;
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            foreach (array_filter($postData['workflow_team']) as $key => $team) {
                $teamInfo = WorkflowTeam::with('employee')->where('emp_code', $team)->first();//dd($teamInfo);
                $params = [
                    'p_workflow_recipient_id' => $workflow_recipient_id,
                    'p_rule_id' => 1,
                    'p_active_yn' => 'Y',
                    'p_employee_id' => $teamInfo->emp_id,
                    'p_department_id' => $teamInfo->emp_department_id,
                    'p_department_head_yn' => 'N',
                    'p_edit_permission_yn' => 'Y',
                    'p_designation_id' => $teamInfo->employee->charge_designation_id ? $teamInfo->employee->charge_designation_id : $teamInfo->employee->designation_id,
                    'p_designation_name' => $teamInfo->employee->charge_designation_id ? $teamInfo->employee->addi_designation->designation : $teamInfo->employee->designation->designation,
                    'p_reference_id' => $mst_id,
                    'p_employee_code' => $teamInfo->emp_code,
                    'p_employee_name' => $teamInfo->employee->emp_name,
                    'p_insert_by' => auth()->id(),
                    'o_status_code' => &$status_code,
                    'o_status_message' => &$status_message,
                ];
                // print_r($params);

                DB::executeProcedure("workflow_recipient_save", $params);//dd($params);
            }

            // dd( $params);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            exit;
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => 'Something went wrong.'];

        }

        return $params;
    }

    public static function workflow($rule, $id)
    {//dd($id);
        $approvalinfo = ApprovalInfo::with(['employee', 'status'])
            ->where('rule_id', $rule)
            ->where('history_yn', 'N')
            ->where('reference_id', $id)
            ->orderBy('approval_ref_seq')
            ->get();


        return $approvalinfo;
    }

    public static function get_signture($emp_id)
    {
        $sql = "SELECT
		            FILE_CONTENT AS SIGNATURE
	            FROM
		            PMIS.EMP_ATTACHMENTS
	            WHERE
		            EMP_ID = :emp_id
		        AND ROWNUM = 1 AND ATTACHMENT_TYPE_ID=3";
        $signature = DB::selectOne($sql, ['emp_id' => $emp_id]);
        return $signature;
    }

    public static function equipment_list_section_wise()
    {
        $userSection = Employee::select('SECTION_ID', 'EMP_ID')->where('EMP_ID', '=', Auth::user()->emp_id)->first(); // So that logged in user can see only his Assigned Sections data.

        $user_role = json_encode(Auth::user()->roles->pluck('role_key'));

        if (strpos($user_role, "SUPER_ADMIN") !== FALSE) {
            $querys = "SELECT * FROM EQUIPMENT WHERE EQUIP_ID NOT IN (SELECT EQUIP_ID FROM EQMS.SERVICE_MST)";
        } else {
            $querys = "SELECT * FROM EQUIPMENT WHERE EQUIP_ID NOT IN (SELECT EQUIP_ID FROM EQMS.SERVICE_MST) and Equipment.WORKSHOP_ID = $userSection->section_id";
        }

        $eqList = db::select($querys);

        return $eqList;
    }
}
