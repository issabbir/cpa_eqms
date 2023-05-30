<?php


namespace App\Repositories;


use App\Entities\Eqms\EquipmentReceive;
use App\Entities\Eqms\ServiceTicket;
use App\Entities\Eqms\EquipmentList;
use App\Entities\Eqms\ServiceEngineerInfoList;

class EquipmentReceiveRepo
{
    protected  $equipmentReceive;
    protected  $serviceTicket;
    protected  $equipmentList;
    protected  $engineerInfo;
    protected $request;

    /**
     *
     * EquipmentReceiveRepo constructor.
     * @param EquipmentReceive $equipmentReceive
     */
    public function __construct(EquipmentReceive $equipmentReceive, EquipmentList $equipmentList, ServiceTicket $serviceTicket, ServiceEngineerInfoList $engineerInfo)
    {
        $this->equipmentReceive = $equipmentReceive;
        $this->equipmentList = $equipmentList;
        $this->serviceTicket = $serviceTicket;
        $this->engineerInfo = $engineerInfo;
    }

    /**
     * Find all servicetickets for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->equipmentReceive->orderBy('receipt_no', 'desc')
//            ->leftjoin('service_ticket', 'service_ticket.ticket_no', '=', 'service_equipment_receive.ticket_no')
              ->leftjoin('equipment_list', 'equipment_list.equipment_no', '=', 'service_equipment_receive.equipment_no')
              ->join('service_engineer_info', 'service_engineer_info.service_engineer_id', '=', 'service_equipment_receive.service_engineer_id')
              ->select('service_equipment_receive.*', 'equipment_list.equipment_name', 'service_engineer_info.service_engineer_name', 'service_engineer_info.service_engineer_info_id')
              ->get();
    }

    /**
     * Find all service tickets priority for all
     * @return mixed
     */
    public function findAllServiceTicket() {
        //Todo: Applied filters option as you need
        return $this->serviceTicket->get();
    }

    /**
     * Find all service tickets priority for all
     * @return mixed
     */
    public function findAllEquipmentList() {
        //Todo: Applied filters option as you need
        return $this->equipmentList->get();
    }

    /**
     * Find all service tickets priority for all
     * @return mixed
     */
    public function findAllServiceEngineer() {
        //Todo: Applied filters option as you need
        return $this->engineerInfo->get();
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
     * Find EquipmentReceive specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->equipmentReceive->where('receipt_no', $id)
//            ->leftjoin('service_ticket', 'service_ticket.ticket_no', '=', 'service_equipment_receive.ticket_no')
            ->leftjoin('equipment_list', 'equipment_list.equipment_no', '=', 'service_equipment_receive.equipment_no')
            ->join('service_engineer_info', 'service_engineer_info.service_engineer_id', '=', 'service_equipment_receive.service_engineer_id')
            ->select('service_equipment_receive.*', 'equipment_list.equipment_name', 'service_engineer_info.service_engineer_name', 'service_engineer_info.service_engineer_info_id')
            ->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getEquipmentReceive() {
        return $this->equipmentReceive;
    }
}
