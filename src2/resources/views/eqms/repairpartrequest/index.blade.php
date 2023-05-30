@extends('layouts.default')

@section('title')
    :: Workshop Activities
@endsection

@section('header-style')
    <!--Load custom style link or css-->
    <style>

    </style>
@endsection
@section('content')

    <div class="content-body">
        <section id="form-repeater-wrapper">
            <!-- form default repeater -->
            <div class="row">
                <div class="col-12">

                    @include('eqms.repairpartrequest.list')

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
                                          @if(isset($mData->r_r_mst_id)) action="{{route('repair-part-request-update',[$mData->r_r_mst_id])}}"
                                          @endif method="post">
                                        @csrf
                                        @if (isset($r_r_mst_id))
                                            @method('PUT')
                                            <input type="hidden" name="r_r_mst_id"
                                                   value="{{isset($r_r_mst_id) ? $r_r_mst_id : ''}}">
                                            <input type="hidden" name="equip_id"
                                                   value="{{isset($mData->equip_id) ? $mData->equip_id : ''}}">
                                            <input type="hidden" name="r_p_req_mst_id"
                                                   value="{{isset($mData->r_p_req_mst_id) ? $mData->r_p_req_mst_id : ''}}">
                                        @endif

                                        <h5 class="card-title">Workshop Activities</h5>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-2 mt-1">
                                                <label>Repair Request No</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($mData->r_r_no) ? $mData->r_r_no : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="250"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Equipment Name</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($mData->equip_name) ? $mData->equip_name : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-2 mt-1">
                                                <label class="required">Ticket No</label>
                                                <input type="text" required autocomplete="off"
                                                       class="form-control"
                                                       @if(isset($mData->r_p_req_ticket_no)) readonly @endif
                                                       name="r_p_req_ticket_no"
                                                       value="{{isset($mData->r_p_req_ticket_no) ? $mData->r_p_req_ticket_no : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Team Selection</label>
                                                <select class="custom-select form-control select2" required
                                                        id="for_whom"
                                                        name="workshop_team_id[]" multiple="multiple">
                                                    <option value="">Select One</option>

                                                    @foreach($teams as $value)
                                                        <option value="{{$value->workshop_team_id}}"
                                                        @if(!empty($whom_ids))
                                                            @foreach($whom_ids as $id)
                                                                @if($value->workshop_team_id == $id) {{'selected="selected"'}} @endif
                                                                @endforeach
                                                            @endif >
                                                            {{$value->team_name.' ('.$value->workshop_name.')'}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="col-md-2 mt-1">
                                                <div class="form-group">
                                                    <label class="mb-1">Resolved?</label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                   name="status" id="active_no" checked
                                                                   value="{{ \App\Enums\YesNoFlag::NO }}"
                                                                   @if(isset($mData->status) && $mData->status == "N") checked @endif/>
                                                            <label class="form-check-label">NO</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                   name="status"
                                                                   id="active_yes"
                                                                   value="{{ \App\Enums\YesNoFlag::YES }}"
                                                                   @if(isset($mData->status) && $mData->status == "Y") checked @endif/>
                                                            <label class="form-check-label">YES</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                            <legend class="w-auto" style="font-size: 20px;">Previous Malfunction Found
                                            </legend>
                                            <div class="col-sm-12 mt-1">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped table-bordered">
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
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup">
                                                        @if(isset($diagTeamDtl))
                                                            @foreach($diagTeamDtl as $key=>$value)
                                                                <tr role="row">
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->malfunction}}</td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->malfunction_other}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </fieldset>

                                        <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                            <legend class="w-auto" style="font-size: 20px;">Malfunction After Workshop
                                                Diagnosis
                                            </legend>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="required">Malfunction Type</label>
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
                                                <div class="col-md-9">
                                                    <label>Description(If Any)</label>
                                                    <input type="text" autocomplete="off"
                                                           class="form-control"
                                                           id="description"
                                                    >
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <label class="required">Requested Part</label>
                                                    <select class="select2 form-control pl-0 pr-0"
                                                            id="part_id">
                                                        <option value="">Select One</option>
                                                        @if(isset($allPart))
                                                            @foreach($allPart as $value)
                                                                <option value="{{$value->part_id}}">
                                                                    {{$value->part_name.' ('.$value->part_no.')'}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <label class="required">Quantity</label>
                                                    <input type="number" autocomplete="off"
                                                           class="form-control"
                                                           id="quantity"
                                                    >
                                                </div>
                                                <div class="col-md-3 mt-1">
                                                    <label>Diagnosis By</label>
                                                    <select class="custom-select select2 form-control team_emp_id"
                                                            id="team_emp_id" name="team_emp_id">
                                                    </select>
                                                </div>

                                                <div class="col-sm-1 mt-1" align="right">
                                                    <div id="start-no-field">
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
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Malfunction Type
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                Description (If Any)
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Requested Part
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Quantity
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="10%">
                                                                Diagnosis By
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">
                                                        @if(isset($dData))
                                                            @if(!empty($dData))
                                                                @foreach($dData as $key=>$value)
                                                                    <tr role="row">
                                                                        <td aria-colindex="1" role="cell"
                                                                            class="text-center">
                                                                            @if($mData->status!='Y')
                                                                                <input type='checkbox' name='record'
                                                                                       value="{{$value->r_p_req_dtl_id}}">
                                                                                <input type="hidden"
                                                                                       name="tab_r_p_req_dtl_id[]"
                                                                                       value="{{$value->r_p_req_dtl_id}}">
                                                                                <input type="hidden"
                                                                                       name="tab_r_p_req_mst_id[]"
                                                                                       value="{{$value->r_p_req_mst_id}}">
                                                                                <input type="hidden"
                                                                                       name="tab_diag_by_id[]"
                                                                                       value="{{$value->diag_by_id}}">
                                                                                <input type="hidden"
                                                                                       name="tab_part_id[]"
                                                                                       value="{{$value->part_id}}">
                                                                                <input type="hidden"
                                                                                       name="tab_malfunction_id[]"
                                                                                       value="{{$value->malfunction_type_id}}">
                                                                            @endif
                                                                        </td>
                                                                        <td aria-colindex="7" role="cell">
                                                                            {{$value->malfunction}}
                                                                        </td>
                                                                        <td aria-colindex="7" role="cell">
                                                                            <input type="text" class="form-control"
                                                                                   name="tab_description[]"
                                                                                   value="{{$value->remarks}}">
                                                                        </td>
                                                                        <td aria-colindex="7" role="cell">
                                                                            {{$value->part_name}}
                                                                        </td>
                                                                        <td aria-colindex="7" role="cell">
                                                                            <input type="text" class="form-control"
                                                                                   name="tab_quantity[]"
                                                                                   value="{{$value->req_qty}}">
                                                                        </td>
                                                                        <td aria-colindex="7" role="cell">
                                                                            {{$value->diag_by_name}}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                            @if($mData->status!='Y')
                                                <div class="col-12 d-flex justify-content-start">

                                                    <button type="button"
                                                            class="btn btn-primary mb-1 delete-row">
                                                        Delete
                                                    </button>
                                                </div>
                                            @endif
                                        </fieldset>

                                        <div class="form-group mt-1">
                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                <div class="form-group">
                                                    @if(isset($mData))
                                                        @if($mData->status=='Y')
                                                            <a type="reset"
                                                               href="{{route("repair-part-request-index")}}"
                                                               class="btn btn-light-secondary mb-1"> Back</a>
                                                        @else
                                                            <button id="eq-req-approval" type="submit"
                                                                    class="btn btn-primary mr-1 mb-1">Submit
                                                            </button>
                                                            <a type="reset"
                                                               href="{{route("repair-part-request-index")}}"
                                                               class="btn btn-light-secondary mb-1"> Back</a>
                                                        @endif
                                                    @endif

                                                </div>
                                            </div>
                                        </div>

                                    </form>
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
        $(".add-row").click(function () {
            let description = $("#description").val();
            let quantity = $("#quantity").val();
            let malfunction_id = $("#malfunction_id option:selected").val();
            let malfunction = $("#malfunction_id option:selected").text();

            let part_id = $("#part_id option:selected").val();
            let part = $("#part_id option:selected").text();

            let team_emp_id = $("#team_emp_id option:selected").val();
            if (!team_emp_id) {
                team_emp_id = '';
            }
            let team_emp = $("#team_emp_id option:selected").text();

            if (malfunction_id != '' && part_id != '' && quantity != '') {
                let markup = "<tr role='row'>" +
                    "<td aria-colindex='1' role='cell' class='text-center'>" +
                    "<input type='checkbox' name='record' value=''>" +
                    "<input type='hidden' name='tab_malfunction_id[]' value='" + malfunction_id + "'>" +
                    "<input type='hidden' name='tab_part_id[]' value='" + part_id + "'>" +
                    "<input type='hidden' name='tab_quantity[]' value='" + quantity + "'>" +
                    "<input type='hidden' name='tab_diag_by_id[]' value='" + team_emp_id + "'>" +
                    "<input type='hidden' name='tab_description[]' value='" + description + "'>" +
                    "</td><td aria-colindex='2' role='cell'>" + malfunction + "</td>" +
                    "<td aria-colindex='2' role='cell'>" + description + "</td>" +
                    "<td aria-colindex='2' role='cell'>" + part + "</td>" +
                    "<td aria-colindex='2' role='cell'>" + quantity + "</td>" +
                    "<td aria-colindex='2' role='cell'>" + team_emp + "</td></tr>";
                $("#team_emp_id").empty('');
                $("#malfunction_id").val('').trigger('change');
                $("#part_id").val('').trigger('change');
                $("#description").val('');
                $("#quantity").val('');
                $("#table-operator tbody").append(markup);
            } else {
                Swal.fire(
                    'Fill required value.',
                    '',
                    'error'
                )
            }
        });

        $(".delete-row").click(function () {
            let r_p_req_dtl_id = [];
            $(':checkbox:checked').each(function (i) {
                r_p_req_dtl_id[i] = $(this).val();
            });

            if (r_p_req_dtl_id.length != 0) {
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
                            url: '/ws-dtl-data-remove',
                            data: {r_p_req_dtl_id: r_p_req_dtl_id},
                            success: function (msg) {
                                if (msg == 0) {
                                    Swal.fire({
                                        title: 'Error in deleting data.',
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
            }
        });

        $('.team_emp_id').select2({
            placeholder: "Select one",
            ajax: {
                url: '/get-employee',
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
            let url = '{{route('repair-part-request-datatable')}}';
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
                    {data: 'r_r_no', name: 'r_r_no', searchable: true},
                    {data: 'equip_name', name: 'equip_name', searchable: true},
                    {data: 'status', name: 'status', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            var r_r_mst_id = '{{isset($mData->r_r_mst_id) ? $mData->r_r_mst_id : ''}}';

            if (r_r_mst_id) {
                $("html, body").animate({scrollTop: $(document).height()}, 1000);
            }

            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);

            eqReqList();
        });

    </script>

@endsection

