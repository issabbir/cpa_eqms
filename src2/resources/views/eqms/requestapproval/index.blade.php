@extends('layouts.default')

@section('title')
    :: Equipment Request Approval
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

                    @include('eqms.requestapproval.list')

                    @if(isset($mData))
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <form enctype="multipart/form-data"
                                          @if(isset($mData->eqr_id)) action="{{route('equip-request-approval-update',[$mData->eqr_id])}}"
                                          @endif method="post" onsubmit="return chkData()">
                                        @csrf
                                        @if (isset($mData->eqr_id))
                                            @method('PUT')
                                            <input type="hidden" id="eqr_id" name="eqr_id"
                                                   value="{{isset($mData->eqr_id) ? $mData->eqr_id : ''}}">
                                        @endif

                                        <h5 class="card-title">Equipment Request Approval</h5>
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
                                                <input type="text" required
                                                       placeholder="Nothi No"
                                                       name="nothi_no" autocomplete="off"
                                                       class="form-control"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="500"
                                                       value="{{isset($mData->nothi_no) ? $mData->nothi_no : ''}}"
                                                       @if(isset($mData->nothi_no)) readonly @endif
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Nothi No Bangla</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Nothi No Bangla"
                                                       name="nothi_no_bn"
                                                       class="form-control"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="3000"
                                                       value="{{isset($mData->nothi_no_bn) ? $mData->nothi_no_bn : ''}}"
                                                       @if(isset($mData->nothi_no_bn)) readonly @endif
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
                                                                Request Detail
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">
                                                        @if(!empty($mmData))
                                                            @foreach($mmData as $key=>$value)
                                                                <tr role="row">
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->location_type}}
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->location}}
                                                                    </td>
                                                                    <td aria-colindex="7" role="cell">
                                                                        <input type="text" class="form-control" readonly
                                                                               name="tab_container_20[]"
                                                                               value="{{$value->container_20}}">
                                                                    </td>
                                                                    <td aria-colindex="7" role="cell">
                                                                        <input type="text" class="form-control" readonly
                                                                               name="tab_container_40[]"
                                                                               value="{{$value->container_40}}">
                                                                    </td>
                                                                    {{--<td style='text-align:center; vertical-align:middle'>
                                                                        <button type="button" onclick="getData({{$value->erm_id}},{{$value->eqr_id}})"
                                                                                class="btn btn-info show-receive-modal workflowBtn">Detail</button>
                                                                    </td>--}}
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
                                            {{--<div class="col-12 d-flex justify-content-start">

                                                 <button type="button"
                                                        class="btn btn-primary mb-1 delete-row">
                                                    Delete
                                                </button>
                                            </div>--}}
                                        </fieldset>

                                        <fieldset>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @if(count($workflows) > 0)
                                                @include('eqms.requestapproval.workflow')
                                            @endif
                                        </fieldset>

                                        <div class="form-group mt-1">
                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                <div class="form-group">
                                                    @if($mData->nothi_no==null)
                                                        <button id="eq-req-approval" type="submit" name="update_val"
                                                                value="1"
                                                                class="btn btn btn-dark shadow mr-1 mb-1 btn-primary"
                                                                onclick="chkData()">
                                                            Update
                                                        </button>
                                                    @endif
                                                    @if(isset($approvalData))
                                                        @if($approvalData->approval_status_id==2 && $mData->nothi_no!=null && $curr_data->approval_status_id!=1)
                                                            <button
                                                                class="btn btn btn-success shadow mr-1 mb-1 btn-primary rejectReq"
                                                                style="color: white"
                                                                onclick="commentWin({{$value->eqr_id}},{{$approvalData->approval_info_id}},'F')"
                                                                type="button">@if($next_data!=null) Forward @else
                                                                    Approve @endif
                                                            </button>
                                                        @endif

                                                        @if($approvalData->approval_ref_seq!=1 && $next_data==null && $curr_data->approval_status_id!=1)
                                                            <button class="btn btn-dark mr-1 mb-1 btn-danger rejectReq"
                                                                    type="button"
                                                                    onclick="commentWin({{$value->eqr_id}},{{$approvalData->approval_info_id}},'R')"
                                                                    style="color: white"> Reject
                                                            </button>
                                                        @endif
                                                        @if($approvalData->approval_ref_seq!=1 && $curr_data->approval_status_id!=1)
                                                            <button
                                                                class="btn btn btn-success shadow mr-1 mb-1 btn-danger rejectReq"
                                                                type="button"
                                                                onclick="commentWin({{$value->eqr_id}},{{$approvalData->approval_info_id}},'B')"
                                                                style="color: white">Backward
                                                            </button>
                                                        @endif
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
                                                        Equipment Detail
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
                                                            {{--<div class="row">
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
                                                            </div>--}}

                                                            <div class="col-sm-12 mt-1">
                                                                <div class="table-responsive">
                                                                    <table
                                                                        class="table table-sm table-striped table-bordered"
                                                                        id="table-dtl">
                                                                        <thead>
                                                                        <tr>
                                                                            {{--<th role="columnheader" scope="col"
                                                                                aria-colindex="1" class="text-center"
                                                                                width="1%">Action
                                                                            </th>--}}
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
                                                                            <th role="columnheader" scope="col"
                                                                                aria-colindex="2" class="text-center"
                                                                                width="10%">Supplied Equipment
                                                                            </th>
                                                                            <th role="columnheader" scope="col"
                                                                                aria-colindex="2" class="text-center"
                                                                                width="10%">Supply Date
                                                                            </th>
                                                                        </tr>
                                                                        </thead>

                                                                        <tbody role="rowgroup">
                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                            {{--<div class="col-12 d-flex justify-content-start">

                                                                <button type="button"
                                                                        class="btn btn-primary mb-1 delete-row-dtl">
                                                                    Delete
                                                                </button>
                                                            </div>--}}
                                                            @if($curr_data->approval_status_id!=1)
                                                                <div class="form-group mt-1">
                                                                    <div
                                                                        class="col-md-12 pr-0 d-flex justify-content-end">
                                                                        <div class="form-group">
                                                                            <button id="save-info" type="submit"
                                                                                    class="btn btn-primary mr-1 mb-1">
                                                                                Save
                                                                            </button>
                                                                            <button class="btn btn-primary mr-1 mb-1"
                                                                                    type="button" data-dismiss="modal">
                                                                                Close
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="comment-window" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title text-uppercase text-left" id="modal_title">

                                                    </h4>
                                                    <button class="close" type="button" data-dismiss="modal"
                                                            area-hidden="true">
                                                        &times;
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="workflow_submit" method="post">
                                                        {!! csrf_field() !!}
                                                        <fieldset class="border p-1 mt-2 mb-1 col-sm-12"
                                                                  id="detail_data">
                                                            <input type="hidden" id="app_eqr_id" name="app_eqr_id">
                                                            <input type="hidden" id="approval_info_id"
                                                                   name="approval_info_id">
                                                            <input type="hidden" id="apprv_status" name="apprv_status">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="required">Comment</label>
                                                                        <textarea class="form-control" name="comments"
                                                                                  id="" rows="4" required></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group mt-1">
                                                                <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                                    <div class="form-group">
                                                                        <button class="btn btn-primary" type="submit"
                                                                                id="btn_ok">
                                                                        </button>
                                                                        <button type="button" class="btn btn-danger"
                                                                                data-dismiss="modal"><i
                                                                                class="bx bx-exit"></i> Close
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
        </section>
    </div>

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function commentWin(app_eqr_id, approval_info_id, apprv_status) {
            let myModal = $('#comment-window');
            $("#app_eqr_id").val(app_eqr_id);
            $("#approval_info_id").val(approval_info_id);
            $("#apprv_status").val(apprv_status);

            if (apprv_status == 'F') {
                $("#modal_title").text('Comment');
                $("#btn_ok").text('Submit');
            } else if (apprv_status == 'R') {
                $("#modal_title").text('Reject Comment');
                $("#btn_ok").text('Reject');
            } else if (apprv_status == 'B') {
                $("#modal_title").text('Backward Comment');
                $("#btn_ok").text('Backward');
            }
            myModal.modal({show: true});
            return false;
        }


        function chkData() {
            let eqr_id = $("#eqr_id").val();//alert(eqr_id);
            let data;
            //return false;
            $.ajax({
                async: false,
                url: APP_URL + '/submission-chk/' + eqr_id,
                success: function (msg) {
                    data = msg;
                }
            });

            if (data == 0) {
                Swal.fire({
                    title: 'Please Fill the required value!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else {
                return true;
            }

            /*if ($('#comp_body tr').length == 0) {
                Swal.fire({
                    title: 'Complainant Data Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else if ($('#def_body tr').length == 0) {
                Swal.fire({
                    title: 'Defendant Data Empty!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else if ($('#file_body tr').length == 0) {
                Swal.fire({
                    title: 'Upload minimum 1 attachment!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            } else {
                return true;
            }*/
        }

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

            $.ajax({
                url: APP_URL + '/get-eqp-req-dtl/' + eqr_id + '/' + erm_id,
                success: function (msgs) {//console.log(msgs)
                    let markup = '';
                    let mst = msgs.equip_request_mst;
                    console.log(mst.location_type)
                    $("#loc_typ").val(mst.location_type);
                    $("#loc").val(mst.location);
                    $("#cont_20").val(mst.container_20);
                    $("#cont_40").val(mst.container_40);

                    let msg = msgs.equip_request_dtl;

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
                        let supplied_date;
                        if (msg[i].supplied_date) {
                            supplied_date = convertDate(msg[i].supplied_date);
                        } else {
                            supplied_date = convertDate(msg[i].req_for_date);
                        }

                        /*if(supplied_date == null){
                            supplied_date = '';
                        }else{
                            supplied_date = convertDate(msg[i].supplied_date);
                        }*/
                        markup += "<tr role='row'>" +
                            "<td aria-colindex='2' role='cell'>" + msg[i].equip_type + "</td>" +
                            "<td aria-colindex='2' role='cell'>" + loadC + "</td>" +
                            "<td aria-colindex='2' role='cell'>" + requested_equip + "</td>" +
                            "<td aria-colindex='2' role='cell'>" +
                            "<input type='hidden' name='tab_eqr_id[]' value='" + msg[i].eqr_id + "'>" +
                            "<input type='hidden' name='tab_erm_id[]' value='" + msg[i].erm_id + "'>" +
                            "<input type='hidden' name='tab_erd_id[]' value='" + msg[i].erd_id + "'>" +
                            "<input type='hidden' name='tab_equip_type_id[]' value='" + msg[i].equip_type_id + "'>" +
                            "<input type='hidden' name='tab_load_capacity_id[]' value='" + msg[i].load_capacity_id + "'>" +
                            "<input type='number' max='" + requested_equip + "' class='form-control' required name='tab_supplied_equip[]' value='" + supplied_equip + "'></td>" +
                            "<td aria-colindex='2' role='cell'><input type='date' required class='form-control' name='tab_supplied_date[]' value='" + supplied_date + "'></td>" +
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
            let url = '{{route('equip-request-approval-datatable')}}';
            let oTable = $('#searchResultTable').DataTable({
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
                    {data: 'approved_yn', name: 'approved_yn', searchable: false},
                    {data: 'req_status_update_date', name: 'req_status_update_date', searchable: false},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            var eqr_id = '{{isset($mData->eqr_id) ? $mData->eqr_id : ''}}';

            if (eqr_id) {
                $("html, body").animate({scrollTop: $(document).height()}, 1000);
            }
            $("#workflow_form").attr('action', '{{ route('equipment-req-dtl-post') }}');
            $("#workflow_submit").attr('action', '{{ route('approve-reject') }}');
            datePicker('#datetimepicker3');
            datePicker('#datetimepicker4');
            eqReqList();

            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);
        });

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

            if (location_type_id == '1' && container_20 == '' && container_40 == '') {
                Swal.fire(
                    'Fill required value.',
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
                Swal.fire({
                    title: 'Entry Successfully Deleted!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function () {
                    $('td input:checked').closest('tr').remove();
                });
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

