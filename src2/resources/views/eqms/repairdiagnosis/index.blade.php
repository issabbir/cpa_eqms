@extends('layouts.default')

@section('title')
    :: Repair Diagnosis
@endsection

@section('header-style')
    <!--Load custom style link or css-->
    <style>
        /*.emp_id span {*/
        /*    width: 100% !important;*/
        /*}*/

        /*.emp_id span b {*/
        /*    margin-left: 160px !important;*/
        /*}*/
    </style>
@endsection
@section('content')

    <div class="content-body">
        <section id="form-repeater-wrapper">
            <!-- form default repeater -->
            <div class="row">
                <div class="col-12">

                    @include('eqms.repairdiagnosis.list')
                    @if(isset($dData))
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
                                          @if(isset($mData->r_r_mst_id)) action="{{route('repair-diagnosis-update',[$mData->r_r_mst_id])}}"
                                          @endif method="post">
                                        @csrf
                                        @if (isset($mData->r_r_mst_id))
                                            @method('PUT')
                                            <input type="hidden" name="r_d_id"
                                                   value="{{isset($rdMst->r_d_id) ? $rdMst->r_d_id : ''}}">
                                            <input type="hidden" name="r_r_mst_id"
                                                   value="{{isset($mData->r_r_mst_id) ? $mData->r_r_mst_id : ''}}">
                                        @endif

                                        <h5 class="card-title">Repair Diagnosis</h5>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-3 mt-1">
                                                <label>Equipment Name</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($mData->equip_name) ? $mData->equip_name : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Request By</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($mData->r_r_by_emp_name) ? $mData->r_r_by_emp_name : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Request Date</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($mData->r_r_date) ? date('d-m-Y', strtotime($mData->r_r_date)) : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Diagnosis No</label>
                                                <input type="text" required autocomplete="off"
                                                       name="r_d_no"
                                                       class="form-control"
                                                       value="{{isset($rdMst->r_d_no) ? $rdMst->r_d_no : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="250"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Diagnosis Date:</label>
                                                <div class="input-group date" id="datetimepicker1"
                                                     data-target-input="nearest">
                                                    <input type="text" required
                                                           value="{{isset($rdMst->r_d_date) ? date('d-m-Y', strtotime($rdMst->r_d_date)) : ''}}"
                                                           class="form-control datetimepicker-input"
                                                           data-toggle="datetimepicker" data-target="#datetimepicker1"
                                                           id="r_d_date"
                                                           name="r_d_date"
                                                           autocomplete="off"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-1">
                                                <label class="required">Team Selection</label>
                                                <select class="custom-select form-control select2" required id="for_whom"
                                                        name="workshop_team_id[]" multiple="multiple">
                                                    <option value="">Select One</option>

                                                    @foreach ($teams2 as $value)
                                                        <option value="{{ $value->workshop_team_id }}"
                                                        @if (!empty($whom_ids2))
                                                            @foreach ($whom_ids2 as $id)
                                                                @if ($value->workshop_team_id == $id)
                                                                    {{ 'selected="selected"' }}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        >
                                                            {{ $value->team_name . ' (' . $value->workshop_name . ')' }}
                                                        </option>
                                                    @endforeach

                                                </select>
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
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                Malfunction
                                                                Type
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                Description (If Any)
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Resolved?
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Resolve
                                                                Date
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">Send
                                                                to
                                                                Service?
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Workshop
                                                                Type
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Workshop
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">Add
                                                                Request Detail
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">
                                                        @if(isset($dData))
                                                            @foreach($dData as $key=>$value)
                                                                <tr role="row">
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->malfunction}}</td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->malfunction_other}}</td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">
                                                                        @if($value->malfunction_resolve_yn=='Y')
                                                                            <span
                                                                                class="badge badge-success">Resolved</span>
                                                                        @else
                                                                            <span
                                                                                class="badge badge-danger"> Not Resolved</span>
                                                                        @endif
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">
                                                                        @if($value->malfunction_resolve_date!='')
                                                                            {{date('d-m-Y', strtotime($value->malfunction_resolve_date))}}
                                                                        @else
                                                                            --
                                                                        @endif
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">
                                                                        @if($value->send_service_yn=='Y')
                                                                            YES
                                                                        @else
                                                                            --
                                                                        @endif
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">
                                                                        @if($value->assigned_ws_type!='')
                                                                            {{$value->assigned_ws_type}}
                                                                        @else
                                                                            --
                                                                        @endif
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">
                                                                        @if($value->assigned_ws_name!='')
                                                                            {{$value->assigned_ws_name}}
                                                                        @else
                                                                            --
                                                                        @endif
                                                                    </td>
                                                                    <td style='text-align:center; vertical-align:middle'>
                                                                        @if(isset($rdMst->r_d_no))
                                                                            @if($value->findings=='Y')
                                                                                <button type="button"
                                                                                        onclick="getData({{$value->r_r_mst_id}},{{$value->r_r_d_id}})"
                                                                                        class="btn btn-success show-receive-modal workflowBtn">
                                                                                    Detail
                                                                                </button>
                                                                            @else
                                                                                <button type="button"
                                                                                        onclick="getData({{$value->r_r_mst_id}},{{$value->r_r_d_id}})"
                                                                                        class="btn btn-info show-receive-modal workflowBtn">
                                                                                    Add Detail
                                                                                </button>
                                                                            @endif
                                                                        @endif
                                                                    </td>

                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </fieldset>

                                        <div class="form-group mt-1">
                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                <div class="form-group">
                                                    @if(isset($rdMst))
                                                        {{--<button id="boat-employee-save" type="submit"
                                                                class="btn btn-primary mr-1 mb-1">Save
                                                        </button>--}}
                                                        <a type="reset" href="{{route("repair-diagnosis-index")}}"
                                                           class="btn btn-light-secondary mb-1"> Back</a>
                                                    @else
                                                        @if(isset($mData))
                                                            <button id="eq-req-approval" type="submit"
                                                                    class="btn btn-primary mr-1 mb-1"
                                                                    onclick="chkData()">
                                                                Approve
                                                            </button>
                                                            <a type="reset" href="{{route("repair-diagnosis-index")}}"
                                                               class="btn btn-light-secondary mb-1"> Back</a>
                                                        @endif
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
                                                        Diagnosis Detail
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
                                                            <input type="hidden" id="r_r_d_id" name="r_r_d_id">
                                                            <input type="hidden" id="r_r_mst_id" name="r_r_mst_id">
                                                            <input type="hidden" name="r_d_dtl_id" id="r_d_dtl_id">
                                                            <input type="hidden" name="r_d_mst_id" id="r_d_mst_id">
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label>Malfunction Type</label>
                                                                        <input type="text" disabled
                                                                               id="malfunction_type"
                                                                               class="form-control"
                                                                        >
                                                                        <input type="hidden" name="malfunction_id"
                                                                               id="malfunction_id"
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-9">
                                                                    <div class="form-group">
                                                                        <label>Malfunction Description (If Any)</label>
                                                                        <input type="text" disabled
                                                                               id="malfunction_desc"
                                                                               class="form-control"
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 mt-1">
                                                                    <div class="form-group">
                                                                        <label>Resolved?</label>
                                                                        <select class="select2 form-control" required
                                                                                id="malfunction_resolve_yn"
                                                                                name="malfunction_resolve_yn">
                                                                            <option value="">Select One</option>
                                                                            <option value="N">No</option>
                                                                            <option value="Y">Yes</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 mt-1">
                                                                    <label>Resolved Date:</label>
                                                                    <div class="input-group date" id="datetimepicker2"
                                                                         data-target-input="nearest">
                                                                        <input type="text"
                                                                               class="form-control datetimepicker-input"
                                                                               data-toggle="datetimepicker"
                                                                               data-target="#datetimepicker2"
                                                                               id="malfunction_resolve_date"
                                                                               name="malfunction_resolve_date"
                                                                               autocomplete="off"
                                                                        />
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2 mt-1">
                                                                    <div class="form-group">
                                                                        <label>Send to Service?</label>
                                                                        <select class="select2 form-control" required
                                                                                id="send_service_yn"
                                                                                name="send_service_yn">
                                                                            <option value="N">No</option>
                                                                            <option value="Y">Yes</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 mt-1">
                                                                    <div class="form-group">
                                                                        <label>Workshop Type</label>
                                                                        <select class="select2 form-control"
                                                                                id="w_t_id" name="w_t_id">
                                                                            <option value="">Select One</option>
                                                                            @if(isset($wtList))
                                                                                @foreach($wtList as $value)
                                                                                    <option
                                                                                        value="{{$value->w_t_id}}">
                                                                                        {{$value->w_t_name}}
                                                                                    </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-sm-3 mt-1">
                                                                    <label>Workshop</label>
                                                                    <select
                                                                        class="custom-select form-control select2"
                                                                        style="width: 100%"
                                                                        id="workshop_id" name="workshop_id">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <fieldset id="disable-portion" style="display: none">
                                                                <div class="row">
                                                                    <div class="form-group col-sm-3 mt-1">
                                                                        <label class="required">Diagnosis By :</label>
                                                                        <select
                                                                            class="custom-select form-control emp_id"
                                                                            style="width: 100%" id="emp_id">
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-2 mt-1">
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
                                                                                    width="10%">Employee
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
                                                            </fieldset>


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

        $("#malfunction_resolve_yn").on('change', function (e) {
            let malfunction_resolve_yn = $(this).val();
            if (malfunction_resolve_yn == 'Y') {
                $('#disable-portion').show();
                $('#malfunction_resolve_date').prop('disabled', false);
                $('#send_service_yn').prop('disabled', true);
                $('#w_t_id').prop('disabled', true);
                $('#workshop_id').prop('disabled', true);
                $('#malfunction_resolve_date').val('');
                $("#w_t_id").val('').trigger('change');
                $("#workshop_id").val('').trigger('change');
            } else if (malfunction_resolve_yn == 'N') {
                $('#disable-portion').hide();
                $('#malfunction_resolve_date').prop('disabled', true);
                $('#malfunction_resolve_date').val('');
                $('#send_service_yn').prop('disabled', false);
            } else {
                $('#disable-portion').hide();
                $('#malfunction_resolve_date').prop('disabled', true);
                $('#malfunction_resolve_date').val('');
                $('#send_service_yn').prop('disabled', true);
            }
        });

        $("#send_service_yn").on('change', function (e) {
            let send_service_yn = $(this).val();
            if (send_service_yn == 'Y') {
                $('#w_t_id').prop('disabled', false);
                $('#workshop_id').prop('disabled', false);
                //$('#disable-portion').hide();
                $("#w_t_id").val('').trigger('change');
                $("#workshop_id").val('').trigger('change');
            } else {
                $('#w_t_id').prop('disabled', true);
                $('#workshop_id').prop('disabled', true);
                $("#w_t_id").val('').trigger('change');
                $("#workshop_id").val('').trigger('change');
                //$('#disable-portion').show();
            }
        });

        $("#w_t_id").on('change', function (e) {
            let w_t_id = $(this).val();
            setSelect(w_t_id);
        });

        function setSelect(w_t_id) {
            $.ajax({
                type: 'get',
                url: '/get-workshop',
                data: {w_t_id: w_t_id},
                success: function (msg) {
                    $("#workshop_id").html(msg);
                }
            });
        }

        function convertDate(inputFormat) {
            function pad(s) {
                return (s < 10) ? '0' + s : s;
            }

            var d = new Date(inputFormat)
            return [d.getFullYear(), pad(d.getMonth() + 1), pad(d.getDate())].join('-')
        }

        function setSelectDb(w_t_id, selection_id) {
            $.ajax({
                type: 'get',
                url: '/get-workshop-db',
                data: {w_t_id: w_t_id, selection_id: selection_id},
                success: function (msg) {
                    $("#workshop_id").html(msg);
                }
            });
        }

        function getData(r_r_mst_id, r_r_d_id) {
            let myModal = $('#status-show');
            $('#malfunction_resolve_date').prop('disabled', false);
            $('#send_service_yn').prop('disabled', true);
            $('#w_t_id').prop('disabled', true);
            $('#workshop_id').prop('disabled', true);
            $('#malfunction_resolve_date').val('');
            $("#w_t_id").val('').trigger('change');
            $("#workshop_id").val('').trigger('change');

            $.ajax({
                url: APP_URL + '/get-emp-data/' + r_r_mst_id + '/' + r_r_d_id,
                success: function (msg) {
                    $("#malfunction_resolve_yn").val('').trigger('change');
                    let repair_diagnosis_emp = msg.repair_diagnosis_emp;
                    let repair_diagnosis_dtl = msg.repair_diagnosis_dtl;
                    let repair_diagnosis_mst = msg.repair_diagnosis_mst;
                    if (repair_diagnosis_mst) {
                        $("#r_d_mst_id").val(repair_diagnosis_mst.r_d_id);
                    }

                    if (repair_diagnosis_dtl) {
                        $("#malfunction_resolve_yn").select2("val", repair_diagnosis_dtl.malfunction_resolve_yn);
                        if (repair_diagnosis_dtl.malfunction_resolve_date) {
                            $("#malfunction_resolve_date").val(convertDate(repair_diagnosis_dtl.malfunction_resolve_date));
                        }
                        $("#send_service_yn").select2("val", repair_diagnosis_dtl.send_service_yn);
                        $("#w_t_id").select2("val", repair_diagnosis_dtl.assigned_ws_type_id);
                        setSelectDb(repair_diagnosis_dtl.assigned_ws_type_id, repair_diagnosis_dtl.assigned_ws_id);
                        $("#r_d_dtl_id").val(repair_diagnosis_dtl.r_d_dtl_id);
                    }
                    $('#malfunction_type').val('');
                    $('#malfunction_desc').val('');
                    $('#malfunction_id').val('');
                    if (msg.repair_request_dtl) {
                        $("#r_r_mst_id").val(msg.repair_request_dtl.r_r_mst_id);
                        $("#r_r_d_id").val(msg.repair_request_dtl.r_r_d_id);
                        $('#malfunction_type').val(msg.repair_request_dtl.malfunction);
                        $('#malfunction_desc').val(msg.repair_request_dtl.malfunction_other);
                        $('#malfunction_id').val(msg.repair_request_dtl.malfunction_id);
                    }

                    if (repair_diagnosis_emp.length !== 0) {
                        let markup = '';
                        $("#table-dtl > tbody").html("");
                        $.each(repair_diagnosis_emp, function (i) {
                            markup += "<tr role='row'>" +
                                "<td aria-colindex='1' role='cell' class='text-center'>" +
                                "<input type='checkbox' name='record' value='" + repair_diagnosis_emp[i].r_d_e_id + "+" + repair_diagnosis_emp[i].r_d_mst_id + "+" + repair_diagnosis_emp[i].r_d_dtl_id + "'>" +
                                "<input type='hidden' name='tab_emp_id[]' value='" + repair_diagnosis_emp[i].emp_id + "'>" +
                                "</td><td aria-colindex='2' role='cell' class='text-center'>" + repair_diagnosis_emp[i].emp_name + "</td></tr>";

                        });
                        $("#table-dtl tbody").html(markup);
                    }else{
                        $('#disable-portion').hide();
                    }

                }
            });
            myModal.modal({show: true});
            return false;
        }

        function eqReqList() {
            var url = '{{route('repair-diagnosis-datatable')}}';
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
                    {data: 'equip_name', name: 'equip_name', searchable: true},
                    {data: 'equipment.equip_model', name: 'equip_name', searchable: true},
                    {data: 'r_r_by_emp_name', name: 'r_r_by_emp_name', searchable: true},
                    {data: 'r_r_date', name: 'r_r_date', searchable: false},
                    {data: 'resolve_yn', name: 'resolve_yn', searchable: false},
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

            $('#send_service_yn').prop('disabled', true);
            var r_r_mst_id = '{{isset($mData->r_r_mst_id) ? $mData->r_r_mst_id : ''}}';

            if (r_r_mst_id) {
                $("html, body").animate({scrollTop: $(document).height()}, 1000);
            }
            $('#malfunction_resolve_date').prop('disabled', true);
            $('#w_t_id').prop('disabled', true);
            $('#workshop_id').prop('disabled', true);

            $('.emp_id').select2({
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
            $("#workflow_form").attr('action', '{{ route('repair-diagnosis-dtl-post') }}');
            datePicker('#datetimepicker1');
            datePicker('#datetimepicker2');
            eqReqList();
        });

        $(".add-row-dtl").click(function () {
            let emp_id = $("#emp_id option:selected").val();
            let emp_name = $("#emp_id option:selected").text();

            if (emp_id == '') {
                Swal.fire(
                    'Fill required value.',
                    '',
                    'error'
                )
            } else {
                let markup = "<tr role='row'>" +
                    "<td aria-colindex='1' role='cell' class='text-center'>" +
                    "<input type='checkbox' name='record' value='" + "" + "+" + "" + "'>" +
                    "<input type='hidden' name='tab_emp_id[]' value='" + emp_id + "'>" +
                    "</td><td aria-colindex='2' role='cell' style='text-align: center'>" + emp_name + "</td></tr>";
                $("#emp_id").val('').trigger('change');
                $("#table-dtl tbody").append(markup);
            }

        });

        $(".delete-row-dtl").click(function () {
            let arr_stuff = [];
            let r_d_e_id = [];
            $(':checkbox:checked').each(function (i) {
                arr_stuff[i] = $(this).val();
                let sd = arr_stuff[i].split('+');
                //dtl_id = sd[2];
                //operator_id.push(sd[0]);
                if (sd[0]) {
                    r_d_e_id.push(sd[0]);
                }
            });

            if (r_d_e_id.length != 0) {
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
                            url: '/emp-remove',
                            data: {r_d_e_id: r_d_e_id},
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

