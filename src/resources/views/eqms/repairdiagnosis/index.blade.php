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
                    @include('eqms.repairdiagnosis.list')
                    @if(isset($dData))
                        <div class="card">
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
                                                <label>Request No</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($mData->r_r_no) ? $mData->r_r_no : ''}}"
                                                >
                                            </div>

                                            <div class="col-md-3 mt-1">
                                                <label>Equipment</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($mData->equip_name) ? $mData->equip_no.' - '.$mData->equip_name : ''}}"
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

                                            <div class="col-md-3 mt-1 hidden">
                                                <label class="required">Diagnosis No</label>
                                                <input type="text" readonly required autocomplete="off"
                                                       name="r_d_no"
                                                       class="form-control"
                                                       {{-- value="{{isset($mData->r_r_no) ? $mData->r_r_no : ''}}"--}}
                                                       {{-- preg_replace to remove 'RP' from $mData->r_r_no --}}
                                                       value="{{isset($rdMst->r_d_no) ? $rdMst->r_d_no : 'RD-'.preg_replace("/[^0-9.]/", "",  $mData->r_r_no) }}"
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
                                                <select class="custom-select form-control select2" required
                                                        id="for_whom"
                                                        name="workshop_team_id[]" multiple="multiple">


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

                                            <div class="col-md-12 mt-1">
                                                <label>Description</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Description"
                                                       name="description"
                                                       class="form-control"
                                                       value="{{isset($rdMst->description) ? $rdMst->description : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="500"
                                                >
                                            </div>
                                        </div>

                                        <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                            @if(isset($mData->submit_approval) && $mData->submit_approval =='N')
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>Malfunction Type</label>
                                                            <select class="select2 form-control pl-0 pr-0"
                                                                    id="malfunction_id">
                                                                <option value="">Select One</option>
                                                                @if(isset($mfList))
                                                                    @foreach($mfList as $value)
                                                                        <option value="{{$value->malfunction_id}}">
                                                                            {{$value->malfunction}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5" id="hide_desc">
                                                        <label>Malfunction Description</label>
                                                        <input type="text"
                                                               id="malfunction_other"
                                                               class="form-control"
                                                        >
                                                    </div>
                                                    <div class="col-sm-2 " id="hide_desc">
                                                        <label>repairing workshop</label>
                                                        <select class="custom-select form-control mal_workshop_id">
                                                            <option value="">Select One</option>
                                                            @foreach($lWorkshopList as $lWorkshop)
                                                                <option value="{{$lWorkshop->workshop_id}}"
                                                                >{{$lWorkshop->workshop_name}}</option>
                                                            @endforeach
                                                        </select>
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
                                            @endif
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
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                Malfunction
                                                                Type
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="40%">
                                                                Description (If Any)
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="1" class="text-center required"
                                                                width="20%">
                                                                Repairing Workshop
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">
                                                        @if(!empty($dData2))
                                                            @foreach($dData2 as $key=>$value)
                                                                <tr role="row">
                                                                    <td aria-colindex="1" role="cell"
                                                                        class="text-center">
                                                                        <input type='checkbox' name='record'
                                                                               value="{{$value->r_r_mst_id.'+'.$value->r_r_d_id}}">
                                                                        <input type="hidden" name="tab_malfunction_id[]"
                                                                               value="{{$value->malfunction_id}}">
                                                                        <input type="hidden" name="tab_r_r_d_id[]"
                                                                               value="{{$value->r_r_d_id}}">
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->malfunction}}</td>
                                                                    <td aria-colindex="7" role="cell">
                                                                        <input
                                                                            @if($value->malfunction_other==null) readonly
                                                                            @endif type="text" class="form-control"
                                                                            name="tab_malfunction_other[]"
                                                                            value="{{$value->malfunction_other}}">
                                                                    </td>
                                                                    <td aria-colindex="7" role="cell">
                                                                        <select class="custom-select form-control"
                                                                                required
                                                                                name="tab_workshop_id[]">
                                                                            <option value="">Select One</option>
                                                                            @foreach($lWorkshopList as $lWorkshop)
                                                                                <option
                                                                                    value="{{$lWorkshop->workshop_id}}"
                                                                                    {{isset($value->repair_workshop_id) && $value->repair_workshop_id == $lWorkshop->workshop_id ? 'selected' : ''}}
                                                                                >{{$lWorkshop->workshop_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                            @if(isset($mData->submit_approval) && $mData->submit_approval =='N')
                                                <div class="col-12 d-flex justify-content-start">

                                                    <button type="button"
                                                            class="btn btn-primary mb-1 delete-row">
                                                        Delete
                                                    </button>
                                                </div>
                                            @endif
                                        </fieldset>

                                        @if(isset($mData->submit_approval) && $mData->submit_approval =='N')
                                            <div class="col-md-12 mt-2 mb-2">
                                                <fieldset class="border p-2 mb-2">
                                                    <legend class="w-auto">&nbsp;Workflow Step For Requisition
                                                        Approval
                                                    </legend>
                                                    @include('eqms.requestapproval.workflow_step')
                                                </fieldset>
                                            </div>
                                        @endif

                                        @if(isset($mData->submit_approval) && $mData->submit_approval =='Y')
                                            <div class="col-md-12 mt-2 mb-2">
                                                <fieldset class="border p-2 mb-2">
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @if(count($workflows) > 0)
                                                        @include('eqms.repairdiagnosis.workflow')
                                                    @endif
                                                </fieldset>
                                            </div>
                                        @endif


                                        <div class="form-group mt-1">

                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                @if($mData->req_status_id ==1)
                                                    @if($inventoryData > 0)
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-md">
                                                                <span
                                                                    class="text-danger"><b>DEMAND SUBMITTED FOR APPROVAL.</b></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-md">
                                                                    @php
                                                                        $url = App\Entities\Security\Menu::where('menu_id', 42)
                                                                                ->where('module_id', 45)
                                                                                ->first()
                                                                                ->base_url;
                                                                    @endphp
                                                                    <a target="_blank"
                                                                       href="{{externalLoginUrl($url, '/create-item-demand?module_id=59&refcode=ED&ref=' . $mData->r_r_mst_id.'&equip_id='.$mData->equip_id)}}"
                                                                       class="btn btn-primary mb-1"> New Demand</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="form-group">
                                                    @if(isset($mData->submit_approval) && $mData->submit_approval =='Y' && $mData->resolve_yn =='N')
                                                        @if(isset($curr_data) && $curr_data->current_yn == 'Y')
                                                            <button
                                                                class="btn btn btn-success shadow mr-1 mb-1 btn-primary rejectReq"
                                                                style="color: white"
                                                                @if(isset($curr_data) && $curr_data->current_yn != 'Y') disabled
                                                                @endif
                                                                onclick="commentWin({{$mData->r_r_mst_id}},{{$curr_data->approval_info_id}},'F')"
                                                                type="button">@if($next_data!=null) Forward @else
                                                                    Approve @endif
                                                            </button>
                                                        @endif


                                                        @if(isset($curr_data) && $curr_data->approval_ref_seq > $min_seq)
                                                            @if($curr_data->current_yn=='Y')
                                                                <button
                                                                    class="btn btn btn-success shadow mr-1 mb-1 btn-danger rejectReq"
                                                                    type="button"
                                                                    onclick="commentWin({{$mData->r_r_mst_id}},{{$curr_data->approval_info_id}},'B')"
                                                                    style="color: white">Backward
                                                                </button>
                                                            @endif
                                                        @endif

                                                        @if(isset($curr_data) && $curr_data->approval_ref_seq == $max_seq && $curr_data->current_yn=='Y')
                                                            <button class="btn btn-dark mr-1 mb-1 btn-danger rejectReq"
                                                                    type="button"
                                                                    onclick="commentWin({{$mData->r_r_mst_id}},{{$curr_data->approval_info_id}},'R')"
                                                                    style="color: white"> Reject
                                                            </button>
                                                        @endif
                                                    @endif

                                                    @if(isset($mData->submit_approval) && $mData->submit_approval =='N')
                                                        <button id="eq-req-approval" type="submit" name="approve"
                                                                value="1"
                                                                class="btn btn-primary mr-1 mb-1">
                                                            Submit For Approval
                                                        </button>
                                                        <button id="eq-req-approval" type="submit" value="1"
                                                                class="btn btn-primary mr-1 mb-1" name="update">
                                                            Update
                                                        </button>
                                                    @endif
                                                    <a type="reset" href="{{route("repair-diagnosis-index")}}"
                                                       class="btn btn-light-secondary mb-1"> Back</a>

                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                    {{--                                    ---------------------------------------------------------------------------------- form ends--}}


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
                                                            <input type="hidden" id="app_r_r_mst_id"
                                                                   name="app_r_r_mst_id">
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
            <!--/ form default repeater -->

        </section>
    </div>



@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        function commentWin(app_eqr_id, approval_info_id, apprv_status) {
            let myModal = $('#comment-window');
            $("#app_r_r_mst_id").val(app_eqr_id);
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
                    } else {
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
                    {data: 'r_r_no', name: 'r_r_no', searchable: true},
                    {data: 'equip_name', name: 'equip_name', searchable: true},
                    {data: 'workshop_name', name: 'workshop_name', searchable: false},
                    {data: 'equip_model', name: 'equip_model', searchable: true},
                    {data: 'r_r_by_emp_name', name: 'r_r_by_emp_name', searchable: true},
                    {data: 'r_r_date', name: 'r_r_date', searchable: false},
                    {data: 'resolve_yn', name: 'resolve_yn', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            $("#workflow_submit").attr('action', '{{ route('diag-approve-reject') }}');
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

        // ----------------------------------------

        $(".add-row").click(function () {
            let malfunction_id = $("#malfunction_id option:selected").val();
            let malfunction = $("#malfunction_id option:selected").text();
            let malfunction_other = $("#malfunction_other").val();
            let workshop_id = $(".mal_workshop_id option:selected").val();
            let workshop_name = $(".mal_workshop_id option:selected").text();
            let r_r_no = $("#r_r_no").val();
            console.log(malfunction_id, malfunction, malfunction_other, r_r_no);

            if (malfunction_id == '' || malfunction_id == '8' && malfunction_other == '' || r_r_no == '') {
                Swal.fire(
                    'Fill required value.',
                    '',
                    'error'
                )
            } else {
                let markup = "<tr role='row'>" +
                    "<td aria-colindex='1' role='cell' class='text-center'>" +
                    "<input type='checkbox' name='record' value='" + "" + "+" + "" + "'>" +
                    "<input type='hidden' name='tab_malfunction_id[]' value='" + malfunction_id + "'>" +
                    "<input type='hidden' name='tab_workshop_id[]' value='" + workshop_id + "'>" +
                    "<input type='hidden' name='tab_malfunction_other[]' value='" + malfunction_other + "'>" +
                    "<input type='hidden' name='tab_r_r_d_id[]' value='" + '' + "'>" +
                    "</td>" +
                    "<td aria-colindex='2' role='cell'>" + malfunction + "</td><td aria-colindex='2' role='cell'>" + malfunction_other + "</td><td aria-colindex='2' role='cell'>" + workshop_name + "</td>" +
                    "</tr>";
                $("#malfunction_other").val('');
                $("#malfunction_id").val('').trigger('change');
                $("#table-operator tbody").append(markup);
            }

        });

        $(".delete-row").click(function () {
            let arr_stuff = [];
            let r_r_mst_id = [];
            let r_r_d_id = [];
            $(':checkbox:checked').each(function (i) {
                arr_stuff[i] = $(this).val();
                let sd = arr_stuff[i].split('+');
                if (sd[0]) {
                    r_r_mst_id.push(sd[0]);
                    r_r_d_id.push(sd[1]);
                }
            });

            if (r_r_d_id.length != 0) {
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
                            url: '/repair-request-data-remove',
                            data: {r_r_mst_id: r_r_mst_id, r_r_d_id: r_r_d_id},
                            success: function (msg) {
                                if (msg == 0) {
                                    Swal.fire({
                                        title: 'Repair request in process!!',
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



