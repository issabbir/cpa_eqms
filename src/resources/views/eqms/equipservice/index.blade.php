@extends('layouts.default')

@section('title')
    :: Scheduled Service
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
                                      @if(isset($serviceMaster[0]->s_m_id))
                                      action="{{route('equipment-service-update', ['id' => $serviceMaster[0]->s_m_id])}}"
                                      @else action="{{route('equipment-service-post')}}" @endif method="post" onsubmit="return chkTable()">

                                    @if (isset($serviceMaster[0]->s_m_id))
                                        @method('PUT')
                                        <input type="hidden" id="s_m_id" name="s_m_id"
                                               value="{{isset($serviceMaster[0]->s_m_id) ? $serviceMaster[0]->s_m_id: ''}}">
                                    @endif
                                    @csrf
{{--                                        a_pending_info--}}

                                    <h5 class="card-title">Scheduled Service</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <label class="required">Service No</label>
                                            <input type="text"
                                                   class=" form-control" required
                                                   id="service_no" autocomplete="off"
                                                   name="service_no"
                                                   placeholder="Service No"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="250"
                                                   value="{{isset($serviceMaster[0]->service_no) ? $serviceMaster[0]->service_no : ''}}"/>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="required">Equipment NO</label>
                                            <select class="custom-select select2 form-control"
                                                    name="eqp_id" id="eqp_id">
                                                <option value="">---Choose---</option>
                                                @foreach($equipmentList as $value)
                                                    <option
                                                        value="{{$value->equip_id}}" {{isset($serviceMaster[0]->equip_id) && $serviceMaster[0]->equip_id == $value->equip_id ? 'selected' : ''}}>{{$value->equip_no.'-'.$value->equip_name}} </option>

                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 ">
                                            <label class="required">Operator Employee</label>
                                            <select class="custom-select  form-control operator_emp"
                                                    id="operator_emp " name="operator_emp">
                                                @if(isset($serviceMaster[0]))
                                                    <option
                                                        value="{{$serviceMaster[0]->operator_emp_id}}">{{$serviceMaster[0]->empInfo->emp_code.'-'.$serviceMaster[0]->empInfo->emp_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 ">
                                            <label class="required">Service Date</label>
                                            <div class="input-group date" id="datetimepicker3"
                                                 data-target-input="nearest">
                                                <input type="text" required
                                                       value="{{isset($serviceMaster[0]->service_date) ? date('d-m-Y', strtotime($serviceMaster[0]->service_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker3"
                                                       id="service_date"
                                                       name="service_date" placeholder="Service Date"
                                                       autocomplete="off"/>
                                            </div>
                                        </div>
                                        {{--<div class="col-md-3 mt-1">
                                            <label class="required">Service End Date</label>
                                            <div class="input-group date" id="datetimepicker4"
                                                 data-target-input="nearest">
                                                <input type="text" required
                                                       value="{{isset($serviceMaster[0]->service_end_date) ? date('d-m-Y', strtotime($serviceMaster[0]->service_end_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker4"
                                                       id="service_end_date"
                                                       name="service_end_date" placeholder="Service End Date"
                                                       autocomplete="off"/>
                                            </div>
                                        </div>--}}
                                        <div class="col-md-3 mt-1 mb-1">
                                            <label for="service_end_date" class="required">Service End Date</label>
                                            <div class="input-group date datePiker">
                                                <input type="text"
                                                autocomplete="off"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker"
                                                       id="service_end_date"
                                                       data-target="#service_end_date"
                                                       name="service_end_date"
                                                       data-predefined-date="{{old('service_end_date',isset($serviceMaster[0]->service_end_date) ? date('d-m-Y', strtotime($serviceMaster[0]->service_end_date)) : '')}}"
                                                >
                                                @if(isset($data->service_end_date))
                                                    <input type="hidden" name="service_end_date" value="{{old('service_end_date',isset($serviceMaster[0]->service_end_date) ? date('d-m-Y', strtotime($serviceMaster[0]->service_end_date)) : '')}}">
                                                @endif
                                                <div class="input-group-append" data-target="#service_end_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i
                                                            class="bx bxs-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Service</label>
                                                    <select class="custom-select select2 form-control pl-0 pr-0"
                                                            id="service_id"
                                                            name="service_id">
                                                        <option value="">Select One
                                                        </option> @foreach($serviceList as $value)
                                                            <option value="{{$value->service_id}}"
                                                            >{{$value->service}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            {{--<div class="col-sm-2">
                                                <label class="">Service End Date</label>
                                                <div class="input-group date" id="datetimepicker5"
                                                     data-target-input="nearest">
                                                    <input type="text"
                                                           value=""
                                                           class="form-control datetimepicker-input"
                                                           data-toggle="datetimepicker" data-target="#datetimepicker5"
                                                           id="end_date" placeholder="Service End Date"
                                                           name="end_date"
                                                           autocomplete="off"/>
                                                </div>
                                            </div>--}}
                                            <div class="col-md-3">
                                                <label for="end_date">Service End Date</label>
                                                <div class="input-group date datePiker">
                                                    <input type="text"
                                                    autocomplete="off" disabled
                                                           class="form-control datetimepicker-input"
                                                           data-toggle="datetimepicker"
                                                           id="end_date"
                                                           data-target="#end_date"
                                                           name="end_date"
                                                    >
                                                    <div class="input-group-append" data-target="#end_date"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Quantity</label>
                                                <input type="text"
                                                       id="quantity"
                                                       name="quantity" autocomplete="off"
                                                       class="form-control"
                                                       placeholder="Quantity">
                                            </div>
                                            <div class="col-sm-3">
                                                <label>Remarks</label>
                                                <input class="form-control" name="remarks" autocomplete="off"
                                                       id="remarks" placeholder="Remarks">
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
                                                            aria-colindex="2" class="text-center" width="10%">Service

                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">End Date
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">Quantity
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">Remarks
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="10%">Add
                                                            Service Employee
                                                        </th>
                                                    </tr>
                                                    </thead>


                                                    <tbody role="rowgroup" id="comp_body">
                                                    @if(!empty($ServiceDetails))
                                                        @foreach($ServiceDetails as $key=>$value)
                                                            <tr role="row">
                                                                <td aria-colindex="1" role="cell" class="text-center">
                                                                    <input type='checkbox' name='record'
                                                                           value="{{$value->s_d_id.'+'.$value->s_m_id}}">
                                                                    <input type="hidden" name="tab_ser_id[]"
                                                                           value="{{$value->s_d_id}}"
                                                                           class="erm_id">
                                                                </td>
                                                                <td aria-colindex="7" role="cell">
                                                                    <input type="text" class="form-control" readonly
                                                                           value="{{$value->service}}">
                                                                    <input type="hidden" class="form-control"
                                                                           name="tab_service_id[]"
                                                                           value="{{$value->service_id}}"></td>
                                                                <td aria-colindex="7" role="cell">
                                                                    <input type="text" class="form-control"
                                                                           name="tab_end_date[]"
                                                                           value="{{date('Y-m-d', strtotime($value->service_end_time)) }}">
                                                                </td>
                                                                <td aria-colindex="7" role="cell">
                                                                    <input type="text" class="form-control"
                                                                           name="tab_quantity[]"
                                                                           value="{{$value->qty}}">
                                                                </td>
                                                                <td aria-colindex="7" role="cell">
                                                                    <input type="text" class="form-control"
                                                                           name="tab_remarks[]"
                                                                           value="{{$value->remarks}}">
                                                                </td>
                                                                {{--<td style='text-align:center; vertical-align:middle'>
                                                                    <button type="button"
                                                                            onclick="getData({{$value->s_d_id}},{{$value->s_m_id}})"
                                                                            class="btn btn-info show-receive-modal workflowBtn">
                                                                        Detail
                                                                    </button>

                                                                </td>--}}
                                                                <td style='text-align:center; vertical-align:middle'>
                                                                    @if($value->findings=='Y')
                                                                        <button type="button"
                                                                                onclick="getData({{$value->s_d_id}},{{$value->s_m_id}})"
                                                                                class="btn btn-success show-receive-modal workflowBtn">
                                                                            Detail
                                                                        </button>
                                                                    @else
                                                                        <button type="button"
                                                                                onclick="getData({{$value->s_d_id}},{{$value->s_m_id}})"
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
                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                @if(!isset($serviceMaster))
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Save
                                                    </button>
                                                    <a type="reset" href="{{route("equipment-service-index")}}"
                                                       class="btn btn-light-secondary mb-1"> Reset</a>
                                                @else
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Update
                                                    </button>
                                                    <a type="reset" href="{{route("equipment-service-index")}}"
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
                                                    Add Employee
                                                </h4>
                                                <button class="close" type="button" data-dismiss="modal"
                                                        area-hidden="true">
                                                    &times;
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="emp_form" method="post">
                                                    {!! csrf_field() !!}
                                                    <fieldset class="border p-1 mt-2 mb-1 col-sm-12"
                                                              id="detail_data">
                                                        <input type="hidden" id="dtl_sm_id" name="dtl_sm_id">
                                                        <input type="hidden" id="dtl_sd_id" name="dtl_sd_id">
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Employee</label>
                                                                    <select class="custom-select select2 form-control employee_info"
                                                                            id="employee_info" name="employee_info"
                                                                            style="width: 100%">


                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label>Designation</label>
                                                                <input type="text" readonly
                                                                       id="emp_designation"
                                                                       name="emp_designation"
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
                                                                            width="10%">Employee Name
                                                                        </th>
                                                                        <th role="columnheader" scope="col"
                                                                            aria-colindex="2" class="text-center"
                                                                            width="10%">Designation
                                                                        </th>
                                                                        {{--                                                                        <th role="columnheader" scope="col"--}}
                                                                        {{--                                                                            aria-colindex="2" class="text-center"--}}
                                                                        {{--                                                                            width="10%">Department--}}
                                                                        {{--                                                                        </th>--}}
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
                                                                    <button class="btn btn-primary mr-1 mb-1" type="button" data-dismiss="modal">
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

    @include('eqms.equipservice.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function getData(s_d_id, s_m_id) {
            let myModal = $('#status-show');
            $("#dtl_sm_id").val(s_m_id);
            $("#dtl_sd_id").val(s_d_id);

            $.ajax({
                url: APP_URL + '/get-service-dtl-emp-data/' + s_m_id + '/' + s_d_id,
                success: function (msg) {

                    let markup = '';
                    $("#table-dtl > tbody").html("");
                    $.each(msg, function (i) {

                        let empI = msg[i].emp_name;
                        let desI = msg[i].designation;

                        markup += "<tr role='row'>" +
                            "<td aria-colindex='1' role='cell' class='text-center'>" +
                            "<input type='checkbox' name='record' value='" + msg[i].s_m_id + "+" + msg[i].s_d_id + "+" + msg[i].s_d_e_id + "'>" +
                            "<input type='hidden' name='tab_emp_id[]' value='" + msg[i].emp_id + "'>" +
                            "</td><td aria-colindex='2' role='cell'>" + empI + "</td><td aria-colindex='2' role='cell'><input type='text' class='form-control' name='tab_requested_equip[]' value='" + desI + "'></td></tr>";

                    });
                    $("#table-dtl tbody").html(markup);
                }
            });
            myModal.modal({show: true});
            return false;
        }

        $('.operator_emp').select2({
            placeholder: "Select one",
            ajax: {
                url: APP_URL + '/get-employee-mechanic',
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

        $('.employee_info').select2({
            placeholder: "Select one",
            ajax: {
                url: APP_URL + '/get-employee-mechanic',
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

        function ServiceList() {
            var url = '{{route('equipment-service-datatable')}}';
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
                    {data: 'service_no', name: 'service_no', searchable: true},
                    {data: 'equip_no', name: 'equip_no', searchable: true},
                    {data: 'emp_name', name: 'emp_name', searchable: true},
                    /*{data: 'nothi_no', name: 'nothi_no', searchable: true},*/
                    {data: 'service_date', name: 'service_date', searchable: false},
                    {data: 'service_end_date', name: 'service_end_date', searchable: false},
                    // {data: 'req_for_date', name: 'req_for_date', searchable: false},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        var dataArray = new Array();
        $(".add-row-dtl").click(function () {
            let master_id = $("#dtl_sm_id").val();
            let dtl_id = $("#dtl_sd_id").val();
            let emp_id = $("#employee_info option:selected").val();
            let emp_name = $("#employee_info option:selected").text();
            let emp_designation = $("#emp_designation").val();
            let emp_dept = $("#emp_department").val();

            if (emp_id == '') {
                emp_id = '';
            }


            if (emp_id == '') {
                Swal.fire(
                    'Fill required value.',
                    '',
                    'error'
                )
            }
            if (emp_id) {
                if ($.inArray(emp_id, dataArray) > -1) {
                    Swal.fire(
                        'Duplicate value not allowed.',
                        '',
                        'error'
                    )
                } else {
                    let markup = "<tr role='row'>" +
                        "<td aria-colindex='1' role='cell' class='text-center'>" +
                        "<input type='checkbox' name='record' value='" + master_id + "+" + dtl_id + "+" + "" + "'>" +
                        "<input type='hidden' name='tab_emp_id[]' value='" + emp_id + "'>" +
                        "</td><td aria-colindex='2' role='cell'>" + emp_name + "</td><td aria-colindex='2' role='cell'>" + emp_designation + "</td></tr>";
                    $("#emp_id").val('');
                    $("#emp_designation").val('');
// $("#emp_dept").val('');
                    $("#table-dtl tbody").append(markup);
                    dataArray.push(emp_id);
                }
            } else {
                Swal.fire(
                    'Fill required value.',
                    '',
                    'error'
                )
            }

        });

        $(".add-row").click(function () {
            let service_id = $("#service_id option:selected").val();
            let service = $("#service_id option:selected").text();
            let end_date = $("#end_date").val();
            let service_no = $("#service_no").val();
            let quantity = $("#quantity").val();
            let remarks = $("#remarks ").val();


            if (service_id == '') {
                service = '--';
            }

            if (end_date == '') {
                end_date = '--';
            }

            if (quantity == '') {
                quantity = '--';
            }
            if (remarks == '') {
                remarks = '--';
            }

            if (service_id == '') {
                Swal.fire(
                    'Select Service.',
                    '',
                    'error'
                )
            } else if(end_date == ''){
                Swal.fire(
                    'Set Service End Date.',
                    '',
                    'error'
                )
            } else if(quantity == ''){
                Swal.fire(
                    'Set Quantity.',
                    '',
                    'error'
                )
            } else if(service_no == ''){
                Swal.fire(
                    'Set Service No.',
                    '',
                    'error'
                )
            } else {
                let markup = "<tr role='row'>" +
                    "<td aria-colindex='1' role='cell' class='text-center'>" +
                    "<input type='checkbox' name='record' value='" + "" + "+" + "" + "'>" +
                    "<input type='hidden' name='tab_ser_id[]' value=''>" +
                    "<input type='hidden' name='tab_service_id[]' value='" + service_id + "'>" +
                    "<input type='hidden' name='tab_end_date[]' value='" + end_date + "'>" +
                    "<input type='hidden' name='tab_quantity[]' value='" + quantity + "'>" +
                    "<input type='hidden' name='tab_remarks[]' value='" + remarks + "'>" +
                    "</td><td aria-colindex='2' role='cell'>" + service + "</td><td aria-colindex='2' role='cell'>" + end_date + "</td><td aria-colindex='2' role='cell'>" + quantity + "</td><<td aria-colindex='2' role='cell'>" + remarks + "</td><td aria-colindex='2' role='cell'></td></tr>";
                $("#remarks").val('');
                $("#quantity").val('');
                $("#end_date").val('').trigger('change');
                $("#service_id").val('').trigger('change');
                $("#table-operator tbody").append(markup);
            }

        });

        $(".delete-row").click(function () {
            let arr_stuff = [];
            let esm_id = [];
            let esd_id = [];
            $(':checkbox:checked').each(function (i) {
                arr_stuff[i] = $(this).val();
                let sd = arr_stuff[i].split('+');
                if (sd[0]) {
                    esd_id.push(sd[0]);
                    esm_id.push(sd[1]);
                }
            });

            if (esm_id.length != 0) {
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
                            url: '/eqs-mst-data-remove',
                            data: {esd_id: esd_id},
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
                $('td input:checked').closest('tr').remove();
                /*Swal.fire({
                    title: 'Entry Successfully Deleted!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function () {
                    $('td input:checked').closest('tr').remove();
                });*/
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
                            url: '/emp-data-remove',
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
                $('td input:checked').closest('tr').remove();
                /*Swal.fire({
                    title: 'Entry Successfully Deleted!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function () {
                    $('td input:checked').closest('tr').remove();
                });*/
            }
        });

        $("#employee_info").on("change", function () {
            let empID = $("#employee_info").val();

            let url = APP_URL + '/get-employee-details/';
            if (((empID !== undefined) || (empID != null)) && empID) {
                $.ajax({
                    type: "GET",
                    url: url + empID,
                    success: function (data) {


                        $('#emp_designation').val(data[0].designation);
                        $('#emp_department').val(data[0].department_name);

                    },
                    error: function (data) {
                        alert('error asche');
                    }
                });
            } else {
                $('#emp_designation').val('');
                $('#emp_department').val('');

            }
        });

        $(document).ready(function () {

            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);

            $("#emp_form").attr('action', '{{ route('service-dtl-post') }}');
            datePicker('#datetimepicker2');
            datePicker('#datetimepicker3');
            datePicker('#datetimepicker4');
            datePicker('#datetimepicker5');

            ServiceList();

        });

        function chkTable() {
            if ($('#comp_body tr').length == 0) {
                Swal.fire({
                    title: 'Service Setup Needed!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        }
        let service_end_date = '';
        $('#service_end_date').on("change.datetimepicker", function (e) {
            service_end_date = $(this).val();
            if (service_end_date) {
                $('#end_date').prop('disabled', false);
            } else {
                $('#end_date').prop('disabled', true);
            }
            dateRangePicker('#service_end_date', '#end_date', service_end_date);
        });
        dateRangePicker('#service_end_date', '#end_date');
        function dateRangePicker(Elem1, Elem2, minDate = null, maxDate = null) {
            let minElem = $(Elem2);
            let maxElem = $(Elem1);

            // console.log(maxDate)
            minElem.datetimepicker({
                format: 'DD-MM-YYYY',
                ignoreReadonly: true,
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
                minElem.datetimepicker('maxDate', minDate);
                // $(Elem1).datetimepicker('minDate',  moment("DD-MM-YYYY"));
            } else {
                maxElem.on("change.datetimepicker", function (e) {
                    minElem.datetimepicker('maxDate', e.date);
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
    </script>

@endsection

