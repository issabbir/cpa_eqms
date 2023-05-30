@extends('layouts.default')

@section('title')
    :: Spare Parts Request
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

                     <form enctype="multipart/form-data" method="post" action="{{route('spare-parts-stock-post')}}" onsubmit="return chkTable()">
{{--                                      @if(isset($masterData->s_p_req_mst_id))--}}
{{--                                      action="{{route('spare-parts-update', ['id' => $masterData->s_p_req_mst_id])}}"--}}
{{--                                      @else action="{{route('spare-parts-post')}}" @endif method="post"--}}

{{--                                    @if (isset($masterData->s_p_req_mst_id))--}}
{{--                                        @method('PUT')--}}
{{--                                        <input type="hidden" id="s_p_req_mst_id" name="s_p_req_mst_id"--}}
{{--                                               value="{{isset($masterData->s_p_req_mst_id) ? $masterData->s_p_req_mst_id: ''}}">--}}
{{--                                    @endif--}}
                                    @csrf
                                    <h5 class="card-title">Spare Parts Stock</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="required">Estimate No</label>
                                            <select type="text"
                                                   name="estimate_no" id="estimate_no"
                                                   class="form-control custom-select select2" required>
                                                <option value="">---Choose</option>
                                                @foreach($estimateNo as $value)
                                                <option value="{{$value->s_p_req_mst_id}}">{{$value->estimate_no}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="required">parts Name</label>
                                            <select class="custom-select select2 form-control"
                                                    name="parts_name" required
                                                    id="parts_name" >
                                                <option value="">---Select One---</option>

                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Stock Quantity</label>
                                            <input type="text" class="form-control" id="stockQty" placeholder="Stock Quantity" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Requested Quantity</label>
                                            <input type="text" class="form-control" id="reqQty" placeholder="Requested Quantity" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="required">Receive Quantity</label>
                                            <input type="number" class="form-control" id="recQty" placeholder="Receive Quantity"  name="recQty" autocomplete="off">
                                        </div>
                                        <div class="col-sm-12 " align="right">
                                            <div id="start-no-field">
                                                <label for="seat_to1">&nbsp;</label><br/>
                                                <button type="button" id="append"
                                                        class="btn btn-primary mb-1 add-row">
                                                    ADD
                                                </button>

                                            </div>
                                        </div>

                                    </div>

                                    <fieldset class="border p-1 mt-1 mb-1 col-sm-12">
                                        <div class="col-sm-12 mt-1">


                                                <div class="table-responsive res">
                                                    <table class="table table-sm table-striped table-bordered"
                                                           id="table-operator">
                                                        <thead>
                                                        <tr>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="1" class="text-center"width="5%">Action
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="40%">Estimate No
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                class="text-center" width="40%"> parts Name
                                                            </th>

                                                            <th role="columnheader" scope="col"
                                                                class="text-center"width="15%"> Receive Quantity
                                                            </th>

                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">

                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>

                                        <div class="col-12 mt-1 d-flex justify-content-start">

                                            <button type="button"
                                                    class="btn btn-primary mb-1 delete-row">
                                                Delete
                                            </button>
                                        </div>
                                    </fieldset>

                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                @if(!isset($masterData))
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Save
                                                    </button>
                                                @else
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Update
                                                    </button>
                                                @endif
                                                    <a type="reset" href="{{route("spare-parts-request")}}"
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


        </section>
    </div>

    @include('eqms.spareParsStock.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        var dataArray = new Array();

        $(".add-row").click(function () {

            let estimate_no = $("#estimate_no option:selected").val();
            let estimate_name = $("#estimate_no option:selected").text();
            let parts_id= $("#parts_name option:selected").val();
            let parts_name = $("#parts_name option:selected").text();
            let receiveQty = $("#recQty").val();
            let reqQty = $("#reqQty").val();

            if (estimate_no == '') {
                Swal.fire(
                    'Please Select Estimate No.',
                    '',
                    'error'
                )
                $('#Pro_method').focus();
                return;
            }
            if (parts_id == '') {
                Swal.fire(
                    'Please Select Parts Name',
                    '',
                    'error'
                )
                $('#Pro_method').focus();
                return;
            }

            if (receiveQty == '') {
                Swal.fire(
                    'Please input Receive Quantity.',
                    '',
                    'error'
                )
                $('#Pro_method').focus();
                return;
            }
            // if (reqQty != receiveQty) {
            //     Swal.fire(
            //         'Request Quantity and Receive Quantity Not Match.',
            //         '',
            //         'error'
            //     )
            //     $('#Pro_method').focus();
            //     return;
            // }
            if (parts_id) {
                /*if ($.inArray(parts_id, dataArray) > -1) {
                    Swal.fire(
                        'Duplicate Parts not allowed.',
                        '',
                        'error'
                    )
                } else {
                    let markup = "<tr role='row'>" +
                        "<td aria-colindex='1' role='cell' class='text-center'>" +
                        "<input type='checkbox' name='record' value='" + "" + "+" + "" + "'>" +
                        "<input type='hidden' name='tab_spare_id[]' value=''>" +
                        "<input type='hidden' name='tab_estimate_no[]' value='" + estimate_no + "'>" +
                        "<input type='hidden' name='tab_parts_id[]' value='" + parts_id + "'>" +
                        "<input type='hidden' name='tab_receiveQty[]' value='" + receiveQty + "'>" +
                        "</td><td aria-colindex='2' role='cell'>" + estimate_name + "</td><td aria-colindex='2' role='cell'>" + parts_name + "</td><td aria-colindex='2' role='cell'>" + receiveQty + "</td></tr>";
                    $("#parts_id").val('').trigger('change');
                    $("#receiveQty").val('');
                    $("#table-operator tbody").append(markup);
                    dataArray.push(parts_id);
                }*/
                let markup = "<tr role='row'>" +
                    "<td aria-colindex='1' role='cell' class='text-center'>" +
                    "<input type='checkbox' name='record' value='" + "" + "+" + "" + "'>" +
                    "<input type='hidden' name='tab_spare_id[]' value=''>" +
                    "<input type='hidden' name='tab_estimate_no[]' value='" + estimate_no + "'>" +
                    "<input type='hidden' name='tab_parts_id[]' value='" + parts_id + "'>" +
                    "<input type='hidden' name='tab_receiveQty[]' value='" + receiveQty + "'>" +
                    "</td><td aria-colindex='2' role='cell'>" + estimate_name + "</td><td aria-colindex='2' role='cell'>" + parts_name + "</td><td aria-colindex='2' role='cell'>" + receiveQty + "</td></tr>";
                $("#parts_id").val('').trigger('change');
                $("#receiveQty").val('');
                $("#table-operator tbody").append(markup);
                dataArray.push(parts_id);
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
            let s_p_req_dtl_id = [];
            $(':checkbox:checked').each(function (i) {
                arr_stuff[i] = $(this).val();
                let sd = arr_stuff[i].split('+');
                operator_id.push(sd[0]);
                if (sd[1]) {
                    s_p_req_dtl_id.push(sd[1]);
                }
            });

            if (s_p_req_dtl_id.length != 0) {
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
                            url: '/spare-parts-data-remove',
                            data: {s_p_req_dtl_id: s_p_req_dtl_id},
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
                            dataArradminay.splice(i, 1);
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



        function partsStocktList() {
            var url = '{{route('spare-parts-stock-datatable')}}';
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
                    {data: 'parts_no', name: 'parts_no', searchable: true},
                    {data: 'part_name', name: 'part_name', searchable: true},
                    {data: 'stock_qty', name: 'stock_qty', searchable: true},

                ]
            });
        };


        $('#estimate_no').on('change', function () {
            var estimate_no = $('#estimate_no').val();
            loadEstimateParts(estimate_no);
        });

        function loadEstimateParts(estimate_no) {
            if (estimate_no !== undefined && estimate_no) {
                $.ajax({
                    type: "GET",
                    url: APP_URL + '/ajax-estimate-parts-stock/' + estimate_no,
                    success: function (data) {

                        $('#parts_name').html(data);

                    },
                    error: function (data) {
                        alert('error');
                    }
                });
            } else {
                $('#parts_name').val('');
            }
        }
        $("#parts_name").on("change", function () {
            let partsID = $("#parts_name").val();
            let estimate_no = $("#estimate_no").val();


            let url = APP_URL + '/get-request-stock-data/' + estimate_no + '/' + partsID;

            if (((partsID !== undefined) || (partsID != null)) && partsID) {
                $.ajax({
                    type: "GET",

                    url: url,
                    success: function (data) {

                        $('#stockQty').val(data[0].stock_qty);
                        $('#reqQty').val(data[0].req_qty);
                    },
                    error: function (data) {
                        alert('error asche');
                    }
                });
            } else {
                $('#stockQty').val('');
                $('#reqQty').val('');

            }


        });

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
            datePicker('#datetimepicker3');
            partsStocktList();
        });
        function chkTable() {
            if ($('#comp_body tr').length == 0) {
                Swal.fire({
                    title: 'Stock Information Needed!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        }

    </script>

@endsection

