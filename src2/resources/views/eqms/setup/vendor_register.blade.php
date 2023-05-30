@extends('layouts.default')

@section('title')
    Vendor Register
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title"> {{ isset($data->id)?'Edit':'Add' }} Vendor Register </h4>
                        <form method="POST" action="">
                            {{ isset($data->id)?method_field('PUT'):'' }}
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="input-required">Vendor ID<span class="required"></span></label>
                                            <input type="text"
                                                   readonly
                                                   id="description"
                                                   name="description"
                                                   {{--                                                   value="{{ old('local_agent', $data->local_agent) }}"--}}
                                                   placeholder="Vendor ID"
                                                   class="form-control"
                                                   oninput="this.value=this.value.toUpperCase()" />
                                            {{--                                            @if($errors->has("local_agent"))--}}
                                            {{--                                                <span class="help-block">{{$errors->first("local_agent")}}</span>--}}
                                            {{--                                            @endif--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label class="input-required">Vendor Type<span class="required"></span></label>
                                            <select name="vendor_type_id" id="vendor_type_id" class="form-control select2">
                                                <option value="">Select one</option>
                                                {{--                                                @foreach($vesselNames as $vesselName)--}}
                                                {{--                                                    <option {{ ( old("vessel_id", $data->vessel_id) == $vesselName->id) ? "selected" : ""  }} value="{{$vesselName->id}}">{{$vesselName->name.'('.$vesselName->reg_no.') '}}</option>--}}
                                                {{--                                                @endforeach--}}
                                            </select>
                                            {{--                                            @if($errors->has("vessel_id"))--}}
                                            {{--                                                <span class="help-block">{{$errors->first("vessel_id")}}</span>--}}
                                            {{--                                            @endif--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="input-required">Vendor Name<span class="required"></span></label>
                                            <input type="text"
                                                   id="description"
                                                   name="description"
{{--                                                   value="{{ old('local_agent', $data->local_agent) }}"--}}
                                                   placeholder="Description"
                                                   class="form-control"
                                                   oninput="this.value=this.value.toUpperCase()" />
{{--                                            @if($errors->has("local_agent"))--}}
{{--                                                <span class="help-block">{{$errors->first("local_agent")}}</span>--}}
{{--                                            @endif--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="input-required">Address<span class="required"></span></label>
                                            <textarea type="text" name="owner_address"
                                                      placeholder="Owner Address" class="form-control"
                                                      oninput="this.value = this.value.toUpperCase()" style="margin-top: 0px; margin-bottom: 0px; height: 37px;">

                                            </textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label class="input-required">Contact No<span class="required"></span></label>
                                            <input type="text"
                                                   id="description"
                                                   name="description"
                                                   {{--     value="{{ old('local_agent', $data->local_agent) }}"--}}
                                                   placeholder="Contact no"
                                                   class="form-control"
                                                {{--                                                   oninput="this.value=this.value.toUpperCase()" --}}
                                            />
                                            {{--   @if($errors->has("local_agent"))--}}
                                            {{--       <span class="help-block">{{$errors->first("local_agent")}}</span>--}}
                                            {{--         @endif--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label class="input-required">Mobile<span class="required"></span></label>
                                            <input type="text"
                                                   id="mobile"
                                                   name="mobile"
                                                   {{--     value="{{ old('local_agent', $data->local_agent) }}"--}}
                                                   placeholder="Mobile No"
                                                   class="form-control"
                                                {{--                                                   oninput="this.value=this.value.toUpperCase()" --}}
                                            />
                                            {{--   @if($errors->has("local_agent"))--}}
                                            {{--       <span class="help-block">{{$errors->first("local_agent")}}</span>--}}
                                            {{--         @endif--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label class="input-required">Email<span class="required"></span></label>
                                            <input type="text"
                                                   id="email"
                                                   name="email"
                                                   {{--     value="{{ old('local_agent', $data->local_agent) }}"--}}
                                                   placeholder="Email"
                                                   class="form-control"
                                                {{--                                                   oninput="this.value=this.value.toUpperCase()" --}}
                                            />
                                            {{--   @if($errors->has("local_agent"))--}}
                                            {{--       <span class="help-block">{{$errors->first("local_agent")}}</span>--}}
                                            {{--         @endif--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label class="input-required">Enlistment Date<span class="required"></span></label>
                                            <div class="input-group date" onfocusout="$(this).datetimepicker('hide')" id="enlistment_date" data-target-input="nearest">
                                                <input type="text" name="berthing_at"
                                                       {{--                                                       value="{{ old('berthing_at', $data->berthing_at) }}"--}}
                                                       class="form-control berthing_at"
                                                       data-target="#enlistment_date"
                                                       data-toggle="datetimepicker"
                                                       placeholder="Enlistment Date"
                                                       oninput="this.value = this.value.toUpperCase()"
                                                />
                                                <div class="input-group-append" data-target="#enlistment_date" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--                                            @if($errors->has("berthing_at"))--}}
                                            {{--                                                <span class="help-block">{{$errors->first("berthing_at")}}</span>--}}
                                            {{--                                            @endif--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-2">
                                        <div class="col-md-2"><label class="input-required">Status<span class="required"></span></label></div>
                                        <div class="col-md-10">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   {{--                                                                   value="{{ old('status','A') }}" {{isset($data->status) && $data->status == 'A' ? 'checked' : ''}} --}}
                                                                   name="status" id="customRadio1"
                                                                   checked=""
                                                            >
                                                            <label class="custom-control-label" for="customRadio1">Active</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   {{--                                                                   value="{{ old('status','I') }}" {{isset($data->status) && $data->status == 'I' ? 'checked' : ''}} --}}
                                                                   name="status" id="customRadio2"
                                                            >
                                                            <label class="custom-control-label" for="customRadio2">Inactive</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                            </ul>
                                            {{--                                            @if ($errors->has('status'))--}}
                                            {{--                                                <span class="help-block">{{ $errors->first('status') }}</span>--}}
                                            {{--                                            @endif--}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4 class="card-title">Contact Person Info </h4>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="input-required">Person Name<span class="required"></span></label>
                                            <input type="text"
                                                   id="contact_person_name "
                                                   name="contact_person_name"
                                                   {{--     value="{{ old('local_agent', $data->local_agent) }}"--}}
                                                   placeholder="Contact Person Name"
                                                   class="form-control"
                                                   oninput="this.value=this.value.toUpperCase()" />
                                            {{--   @if($errors->has("local_agent"))--}}
                                            {{--       <span class="help-block">{{$errors->first("local_agent")}}</span>--}}
                                            {{--         @endif--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="input-required">Person Name Bangla<span class="required"></span></label>
                                            <input type="text"
                                                   id="contact_person_name_bn "
                                                   name="contact_person_name_bn"
                                                   {{--     value="{{ old('local_agent', $data->local_agent) }}"--}}
                                                   placeholder="Contact Person Name Bangla"
                                                   class="form-control"
                                                   oninput="this.value=this.value.toUpperCase()" />
                                            {{--   @if($errors->has("local_agent"))--}}
                                            {{--       <span class="help-block">{{$errors->first("local_agent")}}</span>--}}
                                            {{--         @endif--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label class="input-required">Mobile<span class="required"></span></label>
                                            <input type="text"
                                                   id="mobile"
                                                   name="mobile"
                                                   {{--     value="{{ old('local_agent', $data->local_agent) }}"--}}
                                                   placeholder="Mobile No"
                                                   class="form-control"
                                                {{--                                                   oninput="this.value=this.value.toUpperCase()" --}}
                                            />
                                            {{--   @if($errors->has("local_agent"))--}}
                                            {{--       <span class="help-block">{{$errors->first("local_agent")}}</span>--}}
                                            {{--         @endif--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <label class="input-required">Email<span class="required"></span></label>
                                            <input type="text"
                                                   id="email"
                                                   name="email"
                                                   {{--     value="{{ old('local_agent', $data->local_agent) }}"--}}
                                                   placeholder="Email"
                                                   class="form-control"
                                                {{--                                                   oninput="this.value=this.value.toUpperCase()" --}}
                                            />
                                            {{--   @if($errors->has("local_agent"))--}}
                                            {{--       <span class="help-block">{{$errors->first("local_agent")}}</span>--}}
                                            {{--         @endif--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="input-required">Remarks<span class="required"></span></label>
                                            <textarea type="text" name="remarks"
                                                      placeholder="Remarks" class="form-control"
                                                      oninput="this.value = this.value.toUpperCase()" style="margin-top: 0px; margin-bottom: 0px; height: 37px;">

                                            </textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">

                            </div>

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="row my-1">
                                        <div class="col-md-12" style="margin-top: 20px">
                                            <div class="d-flex justify-content-end col">
                                                <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">SAVE  </button>
                                                <a  class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Vendor Register List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>VENDOR NAME</th>
                                    <th>MOBILE</th>
                                    <th>VENDOR ADDRESS</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($vendorList as $vendorListAllData)
                                    <tr>
                                        <td>{{ $vendorListAllData->vendor_id }}</td>
                                        <td>{{ $vendorListAllData->vendor_name  }}</td>

                                        <td>{{ $vendorListAllData->mobile }}</td>
                                        <td>{{ $vendorListAllData->vendor_address }}</td>
                                        <td>
                                            <a href="#">
                                                <i class="bx bx-show-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                </div>
            </div>

        </div>
        </div>
    </div>
@endsection


@section('footer-script')
    <!--Load custom script-->
    <script>
        $(function () {
            $('#enlistment_date').datetimepicker(
                {
                    format: 'DD-MM-YYYY',
                }
            );

        });


    </script>
@endsection
