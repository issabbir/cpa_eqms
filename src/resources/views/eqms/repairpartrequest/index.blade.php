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

                    @if (Session::has('message'))
                        <div class="alert {{ Session::get('m-class') ? Session::get('m-class') : 'alert-danger' }} show"
                             role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @include('eqms.repairpartrequest.list')

                    @if (isset($mData))
                        <div class="card">


                            <div class="card-content">
                                <div class="card-body">
                                    <form enctype="multipart/form-data"
                                          @if (isset($mData->r_r_mst_id)) action="{{ route('repair-part-request-update') }}"
                                          @endif
                                          method="POST">
                                        @csrf
                                        @if (isset($mData->r_r_mst_id))
                                            {{-- @method('PUT') --}}
                                            <input type="hidden" name="r_r_mst_id"
                                                   value="{{ isset($mData->r_r_mst_id) ? $mData->r_r_mst_id : '' }}">
                                        @endif

                                        <h5 class="card-title">Workshop Activities</h5>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-3 mt-1">
                                                <label>Request No</label>
                                                <input type="text" disabled class="form-control"
                                                       value="{{ isset($mData->r_r_no) ? $mData->r_r_no : '' }}">
                                            </div>

                                            <div class="col-md-3 mt-1">
                                                <label>Equipment</label>
                                                <input type="text" disabled class="form-control"
                                                       value="{{ isset($mData->equip_name) ?  $mData->equip_no.' - '.$mData->equip_name : '' }}">
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Request By</label>
                                                <input type="text" disabled class="form-control"
                                                       value="{{ isset($mData->r_r_by_emp_name) ? $mData->r_r_by_emp_name : '' }}">
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Request Date</label>
                                                <input type="text" disabled class="form-control"
                                                       value="{{ isset($mData->r_r_date) ? date('d-m-Y', strtotime($mData->r_r_date)) : '' }}">
                                            </div>
                                            {{--                                            <div class="col-md-3 mt-1">--}}
                                            {{--                                                <label>Diagnosis No</label>--}}
                                            {{--                                                <input type="text" disabled autocomplete="off"--}}
                                            {{--                                                       name="r_d_no"--}}
                                            {{--                                                       class="form-control"--}}
                                            {{--                                                       value="{{isset($rdMst->r_d_no) ? $rdMst->r_d_no : $mData->r_r_no}}"--}}
                                            {{--                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"--}}
                                            {{--                                                       maxlength="250"--}}
                                            {{--                                                >--}}
                                            {{--                                            </div>--}}
                                            <div class="col-md-3 mt-1">
                                                <label>Diagnosis Date:</label>
                                                <div class="input-group date" id="datetimepicker1"
                                                     data-target-input="nearest">
                                                    <input type="text" disabled
                                                           value="{{ isset($rdMst->r_d_date) ? date('d-m-Y', strtotime($rdMst->r_d_date)) : '' }}"
                                                           class="form-control datetimepicker-input"
                                                           data-toggle="datetimepicker" data-target="#datetimepicker1"
                                                           id="r_d_date" name="r_d_date" autocomplete="off"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-1">
                                                <label>Team Selection</label>
                                                <select class="custom-select form-control select2" disabled
                                                        id="for_whom"
                                                        name="workshop_team_id[]" multiple="multiple">
                                                    <option value="">Select One</option>

                                                    @foreach ($teams2 as $value)
                                                        <option value="{{ $value->workshop_team_id }}"
                                                        @if (!empty($whom_ids2)) @foreach ($whom_ids2 as $id)
                                                            @if ($value->workshop_team_id == $id)
                                                                {{ 'selected="selected"' }} @endif
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
                                                <input type="text" autocomplete="off" placeholder="Description"
                                                       name="description" disabled
                                                       class="form-control"
                                                       value="{{ isset($rdMst->description) ? $rdMst->description : '' }}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="500">
                                            </div>
                                        </div>

                                        <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                            <div class="col-sm-12 mt-1">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped table-bordered"
                                                           id="table-operator">
                                                        <thead>
                                                        <tr>
                                                            <th role="columnheader" scope="col" aria-colindex="2"
                                                                class="text-center"
                                                                width="20%">
                                                                Malfunction
                                                                Type
                                                            </th>
                                                            <th role="columnheader" scope="col" aria-colindex="2"
                                                                class="text-center"
                                                                width="40%">
                                                                Description (If Any)
                                                            </th>
                                                            <th role="columnheader" scope="col" aria-colindex="2"
                                                                class="text-center"
                                                                width="20%">
                                                                Repairing Workshop
                                                            </th>
                                                            <th role="columnheader" scope="col" aria-colindex="2"
                                                                class="text-center"
                                                                width="20%">
                                                                Resolve Date
                                                            </th>
                                                            <th role="columnheader" scope="col" aria-colindex="2"
                                                                class="text-center"
                                                                width="40%">
                                                                Resolved?
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">
                                                        @if (!empty($dData2))
                                                            @foreach ($dData2 as $key => $value)
                                                                <tr role="row">
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{ $value->malfunction }}</td>
                                                                    <td aria-colindex="7" role="cell">
                                                                        <input readonly type="text" class="form-control"
                                                                               name="tab_malfunction_other[]"
                                                                               value="{{ $value->malfunction_other }}">
                                                                        <input type="hidden" name="tab_r_r_d_id[]"
                                                                               value="{{ $value->r_r_d_id }}">
                                                                        <input type="hidden" name="tab_malfunction_id[]"
                                                                               value="{{ $value->malfunction_id }}">
                                                                    </td>
                                                                    {{-- dom --}}
                                                                    <td aria-colindex="7" role="cell">
                                                                        {{ isset($value->repair_workshop_name) ? $value->repair_workshop_name : '--' }}
                                                                    </td>
                                                                    <td aria-colindex="5" role="cell"
                                                                        id="training_date_pick_{{$key + 1}}"
                                                                        onclick="call_date_picker(this)"
                                                                        data-target-input="nearest">
                                                                        <input type="text"
                                                                               autocomplete="off"
                                                                               class="form-control datetimepicker-input"
                                                                               data-toggle="datetimepicker"
                                                                               data-target="#training_date_pick_{{$key + 1}}"
                                                                               name="training_date[]"
                                                                               value="{{isset($value->resolve_date) ? date('d-m-Y', strtotime($value->resolve_date)) : ''}}"
                                                                               data-predefined-date=""
                                                                        >
                                                                    </td>
                                                                    <td aria-colindex="7" role="cell">
                                                                        <div class="custom-control custom-switch">
                                                                            <select class="form-control"
                                                                                    name="resolve_yn[]">
                                                                                <option
                                                                                    @if (isset($value->resolve_yn) && $value->resolve_yn == 'N') selected
                                                                                    @endif
                                                                                    value="N">No
                                                                                </option>
                                                                                <option
                                                                                    @if (isset($value->resolve_yn) && $value->resolve_yn == 'Y') selected
                                                                                    @endif
                                                                                    value="Y">Yes
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>

                                            <div class="row">

                                                {{-- dom --}}

                                                <div class="col-md-2 ml-2 mt-1">
                                                    <div class="form-group">
                                                        <label class="mb-1 required">Force Resolve?</label>
                                                        <div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                       name="force_resolve_yn" id="force_y"
                                                                       @if ($mData->force_resolve_yn =='Y')
                                                                       checked
                                                                       disabled
                                                                       @endif
                                                                       value=" {{\App\Enums\YesNoFlag::YES }}"/>
                                                                <label class="form-check-label">Yes</label>
                                                            </div>

                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                       name="force_resolve_yn" id="force_n"
                                                                       @if ($mData->force_resolve_yn =='Y')
                                                                       disabled
                                                                       @endif
                                                                       @if ($mData->force_resolve_yn !='Y')
                                                                       checked
                                                                       @endif
                                                                       value="{{ \App\Enums\YesNoFlag::NO }}"/>

                                                                <label
                                                                    class="form-check-label">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-5 mt-1 " id="force_resolve_div">
                                                    <div class="form-group">
                                                        <label for="force_resolve" id="resolve_text" class="required">Resolve
                                                            Note/Task:</label>
                                                        <textarea class="form-control " required rows="2"
                                                                  id="force_resolve"
                                                                  @if ($mData->force_resolve_reason)
                                                                  disabled
                                                                  @endif
                                                                  name="force_resolve_reason"> @if ($mData->force_resolve_reason) {{$mData->force_resolve_reason}} @endif</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 mt-1" id="force_resolve_date">
                                                    <label class="required">Resolve Date:</label>
                                                    <div class="input-group date" id="datetimepicker3"
                                                         data-target-input="nearest">
                                                        <input type="text"
                                                               required
                                                               @if($mData->force_resolve_date)
                                                               disabled
                                                               @endif
                                                               value="{{ !empty($mData->force_resolve_date) ? date('d-m-Y',strtotime($mData['force_resolve_date'])) : '' }} "
                                                               class="form-control datetimepicker-input"
                                                               data-toggle="datetimepicker"
                                                               data-target="#datetimepicker3"
                                                               id="service_start_date"
                                                               name="force_resolve_date"
                                                               autocomplete="off"
                                                        />
                                                    </div>
                                                </div>

                                            </div>

                                        </fieldset>

                                        <div class="form-group mt-1">
                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                <div class="form-group">
                                                    @if (isset($mData))
                                                        @if ($mData->status == 'Y')
                                                            <a type="reset"
                                                               href="{{ route('repair-part-request-index') }}"
                                                               class="btn btn-light-secondary mb-1"> Back</a>
                                                        @else

                                                            {{--@if ($mData->req_status_id != 4)--}}
                                                            {{--                                                                <button id="eq-req-approval" type="submit" name="submit"--}}
                                                            {{--                                                                        class="btn btn-primary mr-1 mb-1"--}}
                                                            {{--                                                                        value="Submit"> Submit--}}
                                                            {{--                                                                </button>--}}
                                                            {{--                                                            @else--}}

                                                            {{--                                                                @php--}}
                                                            {{--                                                                    $url = App\Entities\Security\Menu::where('menu_id', 42)--}}
                                                            {{--                                                                            ->where('module_id', 45)--}}
                                                            {{--                                                                            ->first()--}}
                                                            {{--                                                                            ->base_url;--}}
                                                            {{--                                                                @endphp--}}
                                                            {{--                                                                <a target="_blank"--}}
                                                            {{--                                                                   href="{{externalLoginUrl($url, '/create-item-demand?module_id=59&ref=' . $mData->r_r_mst_id)}}"--}}
                                                            {{--                                                                   class="btn btn-primary mb-1"> Item Demand</a>--}}
                                                            {{--                                                            @endif--}}

                                                            @if ($mData->resolve_yn != 'Y' )
                                                                <button id="eq-req-approval" type="submit" name="submit"
                                                                        class="btn btn-primary mr-1 mb-1"
                                                                        value="Submit"> Submit
                                                                </button>
                                                            @endif

                                                            {{--                                                            @if($mData->is_demand_yn == 'N' )--}}
                                                            {{--                                                                @php--}}
                                                            {{--                                                                    $url = App\Entities\Security\Menu::where('menu_id', 42)--}}
                                                            {{--                                                                            ->where('module_id', 45)--}}
                                                            {{--                                                                            ->first()--}}
                                                            {{--                                                                            ->base_url;--}}
                                                            {{--                                                                @endphp--}}
                                                            {{--                                                                <a target="_blank"--}}
                                                            {{--                                                                   href="{{externalLoginUrl($url, '/create-item-demand?module_id=59&ref=' . $mData->r_r_mst_id)}}"--}}
                                                            {{--                                                                   class="btn btn-primary mb-1"> Item Demand</a>--}}
                                                            {{--                                                            @endif--}}

                                                            <a type="reset"
                                                               href="{{ route('repair-part-request-index') }}"
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
        function datePicker(selector) {
            var elem = $(selector);
            elem.datetimepicker({
                format: 'DD-MM-YYYY',
                ignoreReadonly: true,
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'top'
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
            let preDefinedDate = elem.attr('data-predefined-date');

            if (preDefinedDate) {
                let preDefinedDateMomentFormat = moment(preDefinedDate, "YYYY-MM-DD").format("YYYY-MM-DD");
                elem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
            }
        }

        function call_date_picker(e) {
            datePicker(e);
        }

        $(document).ready(function () {
            maxSysDatePicker('#datetimepicker3');
        });


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
                            data: {
                                r_p_req_dtl_id: r_p_req_dtl_id
                            },
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
            let url = '{{ route('repair-part-request-datatable') }}';
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
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                    {
                        data: 'r_r_no',
                        name: 'r_r_no',
                        searchable: true
                    },
                    {
                        data: 'equip_name',
                        name: 'equip_name',
                        searchable: true
                    },
                    {
                        data: 'workshop_name',
                        name: 'workshop_name',
                        searchable: false
                    },
                    // {data: 'equip_model', name: 'equip_model', searchable: true},
                    // {data: 'r_r_by_emp_name', name: 'r_r_by_emp_name', searchable: true},
                    {
                        data: 'r_r_date',
                        name: 'r_r_date',
                        searchable: false
                    },
                    {
                        data: 'repair_workshop_name',
                        name: 'repair_workshop_name',
                        searchable: false
                    },
                    {
                        data: 'resolve_date',
                        name: 'resolve_date',
                        searchable: false
                    },
                    {
                        data: 'detail_status',
                        name: 'detail_status',
                        searchable: false
                    },
                    /*{data: 'status', name: 'status', searchable: false},*/
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false
                    },
                ]
            });
        };


        // dom
        let checked_yn = $('input:radio[name="force_resolve_yn"]:checked').val();
        $('#force_resolve').prop('required', true);


        if (checked_yn == 'N') {
            // $('#force_resolve_div').addClass('hidden');
            $('#force_resolve_date').addClass('hidden');
            $('#eq-req-approval').text('Submit');
            $('#eq-req-approval').val('Submit');

        }

        $(document).on('click', '#force_y', function () {
            $('#eq-req-approval').removeClass('btn-primary');
            $('#eq-req-approval').addClass('btn-danger');
            $('#eq-req-approval').text('Forced Submit');
            $('#eq-req-approval').val('Forced Submit');
            // $('#force_resolve_div').removeClass('hidden');
            $('#force_resolve_date').removeClass('hidden');
            $("#force_n").prop('checked', false);
            $("#force_y").prop('checked', true);
            $("#resolve_text").text('Resolve Reason');
        });

        $(document).on('click', '#force_n', function () {
            $('#eq-req-approval').removeClass('btn-danger');
            $('#eq-req-approval').addClass('btn-primary');
            $('#eq-req-approval').text('Submit');
            $('#eq-req-approval').val('Submit');
            // $('#force_resolve_div').addClass('hidden');
            $('#force_resolve_date').addClass('hidden');
            $("#force_y").prop('checked', false);
            $("#force_n").prop('checked', true);
            $("#resolve_text").text('Resolve Note/Task');


        });


        $(document).ready(function () {
            var r_r_mst_id = '{{ isset($mData->r_r_mst_id) ? $mData->r_r_mst_id : '' }}';

            if (r_r_mst_id) {
                $("html, body").animate({
                    scrollTop: $(document).height()
                }, 1000);
            }

            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 15000);

            eqReqList();
        });


    </script>
@endsection
