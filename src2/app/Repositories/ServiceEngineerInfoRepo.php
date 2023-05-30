<?php


namespace App\Repositories;


use App\Entities\Eqms\ServiceEngineerInfoList;
use App\Entities\Eqms\VendorType;
use Illuminate\Http\Request;

class ServiceEngineerInfoRepo
{
    protected  $serviceEngineerInfo;
    protected $request;

    /**
     *
     * ServiceEngineerInfoRepo constructor.
     * @param ServiceEngineerInfoList $serviceEngineerInfoList
     */
    public function __construct(ServiceEngineerInfoList $serviceEngineerInfoList)
    {
        $this->serviceEngineerInfo = $serviceEngineerInfoList;
    }

    /**
     * Find all serviceEngineerInfo for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->serviceEngineerInfo->get();
    }


    /**
     * Find serviceEngineerInfo specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->serviceEngineerInfo->where('service_engineer_info_id', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getServiceEngineerInfo() {
        return $this->serviceEngineerInfo;
    }


}
