<?php


namespace App\Repositories;


use App\Entities\Eqms\ServiceTicketAssign;
use App\Entities\Eqms\ServiceTicket;
use App\Entities\Eqms\ServiceEngineerInfoList;
use Illuminate\Http\Request;

class ServiceTicketAssignRepo
{
    protected  $serviceTicketAssign;
    protected  $serviceTicket;
    protected  $engineerInfo;
    protected $request;

    /**
     *
     * ServiceTicketAssignRepo constructor.
     * @param ServiceTicketAssign $serviceTicketAssign
     */
    public function __construct(ServiceTicketAssign $serviceTicketAssign, ServiceTicket $serviceTicket, ServiceEngineerInfoList $engineerInfo)
    {
        $this->serviceTicketAssign = $serviceTicketAssign;
        $this->serviceTicket = $serviceTicket;
        $this->engineerInfo = $engineerInfo;
    }

    /**
     * Find all catagorys for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->serviceTicketAssign->get();
    }

    /**
     * Find serviceTicketAssign specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->serviceTicketAssign->where('ASSIGNMENT_NO', $id)->first();
    }


    public function findTicket($ticket_no) {
        return $this->serviceTicket->where('ticket_no', $ticket_no)->get();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getServiceTicketAssign() {
        return $this->serviceTicketAssign;
    }

    /**
     * getting equipment id
     * @return mixd
     */
    public function getTicketNo()
    {
        return $this->serviceTicket->get();
    }


    /**
     * Find all service tickets priority for all
     * @return mixed
     */
    public function findAllServiceEngineer() {
        //Todo: Applied filters option as you need
        return $this->engineerInfo->get();
    }
}
