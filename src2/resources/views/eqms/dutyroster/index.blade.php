@extends('layouts.default')

@section('title')
    :: Duty Roster
@endsection

@section('header-style')
    <!--Load custom style link or css-->

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
                                      @if(isset($mData->r_m_id)) action="{{route('duty-roster-update',[$mData->r_m_id])}}"
                                      @else action="{{route('duty-roster-post')}}" @endif method="post" onsubmit="return chkTable()">
                                    @csrf
                                    @if (isset($mData->r_m_id))
                                        @method('PUT')
                                        <input type="hidden" id="r_m_id" name="r_m_id"
                                               value="{{isset($mData->r_m_id) ? $mData->r_m_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Duty Roster</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="required">Roster Name</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Roster Name"
                                                   oninput="this.value = this.value.toUpperCase()"
                                                   name="r_name" id="r_name"
                                                   class="form-control" required
                                                   value="{{isset($mData->r_name) ? $mData->r_name : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="100"
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label>Roster Name Bangla</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Roster Name Bangla"
                                                   name="r_name_bn"
                                                   class="form-control"
                                                   value="{{isset($mData->r_name_bn) ? $mData->r_name_bn : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="3000"
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="required">Roster Date:</label>
                                            <div class="input-group date" id="datetimepicker3"
                                                 data-target-input="nearest">
                                                <input type="text" required
                                                       value="{{isset($mData->r_date) ? date('d-m-Y', strtotime($mData->r_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker3"
                                                       id="r_date"
                                                       name="r_date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="required">Shift</label>
                                            <select class="custom-select select2 form-control" required id="rs_id"
                                                    name="rs_id">
                                                <option value="">Select One</option>
                                                @foreach($shiftList as $value)
                                                    <option value="{{$value->rs_id}}"
                                                        {{isset($mData->rs_id) && $mData->rs_id == $value->rs_id ? 'selected' : ''}}
                                                    >{{$value->rs_name.' ('.date('h:i A', strtotime($value->rs_start_time)).' ~ '.date('h:i A', strtotime($value->rs_end_time)).')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control"
                                                          aria-label="Description" id="description"
                                                          name="description"
                                                          placeholder="Enter Description"
                                                ><?php echo e(old('description', isset($mData->description) ? $mData->description : '')); ?></textarea>
                                                <small class="text-muted form-text"></small>
                                            </div>
                                        </div>

                                    </div>

                                    <h5 class="mt-1">Roster Setup</h5>

                                    <hr class="mt-1">

                                    <fieldset class="border p-1 mt-1 mb-1 col-sm-12">
                                        <div class="row ml-1">
                                            <div class="col-sm-3">
                                                <label for="review_by_id" class="required">Operator :</label>
                                                <select class="custom-select select2 form-control operator_id"
                                                        id="operator_id">
                                                </select>
                                                <input type="hidden" id="operator_count"
                                                       value="{{isset($operatorCount) ? $operatorCount : ''}}">
                                                <input type="hidden" id="all_operator"
                                                       value="{{isset($allOperator) ? $allOperator : ''}}">
                                                <span class="text-danger"></span>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="required">Location</label>
                                                    <select class="select2 form-control pl-0 pr-0 location_id"
                                                            id="location_id">
                                                        <option value="">Select One</option>
                                                        @if(isset($locationList))
                                                            @foreach($locationList as $value)
                                                                <option value="{{$value->location_id}}">
                                                                    {{$value->location}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-1" align="right">
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
                                                            aria-colindex="2" class="text-center" width="10%">Operator
                                                        </th>
                                                        <th role="columnheader" scope="col"
                                                            aria-colindex="4" class="text-center" width="2%">Location
                                                        </th>
                                                    </tr>
                                                    </thead>

                                                    <tbody role="rowgroup" id="comp_body">
                                                    @if(!empty($dData))
                                                        @foreach($dData as $key=>$value)
                                                            <tr role="row">
                                                                <td aria-colindex="1" role="cell" class="text-center">
                                                                    <input type='checkbox' name='record'
                                                                           value="{{$value->operator_id.'+'.$value->r_d_id}}">
                                                                    <input type="hidden" name="r_d_id[]"
                                                                           value="{{$value->r_d_id}}"
                                                                           class="r_d_id">
                                                                    <input type="hidden" name="tab_operator_id[]"
                                                                           value="{{$value->operator_id}}">
                                                                </td>
                                                                <td aria-colindex="7" role="cell">
                                                                    <input type="text" class="form-control"
                                                                           name="tab_operator_name[]" readonly
                                                                           value="{{$value->operator_name}}">
                                                                </td>
                                                                <td aria-colindex="2" role="cell">
                                                                    <select
                                                                        class="custom-select form-control select2 tab_location_id"
                                                                        id="tab_location_id_{{$key + 1}}"
                                                                        name="tab_location_id[]">
                                                                        <option value="">Select One</option>
                                                                        @foreach($locationList as $values)
                                                                            <option value="{{$values->location_id}}"
                                                                                {{isset($value->location_id) && $value->location_id == $values->location_id ? 'selected' : ''}}
                                                                            >{{$values->location}}</option>
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
                                        <div class="col-12 d-flex justify-content-start">

                                            <button type="button"
                                                    class="btn btn-primary mb-1 delete-row">
                                                Delete
                                            </button>
                                        </div>
                                    </fieldset>

                                    <div class="form-group">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                @if(!isset($mData))
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Save
                                                    </button>
                                                    <a type="reset" href="{{route("duty-roster-index")}}"
                                                       class="btn btn-light-secondary mb-1"> Back</a>
                                                @else
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Update
                                                    </button>
                                                    <a type="reset" href="{{route("duty-roster-index")}}"
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

    @include('eqms.dutyroster.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        var dataArray = new Array();

        $(".add-row").click(function () {
            let rs_id = $("#rs_id option:selected").val();
            //let year = $("#r_year_id option:selected").val();
            let r_name = $("#r_name").val();
            let r_date = $("#r_date").val();

            let location_id = $("#location_id option:selected").val();
            let location = $("#location_id option:selected").text();

            let emp_id = $("#operator_id option:selected").val();
            let data = $("#operator_id option:selected").text();
            let name = data.split('-');
            let emp_name = name[1];

            if (emp_id && rs_id && r_name && r_date && location_id) {
                if ($.inArray(emp_id, dataArray) > -1) {
                    Swal.fire(
                        'Duplicate value not allowed.',
                        '',
                        'error'
                    )
                } else {
                    let markup = "<tr role='row'>" +
                        "<td aria-colindex='1' role='cell' class='text-center'>" +
                        "<input type='checkbox' name='record' value='" + emp_id + "+" + "" + "'>" +
                        "<input type='hidden' name='tab_operator_id[]' value='" + emp_id + "'>" +
                        "<input type='hidden' name='tab_location_id[]' value='" + location_id + "'>" +
                        "</td><td aria-colindex='2' role='cell'>" + emp_name + "</td><td aria-colindex='2' role='cell'>" + location + "</td></tr>";
                    $("#operator_id").empty('');
                    $("#location_id").val('').trigger('change');
                    $("#table-operator tbody").append(markup);
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

        $(".delete-row").click(function () {
            let arr_stuff = [];
            let operator_id = [];
            let r_d_id = [];
            $(':checkbox:checked').each(function (i) {
                arr_stuff[i] = $(this).val();
                let sd = arr_stuff[i].split('+');
                operator_id.push(sd[0]);
                if (sd[1]) {
                    r_d_id.push(sd[1]);
                }
            });

            if (r_d_id.length != 0) {
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
                            url: '/roster-data-remove',
                            data: {r_d_id: r_d_id},
                            success: function (msg) {
                                if (msg == 0) {
                                    Swal.fire({
                                        title: 'Can not remove data. Attedeance process ongoing for this schedule.',
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
                                        for (var i = dataArray.length - 1; i >= 0; i--) {
                                            for (var j = 0; j < operator_id.length; j++) {
                                                if (dataArray[i] === operator_id[j]) {
                                                    dataArray.splice(i, 1);
                                                }
                                            }
                                        }
                                        $('td input:checked').closest('tr').remove();
                                    });
                                }
                            }
                        });
                    }
                });
            } else {
                for (var i = dataArray.length - 1; i >= 0; i--) {
                    for (var j = 0; j < operator_id.length; j++) {
                        if (dataArray[i] === operator_id[j]) {
                            dataArray.splice(i, 1);
                        }
                    }
                }
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

        $('.operator_id').select2({
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

        function rosterList() {
            var url = '{{route('duty-roster-datatable')}}';
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
                    {data: 'r_name', name: 'r_name', searchable: true},
                    {data: 'r_date', name: 'r_date', searchable: true},
                    {data: 'r_year', name: 'r_year', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);

            var operator_count = $("#operator_count").val();
            var all_operator = $("#all_operator").val();
            var arr_allTrainee = []
            try {
                arr_allTrainee = JSON.parse(all_operator);
            } catch (e) {
                console.log("Invalid json")
            }
            if (operator_count) {
                let i;
                for (i = 0; i < operator_count; i++) {
                    dataArray.push(arr_allTrainee[i]);
                }
            }
            //datePicker('#datetimepicker3');
            minSysDatePicker('#datetimepicker3');
            rosterList();
        });

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

        function chkTable() {
            if ($('#comp_body tr').length == 0) {
                Swal.fire({
                    title: 'Roster Setup Needed!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        }

    </script>

@endsection

