@extends('layouts.default')

@section('title')
    :: Repair Request
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
                                      @if(isset($mData->r_r_mst_id)) action="{{route('repair-request-update',[$mData->r_r_mst_id])}}"
                                      @else action="{{route('repair-request-post')}}" @endif method="post" onsubmit="return chkTable()">
                                    @csrf
                                    @if (isset($mData->r_r_mst_id))
                                        @method('PUT')
                                        <input type="hidden" id="r_r_mst_id" name="r_r_mst_id"
                                               value="{{isset($mData->r_r_mst_id) ? $mData->r_r_mst_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Repair Request</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Request No</label>
                                            <input type="text" readonly
                                            {{-- @if(isset($mData)) @if($mData->r_r_no!=null) readonly @endif @endif 2930 --}}
                                                   placeholder="Request No"
                                                   name="r_r_no" autocomplete="off"
                                                   id="r_r_no"
                                                   class="form-control"
                                                   {{-- value="{{isset($mData->r_r_no) ? $mData->r_r_no : ''}}" 2930--}}
                                                   value="{{isset($mData->r_r_no) ? $mData->r_r_no : 'RP'.$gen_uniq_id}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Equipment </label>
                                            <select class="custom-select select2 form-control" required id="equip_id"
                                                    name="equip_id">
                                                <option value="">Select One</option>
                                                @if(isset($eqList))
                                                    @foreach($eqList as $value)
                                                        <option value="{{$value->equip_id}}"
                                                            {{isset($mData->equip_id) && $mData->equip_id == $value->equip_id ? 'selected' : ''}}
                                                        >{{$value->equip_no.'-'.$value->equip_name.''}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Requester :</label>
                                            <select class="custom-select select2 form-control req_emp_id"
                                                    id="req_emp_id" name="r_r_by_emp_id">
                                                @if(isset($mData))
                                                    <option
                                                        value="{{$mData->r_r_by_emp_id}}">{{$mData->r_r_by_emp_code.'-'.$mData->r_r_by_emp_name.''}}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Request Date:</label>
                                            <div class="input-group date" id="datetimepicker1"
                                                 data-target-input="nearest">
                                                <input type="text" required
                                                       value="{{isset($mData->r_r_date) ? date('d-m-Y', strtotime($mData->r_r_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker1"
                                                       id="r_r_date"
                                                       name="r_r_date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
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
                                            <div class="col-sm-5" id="hide_desc" style="display: none">
                                                <label>Malfunction Description</label>
                                                <input type="text"
                                                       id="malfunction_other"
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
                                                            aria-colindex="2" class="text-center" width="20%">Malfunction
                                                            Type
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="2" class="text-center" width="40%">Description (If Any)
                                                        </th>
                                                    </tr>
                                                    </thead>

                                                    <tbody role="rowgroup" id="comp_body">
                                                    @if(!empty($dData))
                                                        @foreach($dData as $key=>$value)
                                                            <tr role="row">
                                                                <td aria-colindex="1" role="cell" class="text-center">
                                                                    <input type='checkbox' name='record'
                                                                           value="{{$value->r_r_mst_id.'+'.$value->r_r_d_id}}">
                                                                    <input type="hidden" name="tab_malfunction_id[]" value="{{$value->malfunction_id}}">
                                                                    <input type="hidden" name="tab_r_r_d_id[]" value="{{$value->r_r_d_id}}">
                                                                </td>
                                                                <td aria-colindex="7" role="cell">{{$value->malfunction}}</td>
                                                                <td aria-colindex="7" role="cell">
                                                                    <input @if($value->malfunction_other==null) readonly @endif type="text" class="form-control" name="tab_malfunction_other[]" value="{{$value->malfunction_other}}">
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
                                        @if($mData->submit_approval=='N')
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
                                                    <a type="reset" href="{{route("repair-request-index")}}"
                                                       class="btn btn-light-secondary mb-1"> Reset</a>
                                                @else
                                                    @if($mData->submit_approval=='N')
                                                        <button id="eq-req-approval" type="submit" name="approve"
                                                                value="1"
                                                                class="btn btn-primary mr-1 mb-1">
                                                            Submit For Approval
                                                        </button>
                                                    @endif
                                                    <button id="boat-employee-save" type="submit" name="update" value="1"
                                                            class="btn btn-primary mr-1 mb-1">Update
                                                    </button>
                                                    <a type="reset" href="{{route("repair-request-index")}}"
                                                       class="btn btn-light-secondary mb-1"> Back</a>
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ form default repeater -->

        </section>
    </div>

    @include('eqms.repairrequest.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);

            datePicker('#datetimepicker1');
            //maxSysDatePicker('#datetimepicker3');
            eqReqList();
        });

        $("#malfunction_id").on('change', function (e) {
            let malfunction_id = $(this).val();
            if (malfunction_id == '8') {
                $('#hide_desc').css("display", "block");
                $("#malfunction_other").val('');
            } else {
                $('#hide_desc').css("display", "none");
                $("#malfunction_other").val('');
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
            var url = '{{route('repair-request-datatable')}}';
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


        $(".add-row").click(function () {
            let malfunction_id = $("#malfunction_id option:selected").val();
            let malfunction = $("#malfunction_id option:selected").text();
            let malfunction_other = $("#malfunction_other").val();
            let r_r_no = $("#r_r_no").val();

            if (malfunction_id != '8') {
                malfunction_other = '';
            }

            if (malfunction_id == '' || malfunction_id == '8' && malfunction_other == '' || r_r_no=='') {
                Swal.fire(
                    'Fill required value.',
                    '',
                    'error'
                )
            } else {
                let markup = "<tr role='row'>" +
                    "<td aria-colindex='1' role='cell' class='text-center'>" +
                    "<input type='checkbox' name='record' value='" + "" + "+" + "" +"'>" +
                    "<input type='hidden' name='tab_malfunction_id[]' value='" + malfunction_id + "'>" +
                    "<input type='hidden' name='tab_malfunction_other[]' value='" + malfunction_other + "'>" +
                    "<input type='hidden' name='tab_r_r_d_id[]' value='" + '' + "'>" +
                    "</td><td aria-colindex='2' role='cell'>" + malfunction + "</td><td aria-colindex='2' role='cell'>" + malfunction_other + "</td></tr>";
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

        function chkTable() {
            if ($('#comp_body tr').length == 0) {
                Swal.fire({
                    title: 'Equipment Malfunction Needed!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        }

    </script>

@endsection

