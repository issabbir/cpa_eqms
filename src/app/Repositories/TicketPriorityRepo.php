<?php


namespace App\Repositories;


use App\Entities\Eqms\TicketPriority;
use Illuminate\Http\Request;

class TicketPriorityRepo
{
    protected  $ticketpriority;
    protected $request;

    /**
     *
     * TicketPriorityRepo constructor.
     * @param TicketPriority $ticketpriority
     */
    public function __construct(TicketPriority $ticketpriority)
    {
        $this->ticketpriority = $ticketpriority;
    }

    /**
     * Find all ticketpriority for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->ticketpriority->orderBy('ticket_priority_no', 'desc')->get();
    }

    /**
     * Find ticketpriority specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->ticketpriority->where('ticket_priority_no', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getTicketPriority() {
        return $this->ticketpriority;
    }
}
