<div class="card">
    <div class="card-content">
        <div class="card-body" id="vendor_info" style="@if(\Request::get('id')) display: block @else display: none @endif">
            @if ($readonly)
            <h4 class="card-title"> Vendor Info </h4>
            @else
                <h4 class="card-title"> {{ $data && isset($data->vendor_no)?'Edit':'Add' }} Vendor Info </h4>
            @endif
            <form method="POST" action="@if ($data && $data->vendor_no) {{route('vendors.update',['id' => $data->vendor_no])}} @else {{route('vendors.create')}} @endif">
                {{ ($data && isset($data->vendor_no))?method_field('PUT'):'' }}
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="input-required">Vendor ID<span class="required"></span></label>
                                <input type="text"
                                     readonly
                                       id="vendor_id"
                                       name="vendor_id"
                                       value="{{ old('vendor_id', ($data)?$data->vendor_id:$gen_vn_id) }}"
                                       placeholder="Vendor ID"
                                       class="form-control text-uppercase"
                                />
                                @if($errors->has("vendor_id"))
                                    <span class="help-block">{{$errors->first("vendor_id")}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="input-required">Vendor Type<span class="required"></span></label>
                                <select name="vendor_type_no" @if($readonly) disabled @endif id="vendor_type_no" required class="form-control select2">
                                    <option value="">Select one</option>
                                    @foreach($vendorTypes as $op)
                                        <option {{ ( old("vendor_type_no", ($data)?$data->vendor_type_no:'') == $op->vendor_type_no) ? "selected" : ""  }} value="{{$op->vendor_type_no}}">{{$op->vendor_type_name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has("vendor_type_no"))
                                    <span class="help-block">{{$errors->first("vendor_type_no")}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="input-required">Vendor Name<span class="required"></span></label>
                                <input type="text" required @if($readonly) readonly @endif
                                       id="vendor_name"
                                       name="vendor_name"
                                       value="{{ old('vendor_name', ($data)?$data->vendor_name:'') }}"
                                       placeholder="Vendor name"
                                       class="form-control" />

                                @if($errors->has("vendor_name"))
                                    <span class="help-block">{{$errors->first("vendor_name")}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="input-required">Vendor Name (Bangla)</label>
                                <input type="text" @if($readonly) readonly @endif
                                       id="vendor_name_bn"
                                       name="vendor_name_bn"
                                       value="{{ old('vendor_name_bn', ($data)?$data->vendor_name_bn:'') }}"
                                       placeholder="Vendor name bangla"
                                       class="form-control" />

                                @if($errors->has("vendor_name_bn"))
                                    <span class="help-block">{{$errors->first("vendor_name_bn")}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="input-required">Address <span class="required"></span></label>
                                <textarea type="text" name="vendor_address" required @if($readonly) readonly @endif
                                          placeholder="Vendor Address" class="form-control"
                                          style="margin-top: 0px; margin-bottom: 0px; height: 37px;">{{ old('vendor_address', ($data)?trim($data->vendor_address):'') }}
                                            </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="input-required">Email<span class="required"></span></label>
                                <input type="email" @if($readonly) readonly @endif
                                       required
                                       id="email"
                                       name="email"
                                       value="{{ old('email', ($data)?$data->email:'') }}"
                                       placeholder="Contact no"
                                       class="form-control"
                                />
                                @if($errors->has("email"))
                                    <span class="help-block">{{$errors->first("email")}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="input-required">Mobile<span class="required"></span></label>
                                <input type="tel" @if($readonly) readonly @endif
                                       required pattern="[0-9]{11}"
                                       id="mobile_no"
                                       maxlength="11"
                                       name="mobile"
                                       value="{{ old('mobile', ($data)?$data->mobile:'') }}"
                                       placeholder="Mobile No"
                                       class="form-control"
                                />
                                @if($errors->has("mobile"))
                                    <span class="help-block">{{$errors->first("mobile")}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row ">
                            <div class="col-md-12">
                                <label class="input-required">Fax</label>
                                <input type="text" @if($readonly) readonly @endif
                                       id="fax"
                                       name="fax"
                                       value="{{ old('fax', ($data)?$data->fax:'') }}"
                                       placeholder="Fax"
                                       class="form-control"
                                />
                                @if($errors->has("fax"))
                                    <span class="help-block">{{$errors->first("fax")}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


                @if(!$readonly)
                <div class="row">

                    <div class="col-md-12">
                        <div class="row my-1">
                            <div class="col-md-12" style="margin-top: 20px">
                                <div class="d-flex justify-content-end col">
                                    @if (\Request::get('id'))
                                        <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                                            <i class="bx bx-sync"></i> Update</button>
                                        <a href="{{ route('vendors.index') }}" class="btn btn-sm btn-outline-secondary mb-1" style="font-weight: 900;">
                                            <i class="bx bx-arrow-back"></i> Back</a>
                                    @else
                                        <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                                            <i class="bx bx-save"></i> SAVE  </button>
                                        <button type="button" onclick="$('#vendor_info').hide('slow')" class="btn btn btn-outline-dark  mb-1">
                                            <i class="bx bx-window-close"></i> Cancel  </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @endif
            </form>
        </div>
    </div>
</div>
