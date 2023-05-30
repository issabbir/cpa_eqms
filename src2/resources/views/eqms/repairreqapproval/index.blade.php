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

                    @include('eqms.repairreqapproval.list')

                    @if(isset($mData))
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <form>
                                        @csrf
                                        @if (isset($mData->r_r_mst_id))
                                            @method('PUT')
                                            <input type="hidden" id="r_r_mst_id" name="r_r_mst_id"
                                                   value="{{isset($mData->r_r_mst_id) ? $mData->r_r_mst_id : ''}}">
                                        @endif

                                        <h5 class="card-title">Repair Request Approval</h5>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-3 mt-1">
                                                <label>Request No</label>
                                                <input type="text" readonly
                                                       class="form-control"
                                                       value="{{isset($mData->r_r_no) ? $mData->r_r_no : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Equipment</label>
                                                <input type="text" readonly
                                                       class="form-control"
                                                       value="{{isset($mData->equip_id) ? $mData->equip_no.'-'.$mData->equip_name.'' : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Requester</label>
                                                <input type="text" readonly
                                                       class="form-control"
                                                       value="{{isset($mData->r_r_by_emp_name) ? $mData->r_r_by_emp_name : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Request Date</label>
                                                <input type="text" readonly
                                                       class="form-control"
                                                       value="{{isset($mData->r_r_date) ? date('d-m-Y', strtotime($mData->r_r_date)) : ''}}"
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
                                                                aria-colindex="2" class="text-center" width="40%">
                                                                Malfunction
                                                                Type
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="60%">
                                                                Description (If Any)
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">
                                                        @if(!empty($mdData))
                                                            @foreach($mdData as $key=>$value)
                                                                <tr role="row">
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->malfunction}}
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->malfunction_other}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </fieldset>

                                        <fieldset>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @if(count($workflows) > 0)
                                                @include('eqms.repairreqapproval.workflow')
                                            @endif
                                        </fieldset>

                                        <div class="form-group mt-1">
                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                <div class="form-group">
                                                    @if(isset($curr_data)){{--{{dd($curr_data)}}--}}
                                                    @if($curr_data->current_yn == 'Y')
                                                        <button
                                                            class="btn btn btn-success shadow mr-1 mb-1 btn-primary rejectReq"
                                                            style="color: white"
                                                            onclick="commentWin({{$mData->r_r_mst_id}},{{$curr_data->approval_info_id}},'F')"
                                                            type="button">@if($next_data!=null) Forward @else
                                                                Approve @endif
                                                        </button>
                                                    @endif
                                                    @if($curr_data->approval_ref_seq != $approvalData[0] && $curr_data->approval_status_id != 1)
                                                        <button class="btn btn-dark mr-1 mb-1 btn-danger rejectReq"
                                                                type="button"
                                                                onclick="commentWin({{$mData->r_r_mst_id}},{{$curr_data->approval_info_id}},'R')"
                                                                style="color: white"> Reject
                                                        </button>
                                                    @endif
                                                    @if($curr_data->approval_ref_seq != $approvalData[0] && $curr_data->approval_status_id != 1)
                                                        <button
                                                            class="btn btn btn-success shadow mr-1 mb-1 btn-danger rejectReq"
                                                            type="button"
                                                            onclick="commentWin({{$mData->r_r_mst_id}},{{$curr_data->approval_info_id}},'B')"
                                                            style="color: white">Backward
                                                        </button>
                                                    @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </form>

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

        function eqReqList() {
            var url = '{{route('repair-request-approval-datatable')}}';
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
                    {data: 'r_r_by_emp_name', name: 'r_r_by_emp_name', searchable: true},
                    {data: 'equip_no', name: 'equip_no', searchable: false},
                    {data: 'r_r_date', name: 'r_r_date', searchable: false},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            var r_r_mst_id = '{{isset($mData->r_r_mst_id) ? $mData->r_r_mst_id : ''}}';

            if (r_r_mst_id) {
                $("html, body").animate({scrollTop: $(document).height()}, 1000);
            }
            eqReqList();
            $("#workflow_submit").attr('action', '{{ route('repair-approve-reject') }}');
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);
        });

    </script>

@endsection

