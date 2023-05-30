<?php


namespace App\Repositories;


use App\Entities\Eqms\ServiceTicketActionList;
use Illuminate\Http\Request;

class ServiceTicketActionListRepo
{
    protected  $actionlist;
    protected $request;

    /**
     *
     * ServiceTicketActionListRepo constructor.
     * @param ServiceTicketActionList $serviceticketactionlist
     */
    public function __construct(ServiceTicketActionList $serviceticketactionlist)
    {
        $this->actionlist = $serviceticketactionlist;
    }

    /**
     * Find all serviceticketactionlist for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->actionlist->orderBy('action_no', 'desc')->get();
    }

    /**
     * Find serviceticketactionlist specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->actionlist->where('action_no', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getServiceStatus() {
        return $this->actionlist;
    }
}
