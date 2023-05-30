@extends('layouts.default')

@section('title')
    :: Malfunction Type Setup
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
                                      @if(isset($mData->workshop_team_id)) action="{{route('workshop-team-update',[$mData->workshop_team_id])}}"
                                      @else action="{{route('workshop-team-post')}}" @endif method="post">
                                    @csrf
                                    @if (isset($mData->workshop_team_id))
                                        @method('PUT')
                                        <input type="hidden" id="workshop_team_id" name="workshop_team_id"
                                               value="{{isset($mData->workshop_team_id) ? $mData->workshop_team_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Workshop Team Setup</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Workshop</label>
                                            <select class="custom-select select2 form-control" required
                                                    @if(isset($mData->workshop_id))
                                                    disabled @endif
                                                    id="workshop_id" name="workshop_id">
                                                <option value="">Select One</option>
                                                @foreach($workshop as $value)
                                                    <option value="{{$value->workshop_id}}"
                                                        {{isset($mData->workshop_id) && $mData->workshop_id == $value->workshop_id ? 'selected' : ''}}
                                                    >{{$value->workshop_name}}</option>
                                                @endforeach
                                            </select>
                                            @if(isset($mData->workshop_id))
                                                <input type="hidden" id="workshop_id" name="workshop_id"
                                                       value="{{isset($mData->workshop_id) ? $mData->workshop_id : ''}}">
                                            @endif
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Team Name</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Team Name"
                                                   name="team_name"
                                                   class="form-control" required
                                                   value="{{isset($mData->team_name) ? $mData->team_name : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="250"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Valid From:</label>
                                            <div class="input-group date" id="datetimepicker"
                                                 data-target-input="nearest">
                                                <input type="text" required
                                                       value="{{isset($mData->start_date) ? date('d-m-Y', strtotime($mData->start_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker"
                                                       id="start_date"
                                                       name="start_date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Valid To:</label>
                                            <div class="input-group date" id="datetimepicker1"
                                                 data-target-input="nearest">
                                                <input type="text" required
                                                       value="{{isset($mData->end_date) ? date('d-m-Y', strtotime($mData->end_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker1"
                                                       id="end_date"
                                                       name="end_date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-1">
                                            <label>Description</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Description"
                                                   name="description"
                                                   class="form-control"
                                                   value="{{isset($mData->description) ? $mData->description : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                    </div>

                                    <h5 class="mt-1">Member Setup</h5>

                                    <hr class="mt-1">

                                    <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                        <div class="row ml-1">
                                            <div class="col-md-3">
                                                <label class="required">Team Member</label>
                                                <select class="custom-select select2 form-control team_emp_id"
                                                        id="team_emp_id" name="team_emp_id">
                                                </select>
                                                <input type="hidden" id="operator_count"
                                                       value="{{isset($operatorCount) ? $operatorCount : ''}}">
                                                <input type="hidden" id="all_operator"
                                                       value="{{isset($allOperator) ? $allOperator : ''}}">
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
                                                            aria-colindex="2" class="text-center" width="10%">Member
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
                                                                        <input type='checkbox' name='record'
                                                                               value="{{$value->member_id.'+'.$value->w_t_member_id}}">
                                                                        <input type="hidden" name="tab_w_t_member_id[]"
                                                                               value="{{$value->w_t_member_id}}">
                                                                        <input type="hidden" name="tab_workshop_team_id[]"
                                                                               value="{{$value->workshop_team_id}}">
                                                                        <input type="hidden" name="tab_member_id[]"
                                                                               value="{{$value->member_id}}">
                                                                    </td>
                                                                    <td aria-colindex="7" role="cell">
                                                                        <input type="text" class="form-control" readonly
                                                                               value="{{$value->member_name}}">
                                                                    </td>

                                                                </tr>
                                                            @endforeach
                                                        @endif
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
                                                @if(!isset($mData))
                                                    <button id="save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Save
                                                    </button>
                                                @else
                                                    <button id="update" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Update
                                                    </button>
                                                @endif

                                                <a type="reset" href="{{route("workshop-team-entry-index")}}"
                                                   class="btn btn-light-secondary mb-1"> Back</a>
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

    @include('eqms.wsteammember.list')

@endsection

@section('footer-script')

    <script type="text/javascript">
        let dataArray = new Array();

        $(".add-row").click(function () {
            let team_name = $("#team_name").val();
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();
            let emp_id = $("#team_emp_id option:selected").val();
            let data = $("#team_emp_id option:selected").text();
            let name = data.split('-');
            let emp_name = name[1];

            if (emp_id != '' && team_name != '' && start_date != '' && end_date != '') {
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
                        "<input type='hidden' name='tab_member_id[]' value='" + emp_id + "'>" +
                        "<input type='hidden' name='tab_workshop_team_id[]' value=''>" +
                        "<input type='hidden' name='tab_w_t_member_id[]' value=''>" +
                        "</td><td aria-colindex='2' role='cell'>" + emp_name + "</td></tr>";
                    $("#team_emp_id").empty('');
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
            let w_t_member_id = [];
            $(':checkbox:checked').each(function (i) {
                arr_stuff[i] = $(this).val();
                let sd = arr_stuff[i].split('+');
                operator_id.push(sd[0]);
                if (sd[1]) {
                    w_t_member_id.push(sd[1]);
                }
            });

            if (w_t_member_id.length != 0) {
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
                            url: '/team-data-remove',
                            data: {w_t_member_id: w_t_member_id},
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

        function teamList() {
            var url = '{{route('workshop-team-datatable')}}';
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
                    {data: 'workshop.workshop_name', name: 'workshop_name', searchable: true},
                    {data: 'team_name', name: 'team_name', searchable: true},
                    {data: 'start_date', name: 'start_date', searchable: true},
                    {data: 'end_date', name: 'end_date', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            datePicker('#datetimepicker');
            datePicker('#datetimepicker1');
            teamList();

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
        });

    </script>

@endsection

