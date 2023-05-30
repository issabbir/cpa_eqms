@extends('layouts.default')

@section('title')
    :: Workshop Setup
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
                                      @if(isset($data->workshop_id)) action="{{route('workshop-update',[$data->workshop_id])}}"
                                      @else action="{{route('workshop-post')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->workshop_id))
                                        @method('PUT')
                                        <input type="hidden" id="workshop_id" name="workshop_id"
                                               value="{{isset($data->workshop_id) ? $data->workshop_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Workshop Setup</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Workshop Type</label>
                                            <select class="custom-select select2 form-control" required id="wrokshop_type_id"
                                                    name="wrokshop_type_id">
                                                <option value="">Select One</option>
                                                @foreach($workshoptyp as $value)
                                                    <option value="{{$value->w_t_id}}"
                                                        {{isset($data->wrokshop_type_id) && $data->wrokshop_type_id == $value->w_t_id ? 'selected' : ''}}
                                                    >{{$value->w_t_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Workshop Name</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Workshop Name" required
                                                   name="workshop_name"
                                                   class="form-control"
                                                   value="{{isset($data->workshop_name) ? $data->workshop_name : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="100"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Workshop Name (Bangla)</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Workshop Name (Bangla)"
                                                   name="workshop_name_bn"
                                                   class="form-control"
                                                   value="{{isset($data->workshop_name_bn) ? $data->workshop_name_bn : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="3000"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Workshop Address</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Workshop Address"
                                                   name="workshop_address"
                                                   class="form-control"
                                                   value="{{isset($data->workshop_address) ? $data->workshop_address : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
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

                                                <a type="reset" href="{{route("workshop-index")}}"
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

    @include('eqms.workshopsetup.list')

@endsection

@section('footer-script')

    <script type="text/javascript">

        function workshopList() {
            let url = '{{route('workshop-datatable')}}';
            let oTable = $('#searchResultTable').DataTable({
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
                    {data: 'workshop_name', name: 'workshop_name', searchable: true},
                    {data: 'workshop_address', name: 'workshop_address', searchable: true},
                    {data: 'w_t_name', name: 'w_t_name', searchable: true},
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
            workshopList();
        });

    </script>

@endsection

