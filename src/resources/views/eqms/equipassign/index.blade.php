@extends('layouts.default')

@section('title')
    :: Equipment Assign
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

                    @include('eqms.equipassign.list')

                    @if(isset($mData))
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
                                          @if(isset($mData->eqr_id)) action="{{route('equip-request-approval-update',[$mData->eqr_id])}}"
                                          @endif method="post">
                                        @csrf
                                        @if (isset($mData->eqr_id))
                                            @method('PUT')
                                            <input type="hidden" id="eqr_id" name="eqr_id"
                                                   value="{{isset($mData->eqr_id) ? $mData->eqr_id : ''}}">
                                        @endif

                                        <h5 class="card-title">Assign Equipment</h5>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Request</label>
                                                <select class="custom-select select2 form-control" disabled
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
                                                <select class="custom-select select2 form-control" id="bo_id" disabled
                                                        @if(isset($mData->bo_id)) name="bo_id"
                                                        @else name="" @endif>
                                                    <option value="">Select One</option>
                                                    @foreach($boList as $value)
                                                        <option value="{{$value->bo_id}}"
                                                            {{isset($mData->bo_id) && $mData->bo_id == $value->bo_id ? 'selected' : ''}}
                                                        >{{$value->bo_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mt-1" id="hide2"
                                                 @if(isset($mData->ship_name)) style="display: block"
                                                 @else style="display: none" @endif>
                                                <label class="required">Ship Name</label>
                                                <input type="text" disabled
                                                       placeholder="Ship Name"
                                                       @if(isset($mData->ship_name)) name="ship_name"
                                                       @else name="" @endif
                                                       id="ship_name"
                                                       class="form-control"
                                                       value="{{isset($mData->ship_name) ? $mData->ship_name : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1 empId" id="hide3"
                                                 @if(isset($mData->req_emp_id)) style="display: block"
                                                 @else style="display: none" @endif>

                                                <label class="required">Requester :</label>
                                                <select class="custom-select select2 form-control req_emp_id" disabled
                                                        id="req_emp_id" @if(isset($mData->req_emp_id)) name="req_emp_id"
                                                        @else name="" @endif>
                                                    @if(isset($mData))
                                                        <option
                                                            value="{{$mData->req_emp_id}}">{{$mData->req_emp_code.' '.$mData->req_emp_name.''}}</option>
                                                    @endif
                                                </select>

                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Request Date:</label>
                                                <div class="input-group date" id="datetimepicker3"
                                                     data-target-input="nearest">
                                                    <input type="text" disabled
                                                           value="{{isset($mData->req_date) ? date('d-m-Y', strtotime($mData->req_date)) : ''}}"
                                                           class="form-control datetimepicker-input"
                                                           data-toggle="datetimepicker" data-target="#datetimepicker3"
                                                           id="req_date"
                                                           name="req_date"
                                                           autocomplete="off"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Request For Date:</label>
                                                <div class="input-group date" id="datetimepicker4"
                                                     data-target-input="nearest">
                                                    <input type="text" disabled
                                                           value="{{isset($mData->req_for_date) ? date('d-m-Y', strtotime($mData->req_for_date)) : ''}}"
                                                           class="form-control datetimepicker-input"
                                                           data-toggle="datetimepicker" data-target="#datetimepicker4"
                                                           id="req_for_date"
                                                           name="req_for_date"
                                                           autocomplete="off"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Work Type</label>
                                                <select class="custom-select select2 form-control" disabled
                                                        id="req_work_id"
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
                                                <input type="text" disabled
                                                       placeholder="A. P. No"
                                                       name="a_p_no"
                                                       class="form-control"
                                                       value="{{isset($mData->a_p_no) ? $mData->a_p_no : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Nothi No</label>
                                                <input type="text" disabled
                                                       placeholder="Nothi No"
                                                       name="nothi_no"
                                                       class="form-control"
                                                       value="{{isset($mData->nothi_no) ? $mData->nothi_no : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Nothi No Bangla</label>
                                                <input type="text" disabled
                                                       placeholder="Nothi No Bangla"
                                                       name="nothi_no_bn"
                                                       class="form-control"
                                                       value="{{isset($mData->nothi_no_bn) ? $mData->nothi_no_bn : ''}}"
                                                >
                                            </div>
                                        </div>

                                        <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                            <div class="col-sm-12 mt-1">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped table-bordered"
                                                           id="table-operator">
                                                        <thead>
                                                        <tr>
                                                            {{--<th role="columnheader" scope="col"
                                                                aria-colindex="1" class="text-center" width="1%">Action
                                                            </th>--}}
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Location
                                                                Type
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Location
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Container
                                                                20
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Container
                                                                40
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">Add
                                                                Equipment
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">
                                                        @if(!empty($mmData))
                                                            @foreach($mmData as $key=>$value)
                                                                <tr role="row">
                                                                    {{--<td aria-colindex="1" role="cell" class="text-center">
                                                                        <input type='checkbox' name='record'
                                                                               value="{{$value->erm_id.'+'.$value->eqr_id}}">
                                                                        <input type="hidden" name="tab_erm_id[]"
                                                                               value="{{$value->erm_id}}"
                                                                               class="erm_id">
                                                                    </td>--}}
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->location_type}}
                                                                        {{--<select class="select2 form-control"
                                                                                id="location_type_id_{{$key + 1}}"
                                                                                name="tab_location_type_id[]">
                                                                            <option value="">Select One</option>
                                                                            @if(isset($loctypList))
                                                                                @foreach($loctypList as $values)
                                                                                    <option
                                                                                        value="{{$values->location_type_id}}"
                                                                                        {{isset($value->location_type_id) && $value->location_type_id == $values->location_type_id ? 'selected' : ''}}
                                                                                    >{{$values->location_type}}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>--}}
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->location}}
                                                                        {{--<select class="select2 form-control"
                                                                                id="location_id_{{$key + 1}}"
                                                                                name="tab_location_id[]">
                                                                            <option value="">Select One</option>
                                                                            @if(isset($locationList))
                                                                                @foreach($locationList as $values)
                                                                                    <option value="{{$values->location_id}}"
                                                                                        {{isset($value->location_id) && $value->location_id == $values->location_id ? 'selected' : ''}}
                                                                                    >{{$values->location}}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>--}}
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->container_20}}
                                                                        {{--<input type="text" class="form-control" readonly
                                                                               name="tab_container_20[]"
                                                                               value="{{$value->container_20}}">--}}
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->container_40}}
                                                                        {{--<input type="text" class="form-control" readonly
                                                                               name="tab_container_40[]"
                                                                               value="{{$value->container_40}}">--}}
                                                                    </td>
                                                                    {{--<td style='text-align:center; vertical-align:middle'>
                                                                        <button type="button" onclick="getData({{$value->erm_id}},{{$value->eqr_id}})"
                                                                                class="btn btn-info show-receive-modal workflowBtn">Assign Equipment</button>
                                                                        --}}{{--<a onclick="getData({{$value->erm_id}},{{$value->eqr_id}})"
                                                                           href="javascript:void(0)"
                                                                           class="show-receive-modal workflowBtn"
                                                                           title="Add Detail"><i class="bx bx-show"
                                                                                                 data-toggle="tooltip"
                                                                                                 data-placement="top"></i></a>--}}{{--
                                                                    </td>--}}
                                                                    <td style='text-align:center; vertical-align:middle'>

{{--                                                                        @if($value->findings=='Y')--}}
{{--                                                                            <button type="button"--}}
{{--                                                                                    onclick="getData({{$value->erm_id}},{{$value->eqr_id}})"--}}
{{--                                                                                    class="btn btn-success show-receive-modal workflowBtn">--}}
{{--                                                                                Detail--}}
{{--                                                                            </button>--}}
{{--                                                                        @else--}}
                                                                            <button type="button"
                                                                                    onclick="getData({{$value->erm_id}},{{$value->eqr_id}})"
                                                                                    class="btn btn-info show-receive-modal workflowBtn">
                                                                                Detail
                                                                            </button>
{{--                                                                        @endif--}}
                                                                    </td>

                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                            {{--<div class="col-12 d-flex justify-content-start">

                                                <button type="button"
                                                        class="btn btn-primary mb-1 delete-row">
                                                    Delete
                                                </button>
                                            </div>--}}
                                        </fieldset>
                                        <div class="form-group mt-1">
                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                <div class="form-group">
                                                    @if(!isset($mData))
                                                        {{--<button id="boat-employee-save" type="submit"
                                                                class="btn btn-primary mr-1 mb-1">Save
                                                        </button>
                                                        <a type="reset" href="{{route("equipment-request-index")}}"
                                                           class="btn btn-light-secondary mb-1"> Reset</a>--}}
                                                    @else
                                                        {{--<button id="eq-req-approval" type="submit"
                                                                class="btn btn-primary mr-1 mb-1" onclick="chkData()">Approve
                                                        </button>--}}
                                                        <a type="reset" href="{{route("equip-assign-index")}}"
                                                           class="btn btn-light-secondary mb-1"> Back</a>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>

                                    </form>

                                    <div id="status-show" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-full">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title text-uppercase text-left">
                                                        Assign Equipment
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
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Location Type</label>
                                                                        <input type="text" disabled
                                                                               id="loc_typ"
                                                                               class="form-control"
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Location</label>
                                                                        <input type="text"
                                                                               id="loc" disabled
                                                                               class="form-control"
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="form-group">
                                                                        <label>Container 20</label>
                                                                        <input type="number" disabled
                                                                               id="cont_20"
                                                                               class="form-control"
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <div class="form-group">
                                                                        <label>Container 40</label>
                                                                        <input type="number" disabled
                                                                               id="cont_40"
                                                                               class="form-control"
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <label>Request Date:</label>
                                                                    <div class="input-group date" id="datetimepicker3"
                                                                         data-target-input="nearest">
                                                                        <input type="text" disabled
                                                                               value="{{isset($mData->req_date) ? date('d-m-Y', strtotime($mData->req_date)) : ''}}"
                                                                               class="form-control datetimepicker-input"
                                                                               data-toggle="datetimepicker"
                                                                               data-target="#datetimepicker3"
                                                                               id="req_date"
                                                                               name="req_date"
                                                                               autocomplete="off"
                                                                        />
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
                                                                                aria-colindex="2" class="text-center"
                                                                                width="7%">Equipment Type
                                                                            </th>
                                                                            <th role="columnheader" scope="col"
                                                                                aria-colindex="2" class="text-center"
                                                                                width="7%">Load Capacity
                                                                            </th>
                                                                            <th role="columnheader" scope="col"
                                                                                aria-colindex="2" class="text-center"
                                                                                width="7%">Requested Equipment
                                                                            </th>
                                                                            <th role="columnheader" scope="col"
                                                                                aria-colindex="2" class="text-center"
                                                                                width="7%">Approved Equipment
                                                                            </th>
                                                                            <th role="columnheader" scope="col"
                                                                                aria-colindex="2" class="text-center"
                                                                                width="7%">Supply Date
                                                                            </th>
                                                                            <th role="columnheader" scope="col"
                                                                                aria-colindex="2" class="text-center"
                                                                                width="10%">Equipment Assigned
                                                                            </th>
                                                                            <th role="columnheader" scope="col"
                                                                                aria-colindex="2" class="text-center"
                                                                                width="10%">Operator Assigned
                                                                            </th>
                                                                            <th role="columnheader" scope="col"
                                                                                aria-colindex="2" class="text-center"
                                                                                width="20%">Equipment
                                                                            </th>
                                                                            <th role="columnheader" scope="col"
                                                                                aria-colindex="2" class="text-center"
                                                                                width="20%">Operator
                                                                            </th>
                                                                        </tr>
                                                                        </thead>

                                                                        <tbody role="rowgroup">
                                                                        </tbody>
                                                                    </table>

                                                                </div>
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
                    @endif
                </div>
            </div>
            <!--/ form default repeater -->

        </section>
    </div>



@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function convertDate(inputFormat) {
            function pad(s) {
                return (s < 10) ? '0' + s : s;
            }

            var d = new Date(inputFormat)
            return [d.getFullYear(), pad(d.getMonth() + 1), pad(d.getDate())].join('-')
        }

        function getData(erm_id, eqr_id) {
            let myModal = $('#status-show');
            $("#dtl_eqr_id").val(eqr_id);
            $("#dtl_erm_id").val(erm_id);
            console.log(eqr_id + '+' + erm_id);
            $.ajax({
                url: APP_URL + '/get-eqp-req-dtl/' + eqr_id + '/' + erm_id,
                success: function (msgs) {
                    let markup = '';
                    let mst = msgs.equip_request_mst;
                    $("#loc_typ").val(mst.location_type);
                    $("#loc").val(mst.location);
                    $("#cont_20").val(mst.container_20);
                    $("#cont_40").val(mst.container_40);

                    let msg = msgs.equip_request_dtl; //console.log(msg)

                    $("#table-dtl > tbody").html("");
                    $.each(msg, function (i) {
                        let loadC = msg[i].load_capacity;
                        if (loadC === null) {
                            loadC = '';
                        }
                        let supplied_equip = msg[i].supplied_equip;
                        if (supplied_equip === null || supplied_equip == '') {
                            supplied_equip = '';
                        }
                        let requested_equip = msg[i].requested_equip;
                        if (requested_equip == null || requested_equip == '') {
                            requested_equip = '';
                        }
                        let equip_name = msg[i].equip_name;
                        if (equip_name == null || equip_name == '') {
                            equip_name = '';
                        }
                        let operator_name = msg[i].operator_name;
                        if (operator_name == null || operator_name == '') {
                            operator_name = '';
                        }
                        let supplied_date;
                        if (msg[i].supplied_date) {
                            supplied_date = convertDate(msg[i].supplied_date);
                        } else {
                            supplied_date = '';
                        }

                        markup += "<tr role='row'>" +
                            "<td aria-colindex='2' role='cell'>" + msg[i].equip_type + "</td>" +
                            "<td aria-colindex='2' role='cell'>" + loadC + "</td>" +
                            "<td aria-colindex='2' role='cell'>" + requested_equip + "</td>" +
                            "<td aria-colindex='2' role='cell'>" + supplied_equip +
                            "<input type='hidden' name='tab_eqr_id[]' value='" + msg[i].eqr_id + "'>" +
                            "<input type='hidden' name='tab_erm_id[]' value='" + msg[i].erm_id + "'>" +
                            "<input type='hidden' name='tab_erd_id[]' value='" + msg[i].erd_id + "'>" +
                            "<input type='hidden' name='tab_equip_type_id[]' value='" + msg[i].equip_type_id + "'>" +
                            "<input type='hidden' name='tab_load_capacity_id[]' value='" + msg[i].load_capacity_id + "'>" +
                            "<input type='hidden' name='tab_supplied_equip[]' value='" + supplied_equip + "'>" +
                            "<td aria-colindex='2' role='cell'>" + supplied_date + "</td>" +
                            "<td aria-colindex='2' role='cell'>" + equip_name + "</td>" +
                            "<td aria-colindex='2' role='cell'>" + operator_name + "</td>" +
                            "<td aria-colindex='2' role='cell'><select class='custom-select form-control select2' required id='equip_type_id' name='dtl_equip_type_id[]'>" + msg[i].eqip_dropdown + "</select></td>" +
                            "<td aria-colindex='2' role='cell'><select class='custom-select form-control select2' required id='r_d_id' name='dtl_r_d_id[]'>" + msg[i].emp_dropdown + "</select></td>" +
                            "</tr>";
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
                $("#req_emp_id").val('').trigger('change');
                $("#ship_name").val('');
            } else if (requester_id == '1') {
                $('#hide1').css("display", "block");
                $('#hide2').css("display", "block");
                $('#hide3').hide();
                $('#bo_id').prop('required', true);
                $('#ship_name').prop('required', true);
                $("#bo_id").val('').trigger('change');
                $("#req_emp_id").val('').trigger('change');
                $("#ship_name").val('');
            } else {
                $('#hide1').css("display", "none");
                $('#hide2').css("display", "none");
                $('#hide3').hide();
                $("#bo_id").val('').trigger('change');
                $("#req_emp_id").val('').trigger('change');
                $("#ship_name").val('');
            }
        });

        $('.req_emp_id').select2({
            placeholder: "Select one",
            ajax: {
                url: APP_URL + '/get-employee',
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
            var url = '{{route('equip-assign-datatable')}}';
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
                    {data: 'nothi_no', name: 'nothi_no', searchable: true},
                    {data: 'req_date', name: 'req_date', searchable: false},
                    {data: 'req_for_date', name: 'req_for_date', searchable: false},
                    {data: 'req_status_update_date', name: 'req_status_update_date', searchable: false},
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

            var eqr_id = '{{isset($mData->eqr_id) ? $mData->eqr_id : ''}}';

            if (eqr_id) {
                $("html, body").animate({scrollTop: $(document).height()}, 1000);
            }
            $("#workflow_form").attr('action', '{{ route('equip-assign-dtl-post') }}');
            datePicker('#datetimepicker3');
            datePicker('#datetimepicker4');
            eqReqList();
        });

    </script>

@endsection

