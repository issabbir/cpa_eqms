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

                                <form enctype="multipart/form-data"
                                      @if(isset($masterData->s_p_req_mst_id))
                                      action="{{route('spare-parts-update', ['id' => $masterData->s_p_req_mst_id])}}"
                                      @else action="{{route('spare-parts-post')}}" @endif method="post" onsubmit="return chkTable()">

                                    @if (isset($masterData->s_p_req_mst_id))
                                        @method('PUT')
                                        <input type="hidden" id="s_p_req_mst_id" name="s_p_req_mst_id"
                                               value="{{isset($masterData->s_p_req_mst_id) ? $masterData->s_p_req_mst_id: ''}}">
                                    @endif
                                    @csrf
                                    <h5 class="card-title">Spare Parts Request</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="required">Estimate No</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Estimate No" onautocomplete="off"
                                                   name="estimate_no" id="estimate_no"
                                                   class="form-control" required
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="100"
                                                   value="{{isset($masterData->estimate_no)?$masterData->estimate_no:''}}">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="required">Equipment Type</label>
                                            <select class="custom-select select2 form-control"
                                                    name="equip_type" required
                                                    id="equip_type">
                                                <option value="">---Select One---</option>
                                                @foreach($equipType as $value)
                                                    <option
                                                        value="{{$value->equip_type_id}}" {{isset($masterData->equip_type_id) && $masterData->equip_type_id == $value->equip_type_id ? 'selected' : ''}}> {{$value->equip_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="required">No of Equipment</label>
                                            <input type="text" class="form-control"
                                                   name="no_of_eqip" required
                                                   id="no_of_eqip" autocomplete="off"
                                                   onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                   placeholder="No. of Equipment" onkeyup="caltounit();"
                                                   value="{{isset($masterData->no_of_equip)?$masterData->no_of_equip:''}}"
                                                   maxlength="4" minlength="1">
                                        </div>
                                        <div class="col-md-3 ">
                                            <label class="required">WorkShop</label>
                                            <select class="custom-select select2 form-control"
                                                    name="workshop_name" required
                                                    id="workshop_name">
                                                <option value="">---Select One---</option>
                                                @foreach($workshop as $value)
                                                    <option
                                                        value="{{$value->workshop_id}}"{{isset($masterData->workshop_id) && $masterData->workshop_id == $value->workshop_id ? 'selected' : ''}}> {{$value->workshop_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>procurment YEAR</label>
                                            <input type="text" class="form-control"
                                                   name="procuremant_year"
                                                   id="procuremant_year" onautocomplete="off"
                                                   onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                   placeholder="Procurment Year"
                                                   value="{{isset($masterData->procurment_year)?$masterData->procurment_year:''}}"
                                                   maxlength="4" minlength="4">
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <label class="required">Request Date</label>
                                            <div class="input-group date" id="datetimepicker3"
                                                 data-target-input="nearest">
                                                <input type="text" required
                                                       value="{{isset($masterData->req_date)?date('d-m-Y', strtotime($masterData->req_date)):''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker3"
                                                       id="request_date"
                                                       name="request_date" placeholder="Request Date"
                                                       autocomplete="off"/>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Request Employee</label>
                                            <select class="custom-select select2 form-control"
                                                    id="employee_info" name="employee_info">
                                                @if(isset($masterData))
                                                    <option
                                                        value="{{$masterData->req_by_emp_id}}">{{$masterData->empInfo->emp_code.'-'.$masterData->empInfo->emp_name}}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>


                                    <fieldset class="border p-1 mt-2 mb-1 col-sm-12">
                                        <div class="row ml-1">
                                            <div class="col-sm-4">
                                                <label for="" class="required">Parts Name</label>
                                                <select class="custom-select select2 form-control" name="part_id"
                                                        id="part_id">
                                                    <option value="">---Choose</option>
                                                    @foreach($parts as $value)
                                                        <option
                                                            value="{{$value->part_id}}">{{$value->part_no.'-'.$value->part_name}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="col-sm-2">
                                                <label class="">Stock QTY</label>
                                                <input type="text" class="form-control" readonly
                                                       name="stock_qty" id="stock_qty" placeholder="Stock Quantity" onautocomplete="off">
                                            </div>

                                            <div class="col-sm-2">
                                                <label class="required">QTY Per Equipment</label>
                                                <input type="text" class="form-control" placeholder="Qty Per Equipment"
                                                       name="qty_per_equ" id="qty_per_equ" onkeyup="caltounit();"
                                                       onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="required">Required QTY</label>
                                                <input type="text" class="form-control"readonly
                                                       name="required_qty" id="required_qty" placeholder="Required Qty"
                                                       onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                       onkeyup="caltoprice();">
                                            </div>
                                            <div class="col-sm-2">
                                                <label for="" class="required">Unit</label>
                                                <select class="custom-select  form-control" name="unit_id" id="unit_id">
                                                    <option value="">---Choose</option>
                                                    @foreach($unit as $value)
                                                        <option value="{{$value->unit_id}}">{{$value->unit}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="col-sm-2 mt-1">
                                                <label class="">FOB (GPB) </label>
                                                <input type="text" class="form-control"
                                                       name="fob_gpb" id="fob_gpb" placeholder="FOB (GPB) "
                                                       onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                            </div>
                                            <div class="col-md-2 mt-1">
                                                <label>Foreign Price YEAR</label>
                                                <input type="text" class="form-control"
                                                       name="fob_year"
                                                       id="fob_year" placeholder="Foreign Price YEAR"
                                                       onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                       value="" maxlength="4" minlength="4">
                                            </div>
                                            <div class="col-md-2 mt-1">
                                                <label class="">Last Purchese YEAR</label>
                                                <input type="text" class="form-control"
                                                       name="last_purchase_year"
                                                       id="last_purchase_year" placeholder="Last Purchese YEAR"
                                                       onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                       value="" maxlength="4" minlength="4">
                                            </div>
                                            <div class="col-md-2 mt-1">
                                                <label class="">Last Purchese value</label>
                                                <input type="text" class="form-control"
                                                       name="last_purchase_value"
                                                       id="last_purchase_value" placeholder="Last Purchese value"
                                                       onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                       value="">
                                            </div>
                                            <div class="col-md-2 mt-1">
                                                <label class="required">Estimate Unit Price</label>
                                                <input type="text" class="form-control"
                                                       name="unit_price"
                                                       id="unit_price" onkeyup="caltoprice();" placeholder="Estimate Unit Price"
                                                       onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                       value="">
                                            </div>
                                            <div class="col-md-2 mt-1">
                                                <label class="required" >ToTal Rate <small>(Taka)</small></label>
                                                <input type="text" class="form-control"
                                                       name="total_rate"
                                                       id="total_rate" readonly placeholder="ToTal Rate"
                                                       onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                       value="">
                                            </div>
                                            <div class="col-sm-2 mt-1">
                                                <label for="" class="required">Last Procurment Method</label>
                                                <select class="custom-select  form-control" name="Pro_method"
                                                        id="Pro_method">
                                                    <option value="">---Choose</option>
                                                    @foreach($pro_method as $value)
                                                        <option
                                                            value="{{$value->p_methode_id}}">{{$value->procure_methode}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="col-md-7 mt-1">
                                                <label>Remarks</label>
                                                <input type="text" class="form-control"
                                                       name="p_remarks"
                                                       id="p_remarks"
                                                       value="" placeholder="Remarks">
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
                                    </fieldset>
                                    <fieldset class="border p-1 mt-1 mb-1 col-sm-12">
                                        <div class="col-sm-12 mt-1">
                                            <div style="overflow-x:scroll;">
                                                <style>
                                                    .res {
                                                        width: max-content;
                                                    }

                                                    #comp_body tr td,
                                                    #comp_body tr th {
                                                        max-width: 90px;
                                                    }

                                                    #comp_body tr th:nth-child(2),
                                                    #comp_body tr td:nth-child(2) {
                                                        min-width: 18%;
                                                        max-width: 20% !important;
                                                    }
                                                </style>
                                                <div class="table-responsive res">
                                                    <table class="table table-sm table-striped table-bordered"
                                                           id="table-operator">
                                                        <thead>
                                                        <tr>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="1" class="text-center">Action
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center">Parts Name
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                class="text-center"> Stock QTY
                                                            </th>


                                                            <th role="columnheader" scope="col"
                                                                class="text-center"> Req.
                                                                QTY
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                class="text-center"> Unit
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                class="text-center"> FOB (GPB)
                                                            </th>


                                                            <th role="columnheader" scope="col"
                                                                class="text-center"> Foreign
                                                                Price YEAR
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                class="text-center"> Last
                                                                Purchese YEAR
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                class="text-center"> Last
                                                                Purchese value
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                class="text-center"> Unit
                                                                Price
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                class="text-center"> ToTal
                                                                Rate
                                                                (Taka)
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                class="text-center">
                                                                Procurement Method
                                                            </th>

                                                            <th role="columnheader" scope="col"
                                                                class="text-center">Remarks
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">
                                                        @if(!empty($detailsData))

                                                            @foreach($detailsData as $key=>$values)

                                                                <tr role="row">
                                                                    <td aria-colindex="2" role="cell"
                                                                        class="text-center">
                                                                        <input type='checkbox' name='record'
                                                                               value="{{$values->s_p_req_mst_id.'+'.$values->s_p_req_dtl_id}}">
                                                                        <input type="hidden" name="tab_spare_id[]"
                                                                               value="{{$values->s_p_req_dtl_id}}"
                                                                               class="erm_id">
                                                                    </td>
                                                                    <td aria-colindex="2" role="cell">
                                                                        <input type="text" class="form-control" readonly
                                                                               value="{{$values->part_no.'-'.$values->part_name}}">
                                                                        <input type="hidden" class="form-control"
                                                                               name="tab_part_id[]"
                                                                               value="{{$values->part_id}}"></td>

                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               name="tab_stock_qty[]" readonly
                                                                               value="{{$values->stock_qty}}">
                                                                    </td>
                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               readonly
                                                                               value="{{$values->unit_measure}}">
                                                                        <input type="hidden" class="form-control"
                                                                               name="tab_unit_id[]"
                                                                               value="{{$values->unit_id}}">
                                                                    </td>
                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               readonly name='tab_required_qty[]'
                                                                               value="{{$values->req_qty}}">

                                                                    </td>
                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               readonly name='tab_fob_gpb[]'
                                                                               value="{{$values->foreign_price_fob}}">

                                                                    </td>
                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               readonly name='tab_fob_year[]'
                                                                               value="{{$values->foreign_price_year}}">

                                                                    </td>
                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               readonly name='tab_last_purchase_year[]'
                                                                               value="{{$values->last_purchase_year}}">

                                                                    </td>
                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               readonly name='tab_last_purchase_value[]'
                                                                               value="{{$values->last_purchase_price}}">

                                                                    </td>
                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               readonly name='tab_unit_price[]'
                                                                               value="{{$values->est_unit_price}}">

                                                                    </td>
                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               readonly name='tab_total_rate[]'
                                                                               value="{{$values->total_rate}}">

                                                                    </td>
                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               readonly
                                                                               value="{{$values->last_procure_methode}}">
                                                                        <input type="hidden" name="tab_Pro_method_id[]"
                                                                               value="{{$values->last_procure_methode_id}}">

                                                                    </td>
                                                                    <td role="cell">
                                                                        <input type="text" class="form-control"
                                                                               readonly name='tab_p_remarks[]'
                                                                               value="{{$values->remarks}}">

                                                                    </td>


                                                                </tr>

                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                </div>
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
                                                    <a type="reset" href="{{route("spare-parts-request")}}"
                                                       class="btn btn-light-secondary mb-1"> Reset</a>
                                                @else
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Update
                                                    </button>
                                                    <a type="reset" href="{{route("spare-parts-request")}}"
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


        </section>
    </div>

    @include('eqms.sparePars.list')

@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        var dataArray = new Array();

        $(".add-row").click(function () {
            let part_id = $("#part_id option:selected").val();
            let part_name = $("#part_id option:selected").text();
            let stock_qty = $("#stock_qty").val();
            let unit_id = $("#unit_id option:selected").val();
            let unit = $("#unit_id option:selected").text();
            let qty_per_equ = $("#qty_per_equ").val();
            let required_qty = $("#required_qty").val();
            let fob_gpb = $("#fob_gpb").val();
            let fob_year = $("#fob_year").val();
            let last_purchase_year = $("#last_purchase_year").val();
            let last_purchase_value = $("#last_purchase_value").val();
            let unit_price = $("#unit_price").val();
            let total_rate = $("#total_rate").val();
            let Pro_method_id = $("#Pro_method option:selected").val();
            let Pro_method = $("#Pro_method option:selected").text();
            let p_remarks = $("#p_remarks").val();

            if (qty_per_equ == '') {
                qty_per_equ = '';
            }
            if (fob_gpb == '') {
                fob_gpb = '';
            }

            if (fob_year == '') {
                fob_year = '';
            }
            if (part_id == '') {
                Swal.fire(
                    'Please Select Parts Name.',
                    '',
                    'error'
                )
                $('#part_id').focus();
                return;
            }
            if (unit_id == '') {
                Swal.fire(
                    'Please Input Unit.',
                    '',
                    'error'
                )
                $('#unit_id').focus();
                return;
            }
            if (unit_price == '') {
                Swal.fire(
                    'Please Input  Estimate Unit Price .',
                    '',
                    'error'
                )
                $('#unit_id').focus();
                return;
            }

            if (Pro_method_id == '') {
                Swal.fire(
                    'Please Select Procurement Method.',
                    '',
                    'error'
                )
                $('#Pro_method').focus();
                return;
            }


            if (part_id) {
                if ($.inArray(part_id, dataArray) > -1) {
                    Swal.fire(
                        'Duplicate value not allowed.',
                        '',
                        'error'
                    )
                } else {
                    let markup = "<tr role='row'>" +
                        "<td aria-colindex='1' role='cell' class='text-center'>" +
                        "<input type='checkbox' name='record' value='" + "" + "+" + "" + "'>" +
                        "<input type='hidden' name='tab_spare_id[]' value=''>" +
                        "<input type='hidden' name='tab_part_id[]' value='" + part_id + "'>" +
                        "<input type='hidden' name='tab_stock_qty[]' value='" + stock_qty + "'>" +
                        "<input type='hidden' name='tab_unit_id[]' value='" + unit_id + "'>" +
                        "<input type='hidden' name='tab_qty_per_equ[]' value='" + qty_per_equ + "'>" +
                        "<input type='hidden' name='tab_required_qty[]' value='" + required_qty + "'>" +
                        "<input type='hidden' name='tab_fob_gpb[]' value='" + fob_gpb + "'>" +
                        "<input type='hidden' name='tab_fob_year[]' value='" + fob_year + "'>" +
                        "<input type='hidden' name='tab_last_purchase_year[]' value='" + last_purchase_year + "'>" +
                        "<input type='hidden' name='tab_last_purchase_value[]' value='" + last_purchase_value + "'>" +
                        "<input type='hidden' name='tab_unit_price[]' value='" + unit_price + "'>" +
                        "<input type='hidden' name='tab_total_rate[]' value='" + total_rate + "'>" +
                        "<input type='hidden' name='tab_Pro_method_id[]' value='" + Pro_method_id + "'>" +
                        "<input type='hidden' name='tab_p_remarks[]' value='" + p_remarks + "'>" +
                        "</td><td aria-colindex='2' role='cell'>" + part_name + "</td><td aria-colindex='2' role='cell'>" + stock_qty + "</td><td aria-colindex='2' role='cell'>" + required_qty + "</td><td aria-colindex='2' role='cell'>" + unit + "</td><td aria-colindex='2' role='cell'>" + fob_gpb + "</td><td aria-colindex='2' role='cell'>" + fob_year + "</td><td aria-colindex='2' role='cell'>" + last_purchase_year + "</td><td aria-colindex='2' role='cell'>" + last_purchase_value + "</td><td aria-colindex='2' role='cell'>" + unit_price + "</td><td aria-colindex='2' role='cell'>" + total_rate + "</td><td aria-colindex='2' role='cell'>" + Pro_method + "</td><td aria-colindex='2' role='cell'>" + p_remarks + "</td></tr>";
                        $("#part_id").val('').trigger('change');
                        $("#stock_qty").val('').trigger('change');
                        $("#unit_id").val('').trigger('change');
                        $("#qty_per_equ").val('').trigger('change');
                        $("#required_qty").val('').trigger('change');
                        $("#fob_gpb").val('').trigger('change');
                        $("#fob_year").val('').trigger('change');
                        $("#last_purchase_year").val('').trigger('change');
                        $("#last_purchase_value").val('').trigger('change');
                        $("#unit_price").val('').trigger('change');
                        $("#total_rate").val('').trigger('change');
                        $("#Pro_method_id").val('').trigger('change');
                        $("#p_remarks").val('').trigger('change');
                        $("#table-operator tbody").append(markup);
                        dataArray.push(part_id);
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

        $('#employee_info').select2({
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

        function partsRequestList() {
            var url = '{{route('spare-parts-request-datatable')}}';
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
                    {data: 'estimate_no', name: 'estimate_no', searchable: true},
                    {data: 'equip_type', name: 'equip_type', searchable: true},
                    {data: 'no_of_equip', name: 'no_of_equip', searchable: true},
                    {data: 'workshop_name', name: 'workshop_name', searchable: true},
                    {data: 'procurment_year', name: 'procurment_year', searchable: true},
                    {data: 'req_date', name: 'req_date', searchable: true},
                    {data: 'emp_name', name: 'emp_name', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        function caltoprice() {
            var qnty = $('#required_qty').val();
            var uprice = $('#unit_price').val();
            var total = (qnty * uprice);
            $('#total_rate').val(total);
        }
        function caltounit() {
            var equip = $('#no_of_eqip').val();
            var qtueqp = $('#qty_per_equ').val();
            var total = (equip * qtueqp);
            $('#required_qty').val(total);
        }

        $('#part_id').on('change', function () {
            var part_id = $('#part_id').val();
            loadPartStock(part_id);
        });

        function loadPartStock(part_id) {
            if (part_id !== undefined && part_id) {
                $.ajax({
                    type: "GET",
                    url: APP_URL + '/ajax-parts-stock/' + part_id,
                    success: function (data) {

                        $('#stock_qty').val(data[0].stock_qty);

                    },
                    error: function (data) {
                        alert('error');
                    }
                });
            } else {
                $('#stock_qty').val('');
            }
        }

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
            partsRequestList();
        });

        function chkTable() {
            if ($('#comp_body tr').length == 0) {
                Swal.fire({
                    title: 'Parts Information Needed!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        }

    </script>

@endsection

