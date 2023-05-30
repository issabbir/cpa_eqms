<?php

namespace App\Http\Controllers\Eqms\Setup;

use App\Http\Controllers\Controller;
use App\Entities\Eqms\WorkflowTeam;
use App\Managers\Eqms\SetupManager;
use App\Helpers\HelperClass;
use App\Managers\LookupManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDO;

class WorkflowTeamController extends Controller
{

    protected $workflowTeam;

    public function __construct(WorkflowTeam $workflowTeam,  SetupManager $setupManager, LookupManager $lookupManager)
    {
        $this->workflowTeam = $workflowTeam;
        $this->setupManager = $setupManager;
        $this->lookupManager = $lookupManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empDepartment = HelperClass::get_emp_department_id();
        if(Auth::user()->hasRole('SUPER_ADMIN'))
        {
            $departments = $this->setupManager->findDepartment();
            $dpt_department_id = '';
        }else{
            $departments = [];
            $departments[]= $this->setupManager->findDepartment($empDepartment);
            $dpt_department_id = $empDepartment;
        }
        $alldepartments = $this->setupManager->findDepartment();
       // 'designations' => $this->lookupManager->findDesignation()
        return view('eqms.setup.workflow-team.index', [
            'data'              => $this->workflowTeam,
            'departments'       => $departments,
            'alldepartments'       => $alldepartments,
            'dpt_department_id' => $dpt_department_id

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'department_id'      => 'required',
                'p_employee_id'      => 'required',
                // 'sequence_no'       => 'required',
                'active_yn'      => 'required',
            ],
            [
                'department_id.required'    => 'The department field is required.',
                'p_employee_id.required'       => 'The Employee field is required.',
                // 'sequence_no.required'       => 'The Sequence field is required.',
                'active_yn.required'         => 'The Active field is required.'
            ]
        );


        $response = $this->workflow_team_ins($request);

        if ($response['o_status_code'] == 1) {
            session()->flash('m-class', 'alert-success');
            session()->flash('message', $response['o_status_message']);
            return redirect()->route('workflow-team-index');
        } else {
            session()->flash('m-class', 'alert-danger');
            session()->flash('message', $response['o_status_message']);
            return redirect()->route('workflow-team-index')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empDepartment = HelperClass::get_emp_department_id();
        $data           = $this->workflowTeam->with('employee', 'department')->find($id);
        $empInf         = $this->setupManager->findEmpDetails($data->emp_id);
        $data['emp_name'] = $empInf->emp_name;

        if(Auth::user()->hasRole('SUPER_ADMIN'))
        {
            $departments = $this->setupManager->findDepartment();
            $dpt_department_id = '';
        }else{
            $departments = [];
            $departments[]= $this->setupManager->findDepartment($empDepartment);
            $dpt_department_id = $empDepartment;
        }
        $alldepartments = $this->setupManager->findDepartment();
        return view('eqms.setup.workflow-team.index', [
            'data' => $data,
            'empInf'    => $empInf,
            'departments' => $departments,
            'alldepartments' => $alldepartments,
            'dpt_department_id' => $dpt_department_id
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function workflow_team_ins(Request $request)
    {
        DB::beginTransaction();
        $postData = $request->post();
        try{
            $team_id = $postData['team_id'] ? $postData['team_id'] : null;
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            //$storeInfo = $this->setupManager->findStoreByDepartment($postData['department_id']);

            //dd($storeInfo);
            $empinfo = $this->setupManager->findEmpDetails($postData['p_employee_id']);
            $params = [
                'p_team_id' => [
                    'value' => &$team_id,
                    "type" => PDO::PARAM_INPUT_OUTPUT,
                    "length" => 255
                ],
                'p_emp_id'              => $postData['p_employee_id'],
                'p_emp_code'            => $empinfo->emp_code,
                'p_department_id'       => $postData['department_id'],
                'p_emp_department_id'   => $postData['emp_department_id'],
                'p_active_yn'           => $postData['active_yn'],
                'p_insert_by'           => auth()->id(),
                'o_status_code'         => &$status_code,
                'o_status_message'      => &$status_message
            ];
            // dd($params);
            DB::executeProcedure("workflow_team_entry", $params);
            //Log::info($params);
            DB::commit();
        }catch(\Exception $e)
        {
            DB::rollBack();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function dataTableList()
    {
        $queryResult = $this->workflowTeam->with(['employee', 'department']);

        if(!Auth::user()->hasRole('SUPER_ADMIN'))
        {
            $queryResult->where('department_id', HelperClass::get_emp_department_id());
        }

        $queryResult->orderBy('insert_date','desc');
        //dd($queryResult->get());
        return datatables()->of($queryResult->get())
        ->addcolumn('employee_name', function ($query) {
            return isset($query->employee) ? $query->employee->emp_code.' '.$query->employee->emp_name : '';
        })
        ->addcolumn('department_name', function ($query) {
            return isset($query->department) ? $query->department->department_name : '';
        })
        ->addColumn('active_yn', function($query) {
            return $query->active_yn == 'Y' ? 'Active' : 'Inactive';
        })
        ->addColumn('action', function ($query) {

            return '<a class="" title="Edit" href="' . route('workflow-team-edit', $query->team_id) . '"><i class="bx bx-edit cursor-pointer"></i></a> &nbsp';
        })
        ->addIndexColumn()
        ->escapeColumns([])
        ->make(true);
    }

    public function searchEmpByCurrentDepartment(Request $request)
    {
        if($request->input('emp_depart_id', '')){
            $emp_depart = $request->input('emp_depart_id', '');
        }else{
            $emp_depart = HelperClass::get_emp_department_id();
        }

        //echo $emp_depart ;
        $array = ['results' => $this->setupManager->findEmpByCurrentDepartment($request->input('emp_name', ''), $emp_depart)];
        //dd($array);
        return json_encode($array);
    }
}
