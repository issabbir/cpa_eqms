@extends('layouts.default')

@section('title')
    :: Parts Setup
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
                                      @if(isset($data->part_id)) action="{{route('parts-entry-update',[$data->part_id])}}"
                                      @else action="{{route('parts-entry-post')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->part_id))
                                        @method('PUT')
                                        <input type="hidden" id="part_id" name="part_id"
                                               value="{{isset($data->part_id) ? $data->part_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Parts Setup</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Part No</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Part No"
                                                   name="part_no" @if(isset($data->part_no)) readonly @endif
                                                   class="form-control" required
                                                   value="{{isset($data->part_no) ? $data->part_no : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="25"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Part Name</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Part Name"
                                                   name="part_name"
                                                   class="form-control"
                                                   value="{{isset($data->part_name) ? $data->part_name : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="250"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Brand</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Brand"
                                                   name="part_brand"
                                                   class="form-control"
                                                   value="{{isset($data->part_brand) ? $data->part_brand : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="250"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Origin Country</label>
                                            <select class="custom-select select2 form-control" required
                                                    id="origin_country_id" name="origin_country_id">
                                                <option value="">Select One</option>
                                                @foreach($countryList as $value)
                                                    <option value="{{$value->country_id}}"
                                                        {{isset($data->country_id) && $data->country_id == $value->country_id ? 'selected' : ''}}
                                                    >{{$value->country}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Supplier</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Supplier"
                                                   name="supplier"
                                                   class="form-control"
                                                   value="{{isset($data->supplier) ? $data->supplier : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="200"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Variant</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Variant"
                                                   name="varient"
                                                   class="form-control"
                                                   value="{{isset($data->varient) ? $data->varient : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="50"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Part Category</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Part Category"
                                                   name="part_category"
                                                   class="form-control"
                                                   value="{{isset($data->part_category) ? $data->part_category : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="100"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Purchase Date:</label>
                                            <div class="input-group date" id="datetimepicker"
                                                 data-target-input="nearest">
                                                <input type="text" required
                                                       value="{{isset($data->purchase_date) ? date('d-m-Y', strtotime($data->purchase_date)) : ''}}"
                                                       class="form-control datetimepicker-input"
                                                       data-toggle="datetimepicker" data-target="#datetimepicker"
                                                       id="purchase_date"
                                                       name="purchase_date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                @if(!isset($data))
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Save
                                                    </button>
                                                @else
                                                    <button id="boat-employee-save" type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Update
                                                    </button>
                                                @endif

                                                <a type="reset" href="{{route("parts-entry-index")}}"
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

    @include('eqms.partssetup.list')

@endsection

@section('footer-script')

    <script type="text/javascript">

        function partsList() {
            var url = '{{route('parts-entry-datatable')}}';
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
                    {data: 'part_no', name: 'part_no', searchable: true},
                    {data: 'part_name', name: 'part_name', searchable: true},
                    {data: 'part_brand', name: 'part_brand', searchable: true},
                    {data: 'part_made', name: 'part_made', searchable: true},
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

            datePicker('#datetimepicker');
            partsList();
        });

    </script>

@endsection

