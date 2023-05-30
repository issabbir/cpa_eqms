<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'UserController@index')->name('login');

Route::post('/authorization/login', 'Auth\LoginController@authorization')->name('authorization.login');
// FIXME: FORGOT PASSWORD. NEEDED.
/*Route::get('/forgot-password', 'Auth\ForgotPassword2Controller@forgotPassword')->name('forgot-password');
Route::post('/forgot-password-email', 'Auth\ForgotPassword2Controller@forgotPasswordEmail')->name('forgot-password-email');
Route::get('/reset-password/{pin}', 'Auth\ForgotPassword2Controller@resetPassword')->name('reset-password');
Route::post('/reset-password/{pin}', 'Auth\ForgotPassword2Controller@resetPasswordPost')->name('reset-password-post');*/
// FIXME: FORGOT PASSWORD. NEEDED END

Route::group(['middleware' => ['auth']], function () {

       //setup routes
        Route::group(['name' => 'setup.', 'prefix' => 'setup'], function() {

        });

        //Berth Operator Profile
        Route::get('/berth-operator', 'Eqms\BerthOperatorController@index')->name('berth-operator-index');
        Route::get('/berth-operator-edit/{id}', 'Eqms\BerthOperatorController@edit')->name('berth-operator-edit');
        Route::put('/berth-operator-update/{id}', 'Eqms\BerthOperatorController@update')->name('berth-operator-update');
        Route::post('/berth-operator-post', 'Eqms\BerthOperatorController@post')->name('berth-operator-post');
        Route::post('/berth-operator-datatable', 'Eqms\BerthOperatorController@dataTableList')->name('berth-operator-datatable');

        //Equipment Add
        Route::get('/equipment', 'Eqms\EquipmentController@index')->name('add-equipment-index');
        Route::get('/equipment/{id}', 'Eqms\EquipmentController@edit')->name('add-equipment-edit');
        Route::put('/equipment/{id}', 'Eqms\EquipmentController@update')->name('add-equipment-update');
        Route::post('/equipment-post', 'Eqms\EquipmentController@post')->name('add-equipment-post');
        Route::post('/equipment-datatable', 'Eqms\EquipmentController@dataTableList')->name('add-equipment-datatable');
        Route::get('/download/{id}', 'Eqms\EquipmentController@downloader')->name('file-download');
        Route::get('/doc-remove', 'Eqms\EquipmentController@removeDoc')->name('docRemove');

    //Roster
        Route::get('/duty-roster', 'Eqms\RosterController@index')->name('duty-roster-index');
        Route::get('/duty-roster/{id}', 'Eqms\RosterController@edit')->name('duty-roster-edit');
        Route::put('/duty-roster/{id}', 'Eqms\RosterController@update')->name('duty-roster-update');
        Route::post('/duty-roster-post', 'Eqms\RosterController@post')->name('duty-roster-post');
        Route::post('/duty-roster-datatable', 'Eqms\RosterController@dataTableList')->name('duty-roster-datatable');
        Route::get('/get-employee-mechanic', 'Eqms\RosterController@getEmpMechanic')->name('get-employee-mechanic');
        Route::get('/get-employee-traffic', 'Eqms\RosterController@getEmpTraffic')->name('get-employee-traffic');
        Route::get('/roster-data-remove', 'Eqms\RosterController@removeData')->name('roster-data-remove');

        //Equipment Request
        Route::get('/equipment-request', 'Eqms\EquipmentReqController@index')->name('equipment-request-index');
        Route::get('/equipment-request/{id}', 'Eqms\EquipmentReqController@edit')->name('equipment-request-edit');
        Route::put('/equipment-request/{id}', 'Eqms\EquipmentReqController@update')->name('equipment-request-update');
        Route::post('/equipment-request-post', 'Eqms\EquipmentReqController@post')->name('equipment-request-post');
        Route::post('/equipment-request-datatable', 'Eqms\EquipmentReqController@dataTableList')->name('equipment-request-datatable');

        Route::post('/equipment-dtl-post', 'Eqms\EquipmentReqController@dtlPost')->name('equipment-dtl-post');
        Route::get('/get-eqp-req-dtl-data/{eqr_id}/{erm_id}','Eqms\EquipmentReqController@getDtlData')->name("get-detail-data");
        Route::get('/eq-dtl-data-remove', 'Eqms\EquipmentReqController@removeDtlData')->name('eq-dtl-data-remove');
        Route::get('/eq-mst-data-remove', 'Eqms\EquipmentReqController@removeMstData')->name('eq-mst-data-remove');
        Route::get('/get-location', 'Eqms\EquipmentReqController@getLocation')->name('get-location');

        //Equipment Request Approval
        Route::get('/equip-request-approval', 'Eqms\RequestApprovalController@index')->name('equip-request-approval-index');
        Route::get('/equip-request-approval/{id}', 'Eqms\RequestApprovalController@edit')->name('equip-request-approval-edit');
        Route::put('/equip-request-approval/{id}', 'Eqms\RequestApprovalController@update')->name('equip-request-approval-update');
        Route::post('/equip-request-approval-datatable', 'Eqms\RequestApprovalController@dataTableList')->name('equip-request-approval-datatable');
        Route::post('/equipment-req-dtl-post', 'Eqms\RequestApprovalController@dtlPost')->name('equipment-req-dtl-post');
        Route::get('/get-eqp-req-dtl/{eqr_id}/{erm_id}','Eqms\RequestApprovalController@getDtlData')->name("get-req-detail-data");
        Route::get('/submission-chk/{eqr_id}','Eqms\RequestApprovalController@subChk')->name("submission-chk");
        Route::post('/approve-reject', 'Eqms\RequestApprovalController@approveReject')->name('approve-reject');

        //Equipment Assign
        Route::get('/equip-assign', 'Eqms\EquipmentAssignController@index')->name('equip-assign-index');
        Route::get('/equip-assign/{id}', 'Eqms\EquipmentAssignController@edit')->name('equip-assign-edit');
        Route::post('/equip-assign-datatable', 'Eqms\EquipmentAssignController@dataTableList')->name('equip-assign-datatable');
        Route::post('/equip-assign-dtl-post', 'Eqms\EquipmentAssignController@dtlPost')->name('equip-assign-dtl-post');
        Route::get('/get-eqp-drop/{id}/{location_id}','Eqms\EquipmentAssignController@getEquipDrop')->name("get-eqp-drop");

        //Equipment Activities
        Route::get('/equip-activities', 'Eqms\EquipmentActivitiesController@index')->name('equip-activities-index');
        Route::get('/equip-activities/{id}', 'Eqms\EquipmentActivitiesController@edit')->name('equip-activities-edit');
        Route::put('/equip-activities/{id}', 'Eqms\EquipmentActivitiesController@update')->name('equip-activities-update');
        Route::post('/equip-activities-datatable', 'Eqms\EquipmentActivitiesController@dataTableList')->name('equip-activities-datatable');
        Route::get('/show-extra-time/{time}/{equip_id}','Eqms\EquipmentActivitiesController@showExtraTime')->name("show-extra-time");

        //Equipment Serveice
        Route::get('/equipment-service', 'Eqms\EquipmentServiceController@index')->name('equipment-service-index');
        Route::get('/equipment-service/{id}', 'Eqms\EquipmentServiceController@edit')->name('equipment-service-edit');
        Route::put('/equipment-service/{id}', 'Eqms\EquipmentServiceController@update')->name('equipment-service-update');
        Route::post('/equipment-service-post', 'Eqms\EquipmentServiceController@post')->name('equipment-service-post');
        Route::post('/equipment-service-datatable', 'Eqms\EquipmentServiceController@dataTableList')->name('equipment-service-datatable');
        Route::get('/eqs-mst-data-remove', 'Eqms\EquipmentServiceController@removeMstData')->name('eqs-mst-data-remove');
        Route::get('/get-employee-info', 'Eqms\EquipmentServiceController@getEmpInfo')->name('get-employee-info');
        Route::get('/get-employee-details/{empId}', 'Eqms\EquipmentServiceController@getEmpDetails')->name('get-employee-details');
        Route::post('/service-dtl-post', 'Eqms\EquipmentServiceController@dtlPost')->name('service-dtl-post');
        Route::get('/emp-data-remove', 'Eqms\EquipmentServiceController@removeDtlData')->name('emp-data-remove');

        Route::get('/get-service-dtl-emp-data/{s_m_id}/{s_d_id}','Eqms\EquipmentServiceController@getDtlData')->name("get-service-dtl-emp-data");

        //Repair Request
        Route::get('/repair-request', 'Eqms\RepairRequestController@index')->name('repair-request-index');
        Route::get('/repair-request/{id}', 'Eqms\RepairRequestController@edit')->name('repair-request-edit');
        Route::post('/repair-request-post', 'Eqms\RepairRequestController@post')->name('repair-request-post');
        Route::put('/repair-request/{id}', 'Eqms\RepairRequestController@update')->name('repair-request-update');
        Route::get('/repair-request-data-remove', 'Eqms\RepairRequestController@removeDtlData')->name('repair-request-data-remove');
        Route::post('/repair-request-datatable', 'Eqms\RepairRequestController@dataTableList')->name('repair-request-datatable');

        //Repair Diagnosis
        Route::get('/repair-diagnosis', 'Eqms\RepairDiagnosisController@index')->name('repair-diagnosis-index');
        Route::get('/repair-diagnosis/{id}', 'Eqms\RepairDiagnosisController@edit')->name('repair-diagnosis-edit');
        Route::put('/repair-diagnosis/{id}', 'Eqms\RepairDiagnosisController@update')->name('repair-diagnosis-update');
        Route::post('/repair-diagnosis-post', 'Eqms\RepairDiagnosisController@post')->name('repair-diagnosis-post');
        Route::post('/repair-diagnosis-datatable', 'Eqms\RepairDiagnosisController@dataTableList')->name('repair-diagnosis-datatable');
        Route::get('/get-emp-data/{r_r_mst_id}/{r_r_d_id}','Eqms\RepairDiagnosisController@getDtlData')->name("get-emp-data");
        Route::get('/get-workshop', 'Eqms\RepairDiagnosisController@getWorkshop')->name('get-workshop');
        Route::get('/get-workshop-db', 'Eqms\RepairDiagnosisController@getWorkshopDb')->name('get-workshop-db');
        Route::post('/repair-diagnosis-dtl-post', 'Eqms\RepairDiagnosisController@dtlPost')->name('repair-diagnosis-dtl-post');
        Route::get('/emp-remove', 'Eqms\RepairDiagnosisController@removeEmpData')->name('emp-remove');
        Route::post('/diag-approve-reject', 'Eqms\RepairDiagnosisController@approveReject')->name("diag-approve-reject");

        //Repair Request Approval
        Route::get('/repair-request-approval', 'Eqms\RepairReqApprovalController@index')->name('repair-request-approval-index');
        Route::get('/repair-request-approval/{id}', 'Eqms\RepairReqApprovalController@edit')->name('repair-request-approval-edit');
        Route::post('/repair-request-approval/{id}', 'Eqms\RepairReqApprovalController@update')->name('repair-request-approval-update');
        Route::post('/repair-request-approval-datatable', 'Eqms\RepairReqApprovalController@dataTableList')->name('repair-request-approval-datatable');
        Route::post('/repair-approve-reject', 'Eqms\RepairReqApprovalController@approveReject')->name("repair-approve-reject");


    //Spare Parts
        Route::get('/spare-parts-request', 'Eqms\SparePartsController@index')->name('spare-parts-request');
        Route::get('/ajax-parts-stock/{part_id}','Eqms\SparePartsController@stockAjax')->name('stock-ajax');
        Route::Post('/spare-parts-post', 'Eqms\SparePartsController@store')->name('spare-parts-post');
        Route::Post('/spare-parts-request-datatable', 'Eqms\SparePartsController@dataTableList')->name('spare-parts-request-datatable');
        Route::get('/spare-parts-request-edit/{id}', 'Eqms\SparePartsController@edit')->name('spare-parts-request-edit');
        Route::get('/spare-parts-data-remove', 'Eqms\SparePartsController@removeData')->name('spare-parts-data-remove');
        Route::put('/spare-parts-update/{id}', 'Eqms\SparePartsController@update')->name('spare-parts-update');

        //Repair Part Request
        Route::post('/repair-part-request-datatable', 'Eqms\RepairPartRequestController@dataTableList')->name('repair-part-request-datatable');
        Route::get('/repair-part-request', 'Eqms\RepairPartRequestController@index')->name('repair-part-request-index');
        //Route::get('/repair-part-repair-request/{id}', 'Eqms\RepairPartRequestController@edit')->name('repair-part-request-edit');
        Route::get('/repair-part-request/{id}', 'Eqms\RepairPartRequestController@edit')->name('repair-part-request-edit');
        Route::post('/repair-part-request', 'Eqms\RepairPartRequestController@update')->name('repair-part-request-update');
        Route::get('/ws-dtl-data-remove', 'Eqms\RepairPartRequestController@removeData')->name('ws-dtl-data-remove');
        //Route::get('/get-repair-part-request/{r_p_req_mst_id}/{r_r_d_id}/{r_r_mst_id}','Eqms\RepairPartRequestController@getDtlData')->name("get-repair-part-request");
        //Route::post('/repair-part-request-dtl-post', 'Eqms\RepairPartRequestController@dtlPost')->name('repair-part-request-dtl-post');


        //Spare Parts Stock
        Route::get('/spare-part-stock', 'Eqms\SparePartStockController@index')->name('spare-part-request-index');
        Route::get('/spare-part-stock/{id}', 'Eqms\RepairPartRequestController@edit')->name('spare-part-stock-edit');
        Route::get('/ajax-estimate-parts-stock/{id}', 'Eqms\SparePartStockController@estimateParts')->name('ajax-estimate-parts-stock');
        Route::get('/get-request-stock-data/{estimate_no}/{parstID}', 'Eqms\SparePartStockController@requestStock')->name('ajax-estimate-parts-stock');
        Route::post('/spare-parts-stock-post', 'Eqms\SparePartStockController@post')->name('spare-parts-stock-post');
        Route::post('/spare-parts-stock-datatable', 'Eqms\SparePartStockController@dataTableList')->name('spare-parts-stock-datatable');
//    Route::put('/spare-part-stock/{id}', 'Eqms\RepairPartRequestController@update')->name('repair-part-request-update');
//    Route::get('//spare-part-stock-data-remove', 'Eqms\RepairPartRequestController@removeDtlData')->name('repair-part-request-data-remove');
//    ;*/

        //Parts Entry
        Route::get('/parts-entry', 'Eqms\Setup\PartsController@index')->name('parts-entry-index');
        Route::get('/parts-entry/{id}', 'Eqms\Setup\PartsController@edit')->name('parts-entry-edit');
        Route::put('/parts-entry/{id}', 'Eqms\Setup\PartsController@update')->name('parts-entry-update');
        Route::post('/parts-entry-post', 'Eqms\Setup\PartsController@post')->name('parts-entry-post');
        Route::post('/parts-entry-datatable', 'Eqms\Setup\PartsController@dataTableList')->name('parts-entry-datatable');

        //Parts Stock
        Route::get('/parts-stock', 'Eqms\PartsStockController@index')->name('parts-stock-index');
        Route::get('/parts-stock/{id}', 'Eqms\PartsStockController@edit')->name('parts-stock-edit');
        Route::post('/parts-stock-datatable', 'Eqms\PartsStockController@dataTableList')->name('parts-stock-datatable');

        //Service Entry
        Route::get('/service-entry', 'Eqms\Setup\ServiceController@index')->name('service-entry-index');
        Route::get('/service-entry/{id}', 'Eqms\Setup\ServiceController@edit')->name('service-entry-edit');
        Route::put('/service-entry/{id}', 'Eqms\Setup\ServiceController@update')->name('service-entry-update');
        Route::post('/service-entry-post', 'Eqms\Setup\ServiceController@post')->name('service-entry-post');
        Route::post('/service-entry-datatable', 'Eqms\Setup\ServiceController@dataTableList')->name('service-entry-datatable');

        //Equipment Status
        Route::get('/equipment-status', 'Eqms\EquipStatusController@index')->name('equipment-status-index');
        Route::post('/equipment-status-datatable', 'Eqms\EquipStatusController@dataTableList')->name('equipment-status-datatable');

        //Malfunction Type Setup
        Route::get('/malfunction-type', 'Eqms\Setup\MalfunctionTypeController@index')->name('malfunction-type-entry-index');
        Route::get('/malfunction-type/{id}', 'Eqms\Setup\MalfunctionTypeController@edit')->name('malfunction-type-edit');
        Route::put('/malfunction-type/{id}', 'Eqms\Setup\MalfunctionTypeController@update')->name('malfunction-type-update');
        Route::post('/malfunction-type-post', 'Eqms\Setup\MalfunctionTypeController@post')->name('malfunction-type-post');
        Route::post('/malfunction-type-datatable', 'Eqms\Setup\MalfunctionTypeController@dataTableList')->name('malfunction-type-datatable');

        //Workshop Team Setup
        Route::get('/workshop-team', 'Eqms\Setup\WSTeamSetupController@index')->name('workshop-team-entry-index');
        Route::get('/workshop-team/{id}', 'Eqms\Setup\WSTeamSetupController@edit')->name('workshop-team-edit');
        Route::put('/workshop-team/{id}', 'Eqms\Setup\WSTeamSetupController@update')->name('workshop-team-update');
        Route::post('/workshop-team-post', 'Eqms\Setup\WSTeamSetupController@post')->name('workshop-team-post');
        Route::post('/workshop-team-datatable', 'Eqms\Setup\WSTeamSetupController@dataTableList')->name('workshop-team-datatable');
        Route::get('/get-employee', 'Eqms\Setup\WSTeamSetupController@getEmp')->name('get-employee');
        Route::get('/team-data-remove', 'Eqms\Setup\WSTeamSetupController@removeData')->name('team-data-remove');

        //Work Type Entry
        Route::get('/work-type', 'Eqms\Setup\WorkTypeController@index')->name('work-type-index');
        Route::get('/work-type/{id}', 'Eqms\Setup\WorkTypeController@edit')->name('work-type-edit');
        Route::put('/work-type/{id}', 'Eqms\Setup\WorkTypeController@update')->name('work-type-update');
        Route::post('/work-type-post', 'Eqms\Setup\WorkTypeController@post')->name('work-type-post');
        Route::post('/work-type-datatable', 'Eqms\Setup\WorkTypeController@dataTableList')->name('work-type-datatable');

        //Workshop Entry
        Route::get('/workshop', 'Eqms\Setup\WorkshopController@index')->name('workshop-index');
        Route::get('/workshop/{id}', 'Eqms\Setup\WorkshopController@edit')->name('workshop-edit');
        Route::put('/workshop/{id}', 'Eqms\Setup\WorkshopController@update')->name('workshop-update');
        Route::post('/workshop-post', 'Eqms\Setup\WorkshopController@post')->name('workshop-post');
        Route::post('/workshop-datatable', 'Eqms\Setup\WorkshopController@dataTableList')->name('workshop-datatable');

        //Inventory Info
        Route::get('/inventory-info', 'Eqms\InventoryInfoController@index')->name('inventory-info-index');
        Route::get('/inventory-info/{id}', 'Eqms\InventoryInfoController@edit')->name('inventory-info-edit');
        Route::post('/inventory-info-datatable', 'Eqms\InventoryInfoController@dataTableList')->name('inventory-info-datatable');

        // Workflow Team
        Route::get('/workflow-team', 'Eqms\Setup\WorkflowTeamController@index')->name('workflow-team-index');
        Route::get('/workflow-team/{id}', 'Eqms\Setup\WorkflowTeamController@edit')->name('workflow-team-edit');
        Route::post('/workflow-team', 'Eqms\Setup\WorkflowTeamController@store')->name('workflow-team-post');
        Route::post('/workflow-team-datatable-list', 'Eqms\Setup\WorkflowTeamController@dataTableList')->name('workflow-team-datatable-list');

        Route::get('search-employee-current-department-ajax', 'Eqms\Setup\WorkflowTeamController@searchEmpByCurrentDepartment')->name('searchEmpByCurrentDepartment');

        Route::get('/report-generators', 'Eqms\ReportGeneratorController@index')->name('report-generators-index');
        Route::get('/report-generator-params/{id}', 'Eqms\ReportGeneratorController@reportParams')->name('report-params');


        Route::group(['name' => 'equipment-setup', 'as' => 'equipment-setup.'], function () {
            Route::get('/equipments-parts', 'Eqms\setup\PartsController@index')->name('parts-index');
            Route::post('/equipments-parts-store', 'Eqms\setup\PartsController@post')->name('equipments-parts-store');
        });



    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        Route::get('/user/change-password', function () {
            return view('resetPassword');
        })->name('change-password');

        Route::post('/user/change-password', 'Auth\ResetPasswordController@resetPassword')->name('user.reset-password');
        Route::post('/report/render/{title}', 'Report\OraclePublisherController@render')->name('report');
        Route::get('/report/render/{title?}', 'Report\OraclePublisherController@render')->name('report-get');
        Route::post('/authorization/logout', 'Auth\LoginController@logout')->name('logout');


        Route::group(['prefix' => 'ajax', 'name' => 'ajax', 'as' => 'ajax.'], function () {
            Route::get('/employees', 'Ccms\AjaxController@employees')->name('employees');
            Route::get('/employee/{empId}', 'Ccms\AjaxController@employee')->name('employee');
            Route::get('/dept-name', 'Ccms\AjaxController@deptName')->name('dept-name');

        });

        // For News
        Route::get('/get-top-news', 'NewsController@getNews')->name('get-top-news');
        Route::get('/news-download/{id}', 'NewsController@downloadAttachment')->name('news-download');




    Route::get('/inventory-demand', 'Eqms\InventoryDemandController@index')->name('inventory-demand-index');
    Route::post('/inventory-demand-post', 'Eqms\InventoryDemandController@store')->name('inventory-demand-post');
    Route::get('/inventory-demand/{id}', 'Eqms\InventoryDemandController@edit')->name('inventory-demand-edit');
    Route::post('/inventory-demand/{id}', 'Eqms\InventoryDemandController@update')->name('inventory-demand-update');
//  Route::post('/inventory-demand-datatable', 'Eqms\InventoryDemandController@dataTableList')->name('inventory-demand-datatable');
    Route::post('/demanded-item-datatable', 'Eqms\InventoryDemandController@DemandedItemsList')->name('demanded-item-datatable');
});
