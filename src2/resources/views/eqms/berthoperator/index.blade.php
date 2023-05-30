@extends('layouts.default')

@section('title')
:: Berth Operator Profile
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
                                      @if(isset($data->bo_id)) action="{{route('berth-operator-update',[$data->bo_id])}}"
                                      @else action="{{route('berth-operator-post')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->bo_id))
                                        @method('PUT')
                                        <input type="hidden" id="bo_id" name="bo_id" value="{{isset($data->bo_id) ? $data->bo_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Berth Operator Profile</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="required">Berth Operator Name</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Berth Operator Name"
                                                   name="bo_name"
                                                   class="form-control" required
                                                   value="{{isset($data->bo_name) ? $data->bo_name : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="250"
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label>Berth Operator Name Bangla</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Berth Operator Name Bangla"
                                                   name="bo_name_bn"
                                                   class="form-control"
                                                   value="{{isset($data->bo_name_bn) ? $data->bo_name_bn : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                        <div class="col-md-6">
                                            <label>Address</label>
                                            <input type="text" autocomplete="ok"
                                                   placeholder="Berth Operator Name Bangla"
                                                   name="bo_address"
                                                   class="form-control"
                                                   value="{{isset($data->bo_address) ? $data->bo_address : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Telephone No</label>
                                            <input type="number" autocomplete="off"
                                                   placeholder="Telephone No"
                                                   name="bo_tel_no"
                                                   class="form-control"
                                                   value="{{isset($data->bo_tel_no) ? $data->bo_tel_no : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="11"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Mobile No</label>
                                            <input type="number" autocomplete="off"
                                                   placeholder="Mobile No"
                                                   name="bo_mobile" required
                                                   class="form-control"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="11"
                                                   minlength="11"
                                                   value="{{isset($data->bo_mobile) ? $data->bo_mobile : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Email</label>
                                            <input type="email" autocomplete="off"
                                                   placeholder="Email"
                                                   name="bo_email"
                                                   pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"
                                                   class="form-control"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="75"
                                                   oninput="this.value = this.value.toLowerCase()"
                                                   value="{{isset($data->bo_email) ? $data->bo_email : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Contact Person</label>
                                            <input type="text" autocomplete="ok"
                                                   placeholder="Contact Person"
                                                   name="bo_contact_person"
                                                   class="form-control"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="75"
                                                   value="{{isset($data->bo_contact_person) ? $data->bo_contact_person : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Contact Person Designation</label>
                                            <input type="text" autocomplete="ok"
                                                   placeholder="Contact Person Designation"
                                                   name="bo_cp_designation"
                                                   class="form-control"
                                                   onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="50"
                                                   value="{{isset($data->bo_cp_designation) ? $data->bo_cp_designation : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Contact Person Mobile</label>
                                            <input type="text" autocomplete="off"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="11"
                                                   placeholder="Contact Person Mobile"
                                                   name="cp_mobile"
                                                   class="form-control"
                                                   value="{{isset($data->cp_mobile) ? $data->cp_mobile : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Contact Person Email</label>
                                            <input type="email"
                                                   placeholder="Contact Person Email"
                                                   name="cp_email" autocomplete="off"
                                                   oninput="this.value = this.value.toLowerCase()"
                                                   class="form-control"
                                                   pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"
                                                   value="{{isset($data->cp_email) ? $data->cp_email : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="50"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Service Start Date:</label>
                                            <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                                                <input type="text"
                                                       value="{{isset($data->service_start_date) ? date('d-m-Y', strtotime($data->service_start_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker3"
                                                       id="service_start_date" required
                                                       name="service_start_date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Service End Date:</label>
                                            <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                                <input type="text"
                                                       value="{{isset($data->service_end_date) ? date('d-m-Y', strtotime($data->service_end_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker4"
                                                       id="service_end_date"
                                                       name="service_end_date"
                                                       autocomplete="off" disabled
                                                />
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <div class="form-group">
                                                <label class="mb-1 required">Active?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="active_yn" id="reporter_outsider_no1" checked
                                                               value="{{ \App\Enums\YesNoFlag::YES }}"
                                                               @if(isset($data->active_yn) && $data->active_yn == "Y") checked @endif
                                                               @if(isset($data->active_yn) && $data->active_yn == "N") disabled @endif/>
                                                        <label class="form-check-label">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="active_yn"
                                                               id="reporter_cpa_no1" value="{{ \App\Enums\YesNoFlag::NO }}"
                                                               @if(isset($data->active_yn) && $data->active_yn == "N") checked @endif
                                                               @if(isset($data->active_yn) && $data->active_yn == "N") disabled @endif/>
                                                        <label class="form-check-label">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                @if(!isset($data))
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Save
                                                    </button>
                                                @else
                                                    @if(isset($data->active_yn) && $data->active_yn == "Y")
                                                        <button id="boat-employee-save" type="submit"
                                                                class="btn btn-primary mr-1 mb-1">Update
                                                        </button>
                                                    @endif
                                                @endif

                                                    <a type="reset" href="{{route("berth-operator-index")}}"
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

    @include('eqms.berthoperator.list')

@endsection

@section('footer-script')

    <script type="text/javascript">

        function berthOperatorList() {
            var url = '{{route('berth-operator-datatable')}}';
            var oTable =$('#searchResultTable').DataTable({
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
                    {data: 'bo_name', name: 'bo_name', searchable: true},
                    {data: 'bo_mobile', name: 'bo_mobile', searchable: true},
                    {data: 'bo_contact_person', name: 'bo_contact_person', searchable: true},
                    {data: 'cp_mobile', name: 'cp_mobile', searchable: true},
                    {data: 'service_start_date', name: 'service_start_date'},
                    {data: 'service_end_date', name: 'service_end_date', searchable: true},
                    {data: 'active_yn', name: 'active_yn', searchable: true},
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
            maxSysDatePicker('#datetimepicker3');
            berthOperatorList();
        });

    </script>

@endsection

