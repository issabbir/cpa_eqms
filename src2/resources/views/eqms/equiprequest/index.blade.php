@extends('layouts.default')

@section('title')
    :: Equipment Request
@endsection

@section('header-style')
    <!--Load custom style link or css-->
    <style>
        .empId span {
            width: 100% !important;
        }

        .empId span b {
            margin-left: 160px !important;
        }
    </style>
@endsection
@section('content')

    <div class="content-body">
        <section id="form-repeater-wrapper">
            <!-- form default repeater -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if(Session::has('message'))
                            <div
                                class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                                role="alert">
                                {{ Session::get('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="card-content">
                            <div class="card-body">
                                <form enctype="multipart/form-data"
                                      @if(isset($mData->eqr_id)) action="{{route('equipment-request-update',[$mData->eqr_id])}}"
                                      @else action="{{route('equipment-request-post')}}" @endif method="post">
                                    @csrf
                                    @if (isset($mData->eqr_id))
                                        @method('PUT')
                                        <input type="hidden" id="eqr_id" name="eqr_id"
                                               value="{{isset($mData->eqr_id) ? $mData->eqr_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Equipment Request</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label>Equipment Request No</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       value="{{isset($mData->equip_req_no) ? $mData->equip_req_no : 'ER'.$gen_uniq_id}}"
                                                       class="form-control"
                                                       id="equip_req_no"
                                                       name="equip_req_no"
                                                       readonly
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Request By</label>
                                            <select class="custom-select select2 form-control" required
                                                    id="requester_id" name="requester_id">
                                                <option value="">Select One</option>
                                                @foreach($reqList as $value)
                                                    <option value="{{$value->requester_id}}"
                                                        {{isset($mData->requester_id) && $mData->requester_id == $value->requester_id ? 'selected' : ''}}
                                                    >{{$value->requester}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1" id="hide1"
                                             @if(isset($mData->bo_id)) style="display: block"
                                             @else style="display: none" @endif>
                                            <label class="required">Berth Operator</label>
                                            <select class="custom-select select2 form-control" id="bo_id"
                                                    name="bo_id">
                                                <option value="">Select One</option>
                                                @foreach($boList as $value)
                                                    <option value="{{$value->bo_id}}"
                                                        {{isset($mData->bo_id) && $mData->bo_id == $value->bo_id ? 'selected' : ''}}
                                                    >{{$value->bo_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1" id="hide2"
                                             @if(isset($mData->bo_id)) style="display: block"
                                             @else style="display: none" @endif>
                                            <label>Ship Name</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Ship Name"
                                                   name="ship_name"
                                                   id="ship_name"
                                                   class="form-control"
                                                   value="{{isset($mData->ship_name) ? $mData->ship_name : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1 empId" id="hide3"
                                             @if(isset($mData->req_emp_id)) style="display: block"
                                             @else style="display: none" @endif>

                                            <label class="required">Requester</label>
                                            <select class="custom-select select2 form-control req_emp_id"
                                                    id="req_emp_id" name="req_emp_id">
                                                @if(isset($mData))
                                                    <option
                                                        value="{{$mData->req_emp_id}}">{{$mData->req_emp_code.' '.$mData->req_emp_name.''}}</option>
                                                @endif
                                            </select>

                                        </div>
                                        {{--<div class="col-md-3 mt-1">
                                            <label>Request Date</label>
                                            <div class="input-group date" id="datetimepicker3"
                                                 data-target-input="nearest">
                                                <input type="text"
                                                       value="{{isset($mData->req_date) ? date('d-m-Y', strtotime($mData->req_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker3"
                                                       id="req_date"
                                                       name="req_date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>--}}
                                        <div class="col-md-3 mt-1 mb-1">
                                            <label for="req_date " class="required">Request Date</label>
                                            <div class="input-group date datePiker">
                                                <input type="text" @if(isset($data->operation_date)) disabled @endif
                                                autocomplete="off"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker"
                                                       id="req_date"
                                                       data-target="#req_date"
                                                       name="req_date"
                                                       data-predefined-date="{{old('req_date',isset($mData->req_date) ? date('d-m-Y', strtotime($mData->req_date)) : '')}}"
                                                >
                                                @if(isset($mData->req_date))
                                                    <input type="hidden" name="req_date"
                                                           value="{{old('req_date',isset($mData->req_date) ? date('d-m-Y', strtotime($mData->req_date)) : '')}}">
                                                @endif
                                                <div class="input-group-append" data-target="#req_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i
                                                            class="bx bxs-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                        {{--<div class="col-md-3 mt-1">
                                            <label class="required">Request For Date</label>
                                            <div class="input-group date" id="datetimepicker4"
                                                 data-target-input="nearest">
                                                <input type="text" required
                                                       value="{{isset($mData->req_for_date) ? date('d-m-Y', strtotime($mData->req_for_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker4"
                                                       id="req_for_date"
                                                       name="req_for_date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>--}}

                                        <div class="col-md-3 mt-1">
                                            <label for="contract_date" class="required">Request For Date</label>
                                            <div class="input-group date datePiker">
                                                <input type="text" required
                                                       autocomplete="off"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker"
                                                       id="req_for_date"
                                                       data-target="#req_for_date"
                                                       name="req_for_date"
                                                       data-predefined-date="{{old('req_for_date',isset($mData->req_for_date) ? date('d-m-Y', strtotime($mData->req_for_date)) : '')}}"
                                                >
                                                @if(isset($mData->req_for_date))
                                                    <input type="hidden" name="req_for_date"
                                                           value="{{old('req_for_date',isset($mData->req_for_date) ? date('d-m-Y', strtotime($mData->req_for_date)) : '')}}">
                                                @endif
                                                <div class="input-group-append" data-target="#req_for_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Work Type</label>
                                            <select class="custom-select select2 form-control" required id="req_work_id"
                                                    name="req_work_id">
                                                <option value="">Select One</option>
                                                @foreach($wtList as $value)
                                                    <option value="{{$value->work_type_id}}"
                                                        {{isset($mData->req_work_id) && $mData->req_work_id == $value->work_type_id ? 'selected' : ''}}
                                                    >{{$value->work_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>A. P. No</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="A. P. No"
                                                   name="a_p_no"
                                                   class="form-control"
                                                   value="{{isset($mData->a_p_no) ? $mData->a_p_no : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="50"
                                            >
                                        </div>
                                    </div>

                                    <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Location Type</label>
                                                    <select class="select2 form-control pl-0 pr-0"
                                                            id="location_type_id">
                                                        <option value="">Select One</option>
                                                        @if(isset($loctypList))
                                                            @foreach($loctypList as $value)
                                                                <option value="{{$value->location_type_id}}">
                                                                    {{$value->location_type}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Location</label>
                                                    <select class="select2 form-control pl-0 pr-0 location_id"
                                                            id="location_id">
                                                        <option value="">Select One</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2" id="hide4">
                                                <label>Container 20</label>
                                                <input type="number"
                                                       id="container_20"
                                                       class="form-control"
                                                >
                                            </div>
                                            <div class="col-sm-2" id="hide5">
                                                <label>Container 40</label>
                                                <input type="number"
                                                       id="container_40"
                                                       class="form-control"
                                                >
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="seat_to1">&nbsp;</label><br/>
                                                    <button type="button" id="append"
                                                            class="btn btn-primary mb-1 add-row">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 mt-1">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped table-bordered"
                                                       id="table-operator">
                                                    <thead>
                                                    <tr>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="1" class="text-center" width="1%">Action
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">Location
                                                            Type
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">Location
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">Container
                                                            20
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">Container
                                                            40
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">Add
                                                            Request Detail
                                                        </th>
                                                    </tr>
                                                    </thead>

                                                    <tbody role="rowgroup" id="comp_body">
                                                    @if(!empty($mmData))
                                                        @foreach($mmData as $key=>$value)
                                                            <tr role="row">
                                                                <td aria-colindex="1" role="cell" class="text-center">
                                                                    <input type='checkbox' name='record'
                                                                           value="{{$value->erm_id.'+'.$value->eqr_id}}">
                                                                    <input type="hidden" name="tab_erm_id[]"
                                                                           value="{{$value->erm_id}}"
                                                                           class="erm_id">
                                                                </td>
                                                                <td aria-colindex="7"
                                                                    role="cell">{{$value->location_type}}
                                                                </td>
                                                                <td aria-colindex="7" role="cell">{{$value->location}}
                                                                </td>
                                                                <td aria-colindex="7" role="cell">
                                                                    <input type="text" class="form-control"
                                                                           @if($value->location_type_id =='2') readonly
                                                                           @endif
                                                                           name="tab_container_20[]"
                                                                           value="{{$value->container_20}}">
                                                                </td>
                                                                <td aria-colindex="7" role="cell">
                                                                    <input type="text" class="form-control"
                                                                           @if($value->location_type_id =='2') readonly
                                                                           @endif
                                                                           name="tab_container_40[]"
                                                                           value="{{$value->container_40}}">
                                                                </td>
                                                                <td style='text-align:center; vertical-align:middle'>
                                                                    @if($value->findings=='Y')
                                                                        <button type="button"
                                                                                onclick="getData({{$value->erm_id}},{{$value->eqr_id}})"
                                                                                class="btn btn-success show-receive-modal workflowBtn">
                                                                            Detail
                                                                        </button>
                                                                    @else
                                                                        <button type="button"
                                                                                onclick="getData({{$value->erm_id}},{{$value->eqr_id}})"
                                                                                class="btn btn-info show-receive-modal workflowBtn">
                                                                            Add Detail
                                                                        </button>
                                                                    @endif
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-start">

                                            <button type="button"
                                                    class="btn btn-primary mb-1 delete-row">
                                                Delete
                                            </button>
                                        </div>
                                    </fieldset>

                                    @if(isset($mData))
                                        @if($mData->submit_approval=='N' && $pending<=0)
                                            <div class="col-md-12">
                                                <fieldset class="border p-2 mb-2">
                                                    <legend class="w-auto">&nbsp;Workflow Step For Requisition Approval
                                                        &nbsp;
                                                    </legend>
                                                    @include('eqms.requestapproval.workflow_step')
                                                </fieldset>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                @if(!isset($mData))
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Save
                                                    </button>
                                                    <a type="reset" href="{{route("equipment-request-index")}}"
                                                       class="btn btn-light-secondary mb-1"> Reset</a>
                                                @else
                                                    @if($mData->submit_approval=='N' && $pending<=0)
                                                        <button id="eq-req-approval" type="submit" name="approve"
                                                                value="1"
                                                                class="btn btn-primary mr-1 mb-1">
                                                            Submit For Approval
                                                        </button>
                                                    @endif
                                                    <button id="boat-employee-save" type="submit" name="update" value="1"
                                                            class="btn btn-success mr-1 mb-1">Update
                                                    </button>
                                                    <a type="reset" href="{{route("equipment-request-index")}}"
                                                       class="btn btn-light-secondary mb-1"> Back</a>
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                </form>

                                <div id="status-show" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title text-uppercase text-left">
                                                    Add Equipment Detail
                                                </h4>
                                                <button class="close" type="button" data-dismiss="modal"
                                                        area-hidden="true">
                                                    &times;
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="workflow_form" method="post">
                                                    {!! csrf_field() !!}
                                                    <fieldset class="border p-1 mt-2 mb-1 col-sm-12"
                                                              id="detail_data">
                                                        <input type="hidden" id="dtl_eqr_id" name="dtl_eqr_id">
                                                        <input type="hidden" id="dtl_erm_id" name="dtl_erm_id">
                                                        <div class="row">
                                                            <div class="col-sm-2">
                                                                <div class="form-group">
                                                                    <label>Equipment Type</label>
                                                                    <select class="select2 form-control"
                                                                            id="equip_type_id">
                                                                        <option value="">Select One</option>
                                                                        @if(isset($eqptypList))
                                                                            @foreach($eqptypList as $value)
                                                                                <option
                                                                                    value="{{$value->equip_type_id}}">
                                                                                    {{$value->equip_type}}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2" id="hide6">
                                                                <div class="form-group">
                                                                    <label>Load Capacity</label>
                                                                    <select
                                                                        class="select2 form-control pl-0 pr-0 load_capacity_id"
                                                                        id="load_capacity_id">
                                                                        <option value="">Select One</option>
                                                                        @if(isset($ldcpctList))
                                                                            @foreach($ldcpctList as $value)
                                                                                <option
                                                                                    value="{{$value->load_capacity_id}}">
                                                                                    {{$value->load_capacity}}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <label>Requested Equipment</label>
                                                                <input type="number"
                                                                       id="requested_equip"
                                                                       class="form-control"
                                                                >
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <div class="form-group">
                                                                    <label for="seat_to1">&nbsp;</label><br/>
                                                                    <button type="button" id="append"
                                                                            class="btn btn-primary mb-1 add-row-dtl">
                                                                        ADD
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 mt-1">
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table table-sm table-striped table-bordered"
                                                                    id="table-dtl">
                                                                    <thead>
                                                                    <tr>
                                                                        <th role="columnheader" scope="col"
                                                                            aria-colindex="1" class="text-center"
                                                                            width="1%">Action
                                                                        </th>
                                                                        <th role="columnheader" scope="col"
                                                                            aria-colindex="2" class="text-center"
                                                                            width="10%">Equipment Type
                                                                        </th>
                                                                        <th role="columnheader" scope="col"
                                                                            aria-colindex="2" class="text-center"
                                                                            width="10%">Load Capacity
                                                                        </th>
                                                                        <th role="columnheader" scope="col"
                                                                            aria-colindex="2" class="text-center"
                                                                            width="10%">Requested Equipment
                                                                        </th>
                                                                    </tr>
                                                                    </thead>

                                                                    <tbody role="rowgroup">
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </div>
                                                        <div class="col-12 d-flex justify-content-start">

                                                            <button type="button"
                                                                    class="btn btn-primary mb-1 delete-row-dtl">
                                                                Delete
                                                            </button>
                                                        </div>
                                                        <div class="form-group mt-1">
                                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                                <div class="form-group">
                                                                    <button id="save-info" type="submit"
                                                                            class="btn btn-primary mr-1 mb-1">Save
                                                                    </button>
                                                                    <button class="btn btn-primary mr-1 mb-1"
                                                                            type="button" data-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ form default repeater -->

        </section>
    </div>

    @include('eqms.equiprequest.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        function getData(erm_id, eqr_id) {
            let myModal = $('#status-show');
            $("#dtl_eqr_id").val(eqr_id);
            $("#dtl_erm_id").val(erm_id);

            $.ajax({
                url: APP_URL + '/get-eqp-req-dtl-data/' + eqr_id + '/' + erm_id,
                success: function (msg) {
                    let markup = '';
                    $("#table-dtl > tbody").html("");
                    $.each(msg, function (i) {
                        let loadC = msg[i].load_capacity;
                        let requested_equip = msg[i].requested_equip;
                        if (loadC === null) {
                            loadC = '';
                        }
                        if (requested_equip === null) {
                            requested_equip = '';
                        }
                        markup += "<tr role='row'>" +
                            "<td aria-colindex='1' role='cell' class='text-center'>" +
                            "<input type='checkbox' name='record' value='" + msg[i].eqr_id + "+" + msg[i].erm_id + "+" + msg[i].erd_id + "'>" +
                            "<input type='hidden' name='tab_equip_type_id[]' value='" + msg[i].equip_type_id + "'>" +
                            "<input type='hidden' name='tab_load_capacity_id[]' value='" + msg[i].load_capacity_id + "'>" +
                            "</td><td aria-colindex='2' role='cell'>" + msg[i].equip_type + "</td><td aria-colindex='2' role='cell'>" + loadC + "</td><td aria-colindex='2' role='cell'><input type='text' class='form-control' name='tab_requested_equip[]' value='" + requested_equip + "'></td></tr>";

                    });
                    $("#table-dtl tbody").html(markup);
                }
            });
            myModal.modal({show: true});
            return false;
        }

        $("#equip_type_id").on('change', function (e) {
            let equip_type_id = $(this).val();
            if (equip_type_id == '3' || equip_type_id == '4') {
                $('#hide6').css("display", "none");
                $("#load_capacity_id").val('').trigger('change');
            } else {
                $('#hide6').css("display", "block");
                $("#load_capacity_id").val('').trigger('change');
            }
        });

        $("#location_type_id").select2().on('change', function (e) {
            let location_type_id = $(this).val();

            if (location_type_id == '2') {
                $('#hide4').css("display", "none");
                $('#hide5').css("display", "none");
                $("#container_20").val('');
                $("#container_40").val('');
            } else {
                $('#hide4').css("display", "block");
                $('#hide5').css("display", "block");
                $("#container_20").val('');
                $("#container_40").val('');
            }

            $.ajax({
                type: 'get',
                url: '/get-location',
                data: {location_type_id: location_type_id},
                success: function (msg) {
                    $("#location_id").html(msg);
                }
            });
        });

        $("#requester_id").select2().on('change', function (e) {
            let requester_id = $(this).val();
            if (requester_id == '2') {
                $('#hide1').css("display", "none");
                $('#hide2').css("display", "none");
                $('#hide3').show();
                $('#req_emp_id').prop('required', true);
                $("#bo_id").val('').trigger('change');
                $('#bo_id').prop('required', false);
                $("#req_emp_id").val('').trigger('change');
                $("#ship_name").val('');
            } else if (requester_id == '1') {
                $('#hide1').css("display", "block");
                $('#hide2').css("display", "block");
                $('#hide3').hide();
                $('#bo_id').prop('required', true);
                $('#req_emp_id').prop('required', false);
                //$('#ship_name').prop('required', true);
                $("#bo_id").val('').trigger('change');
                $("#req_emp_id").val('').trigger('change');
                $("#ship_name").val('');
            } else {
                $('#hide1').css("display", "none");
                $('#hide2').css("display", "none");
                $('#hide3').hide();
                $("#bo_id").val('').trigger('change');
                $('#bo_id').prop('required', false);
                $('#req_emp_id').prop('required', false);
                $("#req_emp_id").val('').trigger('change');
                $("#ship_name").val('');
            }
        });

        $('.req_emp_id').select2({
            placeholder: "Select one",
            ajax: {
                url: APP_URL + '/get-employee-traffic',
                data: function (params) {
                    if (params.term) {
                        if (params.term.trim().length < 1) {
                            return false;
                        }
                    } else {
                        return false;
                    }

                    return params;
                },
                dataType: 'json',
                processResults: function (data) {
                    var formattedResults = $.map(data, function (obj, idx) {
                        obj.id = obj.emp_id;
                        obj.text = obj.emp_code + '-' + obj.emp_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        function eqReqList() {
            var url = '{{route('equipment-request-datatable')}}';
            var oTable = $('#searchResultTable').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: url,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'equip_req_no', name: 'equip_req_no', searchable: true},
                    {data: 'requester', name: 'requester', searchable: true},
                    {data: 'req_work', name: 'req_work', searchable: true},
                    /*{data: 'nothi_no', name: 'nothi_no', searchable: true},*/
                    {data: 'req_date', name: 'req_date', searchable: false},
                    {data: 'req_for_date', name: 'req_for_date', searchable: false},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);

            $("#workflow_form").attr('action', '{{ route('equipment-dtl-post') }}');
            //minSysDatePicker('#datetimepicker3');
            //datePicker('#datetimepicker3');
            //datePicker('#datetimepicker4');
            eqReqList();
            $('#req_for_date').prop('disabled', true);
        });


        let req_date = '';
        $('#req_date').on("change.datetimepicker", function (e) {
            req_date = $(this).val();
            if (req_date) {
                $('#req_for_date').prop('disabled', false);
            } else {
                $('#req_for_date').prop('disabled', true);
            }
            dateRangePicker('#req_date', '#req_for_date', req_date);
        });
        dateRangePicker('#req_date', '#req_for_date');

        function dateRangePicker(Elem1, Elem2, minDate = null, maxDate = null) {
            let minElem = $(Elem2);
            let maxElem = $(Elem1);

            // console.log(maxDate)
            minElem.datetimepicker({
                format: 'DD-MM-YYYY',
                ignoreReadonly: true,
                autoclose: true,
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
                icons: {
                    time: 'bx bx-time',
                    date: 'bx bxs-calendar',
                    up: 'bx bx-up-arrow-alt',
                    down: 'bx bx-down-arrow-alt',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right',
                    today: 'bx bxs-calendar-check',
                    clear: 'bx bx-trash',
                    close: 'bx bx-window-close'
                }
            });
            maxElem.datetimepicker({
                useCurrent: false,
                format: 'DD-MM-YYYY',
                ignoreReadonly: true,
                autoclose: true,
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
                icons: {
                    time: 'bx bx-time',
                    date: 'bx bxs-calendar',
                    up: 'bx bx-up-arrow-alt',
                    down: 'bx bx-down-arrow-alt',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right',
                    today: 'bx bxs-calendar-check',
                    clear: 'bx bx-trash',
                    close: 'bx bx-window-close'
                }
            });
            // minElem.on("change.datetimepicker", function (e) {
            //     maxElem.datetimepicker('minDate', e.date);
            // });

            if (minDate) {
                minElem.datetimepicker('minDate', minDate);
                // $(Elem1).datetimepicker('minDate',  moment("DD-MM-YYYY"));
            } else {
                maxElem.on("change.datetimepicker", function (e) {
                    minElem.datetimepicker('minDate', e.date);
                });
                // minElem.datetimepicker('minDate', new Date());
            }
            // $(Elem2).datetimepicker('minDate', new Date());
            // minElem.datetimepicker('maxDate', e.date);

            let preDefinedDateMin = minElem.attr('data-predefined-date');
            let preDefinedDateMax = maxElem.attr('data-predefined-date');
            console.log(preDefinedDateMin);

            if (preDefinedDateMin) {
                let preDefinedDateMomentFormat = moment(preDefinedDateMin, "DD-MM-YYYY").format("DD-MM-YYYY");
                minElem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
            }

            if (preDefinedDateMax) {
                let preDefinedDateMomentFormat = moment(preDefinedDateMax, "DD-MM-YYYY").format("DD-MM-YYYY");
                maxElem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
            }

        }


        function convertDate(inputFormat) {
            function pad(s) {
                return (s < 10) ? '0' + s : s;
            }

            var d = new Date(inputFormat)
            return [d.getFullYear(), pad(d.getDate()), pad(d.getMonth() + 1)].join('-')
        }

        function customDateChk(getId, maxDate) {
            $(getId).datetimepicker({

                format: 'DD-MM-YYYY',
                minDate: new Date(),
                maxDate: convertDate(maxDate),
                useCurrent: false,
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
                // format: 'L',
                icons: {
                    time: 'bx bx-time',
                    date: 'bx bxs-calendar',
                    up: 'bx bx-up-arrow-alt',
                    down: 'bx bx-down-arrow-alt',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right',
                    today: 'bx bxs-calendar-check',
                    clear: 'bx bx-trash',
                    close: 'bx bx-window-close'
                }
            });
        }

        function minSysDatePicker(getId) {
            $(getId).datetimepicker({

                format: 'DD-MM-YYYY',
                minDate: new Date(),
                useCurrent: false,
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
                },
                // format: 'L',
                icons: {
                    time: 'bx bx-time',
                    date: 'bx bxs-calendar',
                    up: 'bx bx-up-arrow-alt',
                    down: 'bx bx-down-arrow-alt',
                    previous: 'bx bx-chevron-left',
                    next: 'bx bx-chevron-right',
                    today: 'bx bxs-calendar-check',
                    clear: 'bx bx-trash',
                    close: 'bx bx-window-close'
                }
            });
        }

        $(".add-row").click(function () {
            let location_type_id = $("#location_type_id option:selected").val();
            let location_type = $("#location_type_id option:selected").text();
            let location_id = $("#location_id option:selected").val();
            let location = $("#location_id option:selected").text();
            let equip_type_id = $("#equip_type_id option:selected").val();
            let equip_type = $("#equip_type_id option:selected").text();
            let load_capacity_id = $("#load_capacity_id option:selected").val();
            let load_capacity = $("#load_capacity_id option:selected").text();

            if (location_type_id == '') {
                location_type = '--';
            }

            if (location_id == '') {
                location = '--';
            }

            if (equip_type_id == '') {
                equip_type = '--';
            }

            if (load_capacity_id == '') {
                load_capacity = '--';
            }

            let container_20 = $("#container_20").val();
            let container_40 = $("#container_40").val();
            let requested_equip = $("#requested_equip").val();

            if (location_type_id == '') {
                Swal.fire(
                    'Select Location Type.',
                    '',
                    'error'
                )
            } else if (location_id == '') {
                Swal.fire(
                    'Select Location.',
                    '',
                    'error'
                )
            } else {
                let markup = "<tr role='row'>" +
                    "<td aria-colindex='1' role='cell' class='text-center'>" +
                    "<input type='checkbox' name='record' value='" + "" + "+" + "" + "'>" +
                    "<input type='hidden' name='tab_erm_id[]' value=''>" +
                    "<input type='hidden' name='tab_location_type_id[]' value='" + location_type_id + "'>" +
                    "<input type='hidden' name='tab_location_id[]' value='" + location_id + "'>" +
                    "<input type='hidden' name='tab_container_20[]' value='" + container_20 + "'>" +
                    "<input type='hidden' name='tab_container_40[]' value='" + container_40 + "'>" +
                    "</td><td aria-colindex='2' role='cell'>" + location_type + "</td><td aria-colindex='2' role='cell'>" + location + "</td><td aria-colindex='2' role='cell'>" + container_20 + "</td><td aria-colindex='2' role='cell'>" + container_40 + "</td><td aria-colindex='2' role='cell'></td></tr>";
                $("#container_20").val('');
                $("#container_40").val('');
                $("#location_type_id").val('').trigger('change');
                $("#location_id").val('').trigger('change');
                $("#table-operator tbody").append(markup);
            }

        });

        $(".delete-row").click(function () {
            let arr_stuff = [];
            let erm_id = [];
            let eqr_id = [];
            $(':checkbox:checked').each(function (i) {
                arr_stuff[i] = $(this).val();
                let sd = arr_stuff[i].split('+');
                if (sd[0]) {
                    erm_id.push(sd[0]);
                    eqr_id.push(sd[1]);
                }
            });

            if (erm_id.length != 0) {
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'GET',
                            url: '/eq-mst-data-remove',
                            data: {erm_id: erm_id, eqr_id: eqr_id},
                            success: function (msg) {
                                if (msg == 0) {
                                    Swal.fire({
                                        title: 'Something Went Wrong!!.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                    //return false;
                                } else {
                                    Swal.fire({
                                        title: 'Entry Successfully Deleted!',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(function () {
                                        $('td input:checked').closest('tr').remove();
                                    });
                                }
                            }
                        });
                    }
                });
            } else {
                /*Swal.fire({
                    title: 'Entry Successfully Deleted!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function () {
                    $('td input:checked').closest('tr').remove();
                });*/
                $('td input:checked').closest('tr').remove();
            }
        });

        $(".add-row-dtl").click(function () {
            let eqr_id = $("#dtl_eqr_id").val();
            let erm_id = $("#dtl_erm_id").val();
            let equip_type_id = $("#equip_type_id option:selected").val();
            let equip_type = $("#equip_type_id option:selected").text();
            let load_capacity_id = $("#load_capacity_id option:selected").val();
            let load_capacity = $("#load_capacity_id option:selected").text();

            if (load_capacity_id == '') {
                load_capacity = '';
            }

            let requested_equip = $("#requested_equip").val();

            if (equip_type_id == '' || requested_equip == '') {
                Swal.fire(
                    'Fill required value.',
                    '',
                    'error'
                )
            } else {
                let markup = "<tr role='row'>" +
                    "<td aria-colindex='1' role='cell' class='text-center'>" +
                    "<input type='checkbox' name='record' value='" + eqr_id + "+" + erm_id + "+" + "" + "'>" +
                    "<input type='hidden' name='tab_equip_type_id[]' value='" + equip_type_id + "'>" +
                    "<input type='hidden' name='tab_load_capacity_id[]' value='" + load_capacity_id + "'>" +
                    "<input type='hidden' name='tab_requested_equip[]' value='" + requested_equip + "'>" +
                    "</td><td aria-colindex='2' role='cell'>" + equip_type + "</td><td aria-colindex='2' role='cell'>" + load_capacity + "</td><td aria-colindex='2' role='cell'>" + requested_equip + "</td></tr>";
                $("#requested_equip").val('');
                $("#equip_type_id").val('').trigger('change');
                $("#load_capacity_id").val('').trigger('change');
                $("#table-dtl tbody").append(markup);
            }

        });

        $(".delete-row-dtl").click(function () {
            let arr_stuff = [];
            let dtl_id = [];
            $(':checkbox:checked').each(function (i) {
                arr_stuff[i] = $(this).val();
                let sd = arr_stuff[i].split('+');
                //dtl_id = sd[2];
                //operator_id.push(sd[0]);
                if (sd[2]) {
                    dtl_id.push(sd[2]);
                }
            });

            if (dtl_id.length != 0) {
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'GET',
                            url: '/eq-dtl-data-remove',
                            data: {dtl_id: dtl_id},
                            success: function (msg) {
                                if (msg == 0) {
                                    Swal.fire({
                                        title: 'Something Went Wrong!!.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                    //return false;
                                } else {
                                    Swal.fire({
                                        title: 'Entry Successfully Deleted!',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(function () {
                                        $('td input:checked').closest('tr').remove();
                                    });
                                }
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Entry Successfully Deleted!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function () {
                    $('td input:checked').closest('tr').remove();
                });
            }
        });


    </script>

@endsection

