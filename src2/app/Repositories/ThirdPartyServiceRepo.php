<?php


namespace App\Repositories;


use App\Entities\Eqms\ThirdPartyService;
use App\Entities\Eqms\EquipmentList;
use App\Entities\Eqms\ServiceTicket;
use App\Entities\Eqms\VendorList;
use Illuminate\Http\Request;

class ThirdPartyServiceRepo
{
    protected  $thirdpartyservice;
    protected  $equipmentList;
    protected  $serviceTicket;
    protected  $vendorList;
    protected  $request;

    /**
     *
     * ThirdPartyServiceRepo constructor.
     * @param ThirdPartyService $thirdpartyservice
     */
    public function __construct(ThirdPartyService $thirdpartyservice, EquipmentList $equipmentList, ServiceTicket $serviceTicket, VendorList $vendorList)
    {
        $this->thirdpartyservice = $thirdpartyservice;
        $this->equipmentList = $equipmentList;
        $this->serviceTicket = $serviceTicket;
        $this->vendorList = $vendorList;
    }

    /**
     * Find all thirdpartyservices for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->thirdpartyservice->get();
    }

    /**
     * Find thirdpartyservice specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->thirdpartyservice->where('third_party_service_id', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getThirdPartyService() {
        return $this->thirdpartyservice;
    }

    /**
     * getting equipment id
     * @return mixd
     */
    public function getEquipmentID()
    {
        return $this->equipmentList->get();
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
     * getting equipment id
     * @return mixd
     */
    public function getVendorNo()
    {
        return $this->vendorList->get();
    }
}
