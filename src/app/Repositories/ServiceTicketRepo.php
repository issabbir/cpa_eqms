<?php


namespace App\Repositories;


use App\Entities\Eqms\ServiceTicket;
use App\Entities\Eqms\TicketType;
use App\Entities\Eqms\TicketPriority;
use App\Entities\Eqms\ServiceTicketActionList;
use App\Entities\Eqms\ServiceStatus;
use Illuminate\Support\Facades\DB;
use App\Enums\Pmis\Employee\Statuses;
use App\Entities\Pmis\Employee\Employee;
use Illuminate\Http\Request;

class ServiceTicketRepo
{
    protected  $employee;
    protected  $serviceticket;
    protected  $ticketType;
    protected  $serviceTicketPriority;
    protected  $serviceTicketActionList;
    protected  $serviceStatus;
    protected $request;

    /**
     *
     * ServiceTicketRepo constructor.
     * @param ServiceTicket $serviceticket
     */
    public function __construct(ServiceTicket $serviceticket, TicketPriority $serviceTicketPriority, TicketType $ticketType, Employee $employee, ServiceStatus $serviceStatus, ServiceTicketActionList $serviceTicketActionList)
    {
        $this->serviceTicketActionList = $serviceTicketActionList;
        $this->serviceTicketPriority = $serviceTicketPriority;
        $this->serviceStatus = $serviceStatus;
        $this->serviceticket = $serviceticket;
        $this->ticketType = $ticketType;
        $this->employee = $employee;
    }

    /**
     * Find all servicetickets for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->serviceticket::orderBy('ticket_no', 'desc')->get();
    }


    /**
     * Find all servicetickets for all
     * @return mixed
     */
    public function findMyTicket(){
        return ServiceTicket::where("emp_id",auth()->user()->emp_id)->orderBy('ticket_no', 'DESC');
    }

    /**
     * Find all servicetickets for all
     * @return mixed
     */
    public function serviceTicketAction() {
        //Todo: Applied filters option as you need
        return $this->serviceTicketActionList->get();
    }

    /**
     * Find all servicetickets for all
     * @return mixed
     */
    public function findServiceStatus() {
        //Todo: Applied filters option as you need
        return $this->serviceStatus->get();
    }

    /**
     * Find all servicetickets for all
     * @return mixed
     */
    public function getAllEmployeeId() {
        //Todo: Applied filters option as you need
        return $this->employee->get();
    }

    public function findEmpNameCode($name = null)
    {
    // return Employee::Where('emp_name','like',"%$keyword")->orWhere('emp_code','like',"%$keyword%")->orderBy("emp_name", 'asc')->limit(20)->get(['emp_id as id', 'emp_name', 'emp_code', 'dpt_division_id', 'dpt_department_id', 'designation_id']);
    return Employee::select('emp_id as id','emp_code',DB::raw("emp_code||' '||emp_name AS option_name"),'emp_name','designation_id','dpt_department_id','section_id','bill_code','dpt_division_id')
    ->where('pmis.employee.emp_status_id','=', Statuses::ON_ROLE) //Only on roll employee
    ->where(function($query) use ($name){
        $query->where(DB::raw('LOWER(emp_name)'),'like','%'.strtolower(trim($name).'%'))
        ->orWhere('emp_code', 'like', '%'.trim($name)."%" );
    })
    ->groupBy('emp_id','emp_code','emp_name','designation_id','dpt_department_id','section_id','bill_code','dpt_division_id')
    ->limit(20)
    ->get();
    }

    /**
     * Find all service tickets priority for all
     * @return mixed
     */
    public function findAllTicketPriority() {
        //Todo: Applied filters option as you need
        return $this->serviceTicketPriority->get();
    }

    /**
     * Find all service tickets priority for all
     * @return mixed
     */
    public function findAllTicketType() {
        //Todo: Applied filters option as you need
        return $this->ticketType->get();
    }

    /**
     * Find serviceticket specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOneTicket($idtt) {
        return $this->ticketType->where('ticket_type_no', $idtt)->first();
    }

    public function findServiceTicketStatus($service_status_no, $ticket_type_no) {
        return $this->serviceStatus->where('service_status_no', $service_status_no)->where('ticket_type_no', $ticket_type_no)->get();
    }

    public function findTicketTypes($ticket_type_no) {
        return $this->ticketType->where('ticket_type_no', $ticket_type_no)->get();
    }

    public function findTicketNo($ticket_no) {
        return $this->ticketType->where('ticket_no', $ticket_no)->get();
    }

    /**
     * Find serviceticket specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->serviceticket->where('ticket_no', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getServiceTicket() {
        return $this->serviceticket;
    }

    public function getServiceTicketDashboard() {
        return $this->serviceticket::where('service_engineer_info.user_name',auth()->user()->user_name)
            ->where('service_status_no','!=','1005')
            ->where('service_status_no','!=','1008')
            ->leftjoin('service_engineer_info', 'service_ticket.assign_engineer_id', '=', 'service_engineer_info.service_engineer_id')
            ->leftjoin('equipment_list', 'service_ticket.equipment_no', '=', 'equipment_list.equipment_no');
    }

    public function getData($id) {
        return  $this->serviceticket->where('ticket_no', $id)
            ->leftjoin('equipment_list', 'service_ticket.equipment_no', '=', 'equipment_list.equipment_no')
            ->select('service_ticket.*', 'equipment_list.equipment_name')
            ->first();
    }
}
