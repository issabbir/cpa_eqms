<?php


namespace App\Repositories;


use App\Entities\Eqms\TicketType;
use Illuminate\Http\Request;

class TicketTypeRepo
{
    protected  $ticketype;
    protected $request;

    /**
     *
     * TicketTypeRepo constructor.
     * @param TicketType $ticketype
     */
    public function __construct(TicketType $ticketype)
    {
        $this->ticketype = $ticketype;
    }

    /**
     * Find all ticketype for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->ticketype->orderBy('ticket_type_no', 'desc')->get();
    }

    /**
     * Find ticketype specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->ticketype->where('ticket_type_no', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getTicketType() {
        return $this->ticketype;
    }
}
