@extends('layouts.default')

@section('title')
    :: Add Equipment
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
                                      @if(isset($data->equip_id)) action="{{route('add-equipment-update',[$data->equip_id])}}"
                                      @else action="{{route('add-equipment-post')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->equip_id))
                                        @method('PUT')
                                        <input type="hidden" id="equip_id" name="equip_id"
                                               value="{{isset($data->equip_id) ? $data->equip_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Add Equipment</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="required">Equipment No</label>
                                            <input type="text"
                                                   placeholder="Equipment No"
                                                   name="equip_no" autocomplete="off"
                                                   class="form-control" required
                                                   value="{{isset($data->equip_no) ? $data->equip_no : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="100"
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="required">Equipment Name</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Equipment Name"
                                                   name="equip_name"
                                                   class="form-control"
                                                   value="{{isset($data->equip_name) ? $data->equip_name : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label class="required">EQUIPMENT TYPE</label>
                                            <select class="custom-select select2 form-control" required
                                                    id="equip_type_id" name="equip_type_id">
                                                <option value="">Select One</option>
                                                @foreach($equipmentList as $value)
                                                    <option value="{{$value->equip_type_id}}"
                                                        {{isset($data->equip_type_id) && $data->equip_type_id == $value->equip_type_id ? 'selected' : ''}}
                                                    >{{$value->equip_type}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3" id="hide6">
                                            <label class="required">LOAD CAPACITY</label>
                                            <select class="custom-select select2 form-control" id="load_capacity_id"
                                                    required
                                                    name="load_capacity_id">
                                                <option value="">Select One</option>
                                                @foreach($lCapacityList as $value)
                                                    <option value="{{$value->load_capacity_id}}"
                                                        {{isset($data->load_capacity_id) && $data->load_capacity_id == $value->load_capacity_id ? 'selected' : ''}}
                                                    >{{$value->load_capacity}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>EQUIPMENT MODEL</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Equip Model"
                                                   name="equip_model"
                                                   class="form-control"
                                                   value="{{isset($data->equip_model) ? $data->equip_model : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="100"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>MANUFACTURER NAME</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Manufacturer Name"
                                                   name="manufacturer_name"
                                                   class="form-control"
                                                   value="{{isset($data->manufacturer_name) ? $data->manufacturer_name : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>MANUFACTURER ADDRESS</label>
                                            <input type="text" autocomplete="ok"
                                                   placeholder="Manufacturer Address"
                                                   name="manufacturer_address"
                                                   class="form-control"
                                                   value="{{isset($data->manufacturer_address) ? $data->manufacturer_address : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>ENGINE MODEL</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Engine Model"
                                                   name="engine_model"
                                                   class="form-control"
                                                   value="{{isset($data->engine_model) ? $data->engine_model : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="100"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">ENGINE SL NO</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Engine Sl No"
                                                   name="engine_sl_no" required
                                                   class="form-control"
                                                   value="{{isset($data->engine_sl_no) ? $data->engine_sl_no : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="100"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>CHASSIS NO</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Chassis No"
                                                   name="chassis_no"
                                                   class="form-control"
                                                   value="{{isset($data->chassis_no) ? $data->chassis_no : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="100"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>MANUFACTURE YEAR</label>
                                            <input type="number" autocomplete="off"
                                                   placeholder="Manufacture Year"
                                                   name="manufacture_year"
                                                   class="form-control"
                                                   value="{{isset($data->manufacture_year) ? $data->manufacture_year : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="4"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Origin Country</label>
                                            <select class="custom-select select2 form-control" required
                                                    id="origin_country_id" name="origin_country_id">
                                                <option value="">Select One</option>
                                                @foreach($countryList as $value)
                                                    <option value="{{$value->country_id}}"
                                                        {{isset($data->origin_country_id) && $data->origin_country_id == $value->country_id ? 'selected' : ''}}
                                                    >{{$value->country}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>BHP RPM</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="BHP RPM"
                                                   name="bhp_rpm"
                                                   class="form-control"
                                                   value="{{isset($data->bhp_rpm) ? $data->bhp_rpm : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="250"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>TRANSMISSION MODEL</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Transmission Model"
                                                   name="trans_model"
                                                   class="form-control"
                                                   value="{{isset($data->trans_model) ? $data->trans_model : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Spreder Model</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Spreder Model"
                                                   name="speder_model"
                                                   class="form-control"
                                                   value="{{isset($data->speder_model) ? $data->speder_model : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="200"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>FUEL TANK CAPACITY</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Fuel Tank Capacity"
                                                   name="fuel_tank_capacity"
                                                   class="form-control"
                                                   value="{{isset($data->fuel_tank_capacity) ? $data->fuel_tank_capacity : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="50"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1 mb-1">
                                            <label for="req_date " class="required">Operation Date</label>
                                            <div class="input-group date datePiker">
                                                <input type="text" @if(isset($data->operation_date)) disabled @endif
                                                autocomplete="off"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker"
                                                       id="operation_date"
                                                       data-target="#operation_date"
                                                       name="operation_date"
                                                       data-predefined-date="{{old('operation_date',isset($data->operation_date) ? date('d-m-Y', strtotime($data->operation_date)) : '')}}"
                                                >
                                                @if(isset($data->operation_date))
                                                    <input type="hidden" name="operation_date" value="{{old('operation_date',isset($data->operation_date) ? date('d-m-Y', strtotime($data->operation_date)) : '')}}">
                                                @endif
                                                <div class="input-group-append" data-target="#operation_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i
                                                            class="bx bxs-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-1 mb-1">
                                            <label for="req_date " class="required">Economical Life (Year)</label>
                                            <div class="input-group date datePiker">
                                                <input type="number" @if(isset($data->economical_life)) readonly @endif
                                                class="form-control"
                                                       id="economical_life"
                                                       name="economical_life"
                                                       value="{{isset($data->economical_life) ? $data->economical_life : ''}}"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-1 mb-1">
                                            <label>Date Expire</label>
                                            <div class="input-group date datePiker">
                                                <input type="text" disabled
                                                       class="form-control"
                                                       id="date_expire"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-1 mb-1">
                                            <label>Remaining Economical Life</label>
                                            <div class="input-group date datePiker">
                                                <input type="text" disabled
                                                       class="form-control"
                                                       id="rem_ec_lyf"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <label for="contract_date" class="required">Contract Date</label>
                                            <div class="input-group date datePiker">
                                                <input type="text" @if(isset($data->contract_date)) disabled @endif
                                                autocomplete="off"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker"
                                                       id="contract_date"
                                                       data-target="#contract_date"
                                                       name="contract_date"
                                                       data-predefined-date="{{old('contract_date',isset($data->contract_date) ? date('d-m-Y', strtotime($data->contract_date)) : '')}}"
                                                >
                                                @if(isset($data->contract_date))
                                                    <input type="hidden" name="contract_date" value="{{old('contract_date',isset($data->contract_date) ? date('d-m-Y', strtotime($data->contract_date)) : '')}}">
                                                @endif
                                                <div class="input-group-append" data-target="#contract_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Contract Currency</label>
                                            <select class="custom-select select2 form-control" id="currency_id"
                                                    name="currency_id">
                                                <option value="">Select One</option>
                                                @foreach($currencyList as $value)
                                                    <option value="{{$value->currency_id}}"
                                                        {{isset($data->contract_currency_id) && $data->contract_currency_id == $value->currency_id ? 'selected' : ''}}
                                                    >{{$value->currency}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>CONTRACT No</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Contract No"
                                                   name="contract_no"
                                                   class="form-control"
                                                   value="{{isset($data->contract_no) ? $data->contract_no : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="250"
                                            >
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <label>CONTRACT VALUE</label>
                                            <input type="number" autocomplete="off"
                                                   placeholder="Contract Value"
                                                   name="contract_value"
                                                   class="form-control"
                                                   value="{{isset($data->contract_value) ? $data->contract_value : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>SUPPLIER NAME</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Supplier Name"
                                                   name="supplier_name"
                                                   class="form-control"
                                                   value="{{isset($data->supplier_name) ? $data->supplier_name : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label>SUPPLIER ADDRESS</label>
                                            <input type="text" autocomplete="ok"
                                                   placeholder="Supplier Address"
                                                   name="supplier_address"
                                                   class="form-control"
                                                   value="{{isset($data->supplier_address) ? $data->supplier_address : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                    </div>
                                    <fieldset class="border p-1 mt-1 col-sm-12">
                                        <legend class="w-auto" >Additional Information</legend>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Equipment Short Name</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Equipment Short Name"
                                                       name="equip_short_name"
                                                       class="form-control"
                                                       value="{{isset($data->equip_short_name) ? $data->equip_short_name : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="100"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Machine Name</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Machine Name"
                                                       name="machine_name"
                                                       class="form-control"
                                                       value="{{isset($data->machine_name) ? $data->machine_name : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="500"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>EQUIPMENT SL NO</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Equipment Sl No"
                                                       name="equip_sl_no"
                                                       class="form-control"
                                                       value="{{isset($data->equip_sl_no) ? $data->equip_sl_no : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="100"
                                                >
                                            </div>

                                            <div class="col-md-3 mt-1">
                                                <label>ENGINE TYPE</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Engine Type"
                                                       name="engine_type"
                                                       class="form-control"
                                                       value="{{isset($data->engine_type) ? $data->engine_type : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="100"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>No of Stroke & Stroke Length</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="No of Stroke & Stroke Length"
                                                       name="stroke_no_length"
                                                       class="form-control"
                                                       value="{{isset($data->stroke_no_length) ? $data->stroke_no_length : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="250"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>BORE MM</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="BORE MM"
                                                       name="bore_mm"
                                                       class="form-control"
                                                       value="{{isset($data->bore_mm) ? $data->bore_mm : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="250"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Compression RATIO</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Compression Ratio"
                                                       name="compressore_ratio"
                                                       class="form-control"
                                                       value="{{isset($data->compressore_ratio) ? $data->compressore_ratio : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="200"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>MAX LIFT HEIGHT</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Max Lift Height"
                                                       name="max_lift_height"
                                                       class="form-control"
                                                       value="{{isset($data->max_lift_height) ? $data->max_lift_height : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="50"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>LIFT SPEED LADEN</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Lift Speed Laden"
                                                       name="lift_speed_laden"
                                                       class="form-control"
                                                       value="{{isset($data->lift_speed_laden) ? $data->lift_speed_laden : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="50"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>LIFT SPEED UNLADEN</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Lift Speed Unladen"
                                                       name="lift_speed_unladen"
                                                       class="form-control"
                                                       value="{{isset($data->lift_speed_unladen) ? $data->lift_speed_unladen : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="50"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>LOWER SPEED LADEN</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Lower Speed Laden"
                                                       name="lower_speed_laden"
                                                       class="form-control"
                                                       value="{{isset($data->lower_speed_laden) ? $data->lower_speed_laden : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="50"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>LOWER SPEED UNLADEN</label>
                                                <input type="text" autocomplete="off"
                                                       placeholder="Lower Speed Unladen"
                                                       name="lower_speed_unladen"
                                                       class="form-control"
                                                       value="{{isset($data->lower_speed_unladen) ? $data->lower_speed_unladen : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="50"
                                                >
                                            </div>
                                        </div>

                                    </fieldset>

                                    <fieldset class="border mt-2 col-md-12">
                                        <legend class="w-auto" style="font-size: 18px;">Documents Upload</legend>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div id="start-no-field" class="form-group">
                                                    <label for="seat_from">Document Name</label>
                                                    <input type="text" id="case_doc_name"
                                                           class="form-control "
                                                           placeholder="Document Name" value="" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="order_attachment" class="">Attachment</label>
                                                    <input type="file" class="form-control" id="attachedFile"
                                                           onchange="encodeFileAsURL();"/>
                                                </div>
                                                <input type="hidden" id="converted_file">
                                            </div>

                                            <div class="col-md-1">
                                                <div id="start-no-field"
                                                     class="form-group">
                                                    <label for="seat_to1">&nbsp;</label><br/>
                                                    <button type="button" id="append"
                                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary add-row-doc">
                                                        ADD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">

                                                    <table class="table table-striped table-bordered" id="table-doc">
                                                        <thead>
                                                        <tr>
                                                            <th style="height: 25px;text-align: left; width: 5%">#</th>
                                                            <th style="height: 25px;text-align: left; width: 50%">
                                                                Document Name
                                                            </th>
                                                            <th style="height: 25px;text-align: left; width: 40%">
                                                                Attachment
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody id="file_body">
                                                        @if(isset($docData))
                                                            @foreach($docData as $key=>$value)
                                                                <tr>
                                                                    <td>
                                                                        <input type='checkbox' name='record'>
                                                                        <input type='hidden' name='doc_id[]'
                                                                               value='{{($value)?$value->doc_id:'0'}}'
                                                                               class="doc_id">
                                                                        <input type='hidden' name='doc_name[]'
                                                                               value='{{($value)?$value->doc_name:''}}'
                                                                               class="doc_name">
                                                                        <input type='hidden' name='doc[]'
                                                                               value='{{($value)?$value->files:''}}'
                                                                               class="doc">
                                                                        <input type='hidden' name='doc_type[]'
                                                                               value='{{($value)?$value->doc_type:''}}'
                                                                               class="doc_type">
                                                                    </td>
                                                                    <td><input type="text" class="form-control"
                                                                               name="doc_name[]" readonly
                                                                               value="{{$value->doc_name}}"></td>
                                                                    <td>@if(isset($value->files))
                                                                            <a href="{{ route('file-download', [$value->doc_id]) }}"
                                                                               target="_blank"><i class='bx bxs-download cursor-pointer'></i></a>
                                                                        @endif</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                    <button type="button"
                                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary delete-row-file">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                @if(!isset($data))
                                                    <button id="save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1"> Save
                                                    </button>
                                                @else
                                                    <button id="update" type="submit"
                                                            class="btn btn-primary mr-1 mb-1"> Update
                                                    </button>
                                                @endif
                                                <a type="reset" href="{{route("add-equipment-index")}}"
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

    @include('eqms.addequipment.list')

@endsection

@section('footer-script')

    <script type="text/javascript">
        let dataArray = new Array();

        $("#save").click(function () {
            $("html").animate({scrollTop: 0}, "slow");
        });

        $("#update").click(function () {
            $("html").animate({scrollTop: 0}, "slow");
        });

        $("#equip_type_id").on('change', function (e) {
            let equip_type_id = $(this).val();
            if (equip_type_id == '3' || equip_type_id == '4') {
                $('#hide6').css("display", "none");
            } else {
                $('#hide6').css("display", "block");
            }
        });

        function equipmentList() {
            var url = '{{route('add-equipment-datatable')}}';
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
                    {data: 'equip_no', name: 'equip_no', searchable: true},
                    {data: 'equip_name', name: 'equip_name', searchable: true},
                    {data: 'machine_name', name: 'machine_name', searchable: true},
                    {data: 'manufacturer_name', name: 'manufacturer_name', searchable: true},
                    {data: 'supplier_name', name: 'supplier_name', searchable: true},
                    {data: 'equip_model', name: 'equip_model'},
                    {data: 'capacity.load_capacity', name: 'load_capacity'},
                    {data: 'manufacture_year', name: 'manufacture_year', searchable: true},
                    {data: 'operation_date', name: 'operation_date', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        function setExpire() {
            let addlyf = $('#economical_life').val();
            let input = $('#operation_date').val();
            let fields = input.split('-');

            let day = fields[0];
            let month = fields[1];
            let year;
            if (addlyf) {
                year = parseInt(fields[2]) + parseInt(addlyf);
                $('#date_expire').val(day + '-' + month + '-' + year);
                let date1 = new Date(month + '/' + day + '/' + year);
                let date2 = new Date();
                let diffTime = Math.abs(date2 - date1);
                let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                $('#rem_ec_lyf').val(diffDays + " Days");
            } else {
                year = fields[2];
                $('#date_expire').val('');
                $('#rem_ec_lyf').val('');
            }


            //console.log(day+'-'+month+'-'+year);
        }

        $('#economical_life').on('keyup paste', setExpire);

        $(document).ready(function () {
            @if(isset($data->economical_life)) setExpire(); @endif
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);

            var equip_id = '{{isset($data->equip_id) ? $data->equip_id : ''}}';

            if (equip_id) {
                $('#contract_date').prop('disabled', true);
            } else {
                $('#contract_date').prop('disabled', true);
            }

            //datePicker('#datetimepicker3');
            //minSysDatePicker('#datetimepicker3');
            //datePicker('#datetimepicker6');
            equipmentList();

            /*$('#operation_date').on('input', function() {
                let operation_date = $('#operation_date').val();
                if(operation_date){
                    $('#contract_date').prop('disabled', false);
                    customDateChk('#datetimepicker6',operation_date);
                }else{
                    $('#contract_date').prop('disabled', true);
                }
            });*/
        });

        let operation_date = '';
        $('#operation_date').on("change.datetimepicker", function (e) {
            operation_date = $(this).val();
            if (operation_date) {
                $('#contract_date').prop('disabled', false);
            } else {
                $('#contract_date').prop('disabled', true);
            }
            dateRangePicker('#operation_date', '#contract_date', operation_date);
        });
        dateRangePicker('#operation_date', '#contract_date');

        function dateRangePicker(Elem1, Elem2, minDate = null, maxDate = null) {
            let minElem = $(Elem2);
            let maxElem = $(Elem1);

            // console.log(maxDate)
            minElem.datetimepicker({
                format: 'DD-MM-YYYY',
                ignoreReadonly: true,
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
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
            maxElem.datetimepicker({
                useCurrent: false,
                format: 'DD-MM-YYYY',
                ignoreReadonly: true,
                widgetPositioning: {
                    horizontal: 'left',
                    vertical: 'bottom'
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
            // minElem.on("change.datetimepicker", function (e) {
            //     maxElem.datetimepicker('minDate', e.date);
            // });

            if (minDate) {
                minElem.datetimepicker('maxDate', minDate);
                // $(Elem1).datetimepicker('minDate',  moment("DD-MM-YYYY"));
            } else {
                maxElem.on("change.datetimepicker", function (e) {
                    minElem.datetimepicker('maxDate', e.date);
                });
                // minElem.datetimepicker('minDate', new Date());
            }
            // $(Elem2).datetimepicker('minDate', new Date());
            // minElem.datetimepicker('maxDate', e.date);

            let preDefinedDateMin = minElem.attr('data-predefined-date');
            let preDefinedDateMax = maxElem.attr('data-predefined-date');
            console.log(preDefinedDateMin);

            if (preDefinedDateMin) {
                let preDefinedDateMomentFormat = moment(preDefinedDateMin, "DD-MM-YYYY").format("DD-MM-YYYY");
                minElem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
            }

            if (preDefinedDateMax) {
                let preDefinedDateMomentFormat = moment(preDefinedDateMax, "DD-MM-YYYY").format("DD-MM-YYYY");
                maxElem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
            }

        }

        function encodeFileAsURL() {
            var file = document.querySelector('input[type=file]')['files'][0];
            var reader = new FileReader();
            var baseString;
            reader.onloadend = function () {
                baseString = reader.result;
                $("#converted_file").val(baseString);
                //console.log(baseString);
            };
            reader.readAsDataURL(file);
        }

        $(".add-row-doc").click(function () {

            let doc_name = $("#case_doc_name").val();
            let converted_file = $("#converted_file").val();

            let filePath = $("#attachedFile").val();
            let file_ext = filePath.substr(filePath.lastIndexOf('.') + 1, filePath.length);
            let fileName = document.getElementById('attachedFile').files[0].name;

            let markup = "<tr><td><input type='checkbox' name='record'>" +
                "<input type='hidden' name='doc_id[]' value=''>" +
                "<input type='hidden' name='doc_name[]' value='" + doc_name + "'>" +
                "<input type='hidden' name='doc_type[]' value='" + file_ext + "'>" +
                "<input type='hidden' name='doc[]' value='" + converted_file + "'>" +
                "</td><td>" + doc_name + "</td><td><i class='bx bxs-file cursor-pointer'></i></td></tr>";
            $("#case_doc_name").val("");
            $("#attachedFile").val("");
            $("#table-doc tbody").append(markup);
        });

        $(".delete-row-file").click(function () {
            $("#table-doc tbody").find('input[name="record"]').each(function () {
                if ($(this).is(":checked")) {
                    let doc_id = $(this).closest('tr').find('.doc_id').val();
                    if (doc_id !== null) {
                        $(this).parents("tr").remove();
                        let url = '{{route('docRemove')}}';
                        $.ajax({
                            type: 'GET',
                            url: url,
                            data: {doc_id: doc_id},
                            success: function (msg) {
                                $(this).parents("tr").remove();
                                Swal.fire({
                                    title: 'Successfully Deleted!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function () {
                                    //location.reload();

                                });
                            }
                        });
                    } else {
                        $(this).parents("tr").remove();
                    }
                    $("#attach_count").val('0');
                }
            });
        });

    </script>

@endsection

