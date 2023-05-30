<?php


namespace App\Repositories;


use App\Entities\Eqms\ServiceStatus;
use Illuminate\Http\Request;

class ServiceStatusRepo
{
    protected  $servicestatus;
    protected $request;

    /**
     *
     * ServiceStatusRepo constructor.
     * @param ServiceStatus $servicestatuses
     */
    public function __construct(ServiceStatus $servicestatus)
    {
        $this->servicestatus = $servicestatus;
    }

    /**
     * Find all servicestatus for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->servicestatus->orderBy('status_no', 'desc')->get();
    }

    /**
     * Find servicestatus specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->servicestatus->where('status_no', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getServiceStatus() {
        return $this->servicestatus;
    }
}
