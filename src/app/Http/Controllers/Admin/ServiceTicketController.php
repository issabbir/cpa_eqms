<?php

namespace App\Http\Controllers\Eqms\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Entities\Eqms\TicketType;
use App\Managers\ProcedureManager;
use Illuminate\Support\Facades\Session;
use App\Managers\Ccms\ServiceTicketManager;
use App\Managers\Ccms\ServiceTicketAssignManager;
use Illuminate\Http\Request;

class ServiceTicketController extends Controller
{
    	/**
    	 * @param Request $request
    	 * @param ServiceTicketManager $genSetupManager
    	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
    	 */
        public function index(Request $request, ServiceTicketManager $serviceTicketManager, ServiceTicketAssignManager $serviceTicketAssignManager)
        {
        	$data = $serviceTicketManager->getServiceTicketRepo()->findOne($request->get('id'));
            $gen_uniq_id = DB::selectOne('select gen_unique_id  as unique_id from dual')->unique_id;
            $getTicketPriorityNo = $serviceTicketManager->getServiceTicketRepo()->findAllTicketPriority();
            $getTicketTypeNo = $serviceTicketManager->getServiceTicketRepo()->findAllTicketType();
            $assigndata = $serviceTicketAssignManager->getServiceTicketAssignRepo()->findOne($request->get('id'));
            $getTicketNo =  $serviceTicketAssignManager->getServiceTicketAssignRepo()->getTicketNo();
            $getServiceEngineerId = $serviceTicketAssignManager->getServiceTicketAssignRepo()->findAllServiceEngineer();
            // dd($ticketNo);
            return view('ccms.service_ticket', compact('data', 'gen_uniq_id', 'getTicketPriorityNo', 'getTicketTypeNo', 'getServiceEngineerId', 'getTicketNo', 'assigndata'));
        }

        public function ticketDtl(Request $request, ServiceTicketManager $serviceTicketManager, ServiceTicketAssignManager $serviceTicketAssignManager)
        {
            $data = $serviceTicketManager->getServiceTicketRepo()->findOne($request->get('id'));
            $getTicketDetls = $serviceTicketManager->getServiceTicketRepo()->findOne($request->get('id'));
            $getTicketAction = $serviceTicketManager->getServiceTicketRepo()->serviceTicketAction();
            $getServiceStatus = $serviceTicketManager->getServiceTicketRepo()->findServiceStatus();
            $gen_uniq_id = DB::selectOne('select gen_unique_id  as unique_id from dual')->unique_id;
            $assigndata = $serviceTicketAssignManager->getServiceTicketAssignRepo()->findOne($request->get('id'));
            $getTicketNo =  $serviceTicketAssignManager->getServiceTicketAssignRepo()->getTicketNo();
            $getServiceEngineerId = $serviceTicketAssignManager->getServiceTicketAssignRepo()->findAllServiceEngineer();
            // dd($getServiceStatus);
            return view('ccms.ticket_dtl', compact('getTicketDetls', 'data', 'getServiceStatus', 'getTicketAction', 'getServiceEngineerId', 'getTicketNo', 'assigndata', 'gen_uniq_id'));
        }

        /**
         * Service Ticket table data list
         *
         * @param Request $request
         * @param ServiceTicketManager $serviceTicketManager
         * @return mixed
         * @throws \Exception
         */
        public function list(Request $request, ServiceTicketManager $serviceTicketManager) {
            return $serviceTicketManager->getServiceTicketTables($request);
        }

        public function store($id = null, Request $request, ProcedureManager $procedureManager)
        {
            $result = $procedureManager->execute('TICKET.SERVICE_TICKET_CRUD', $request)->getParams();
            // dd($result);
            if ($result['o_status_code'] == 1) {
                Session::flash('success', $result['o_status_message']);
                return redirect()->route('service_ticket.index');
            }

            Session::flash('error', $result['o_status_message']);
            if ($id)
             return redirect()->route('service_ticket.index', ['id' => $id]);

             return redirect()->route('service_ticket.index');
        }

}
