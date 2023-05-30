@extends('layouts.default')

@section('title')
    :: Service Setup
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
                                      @if(isset($data->service_id)) action="{{route('service-entry-update',[$data->service_id])}}"
                                      @else action="{{route('service-entry-post')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->service_id))
                                        @method('PUT')
                                        <input type="hidden" id="service_id" name="service_id"
                                               value="{{isset($data->service_id) ? $data->service_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Service Setup</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4 mt-1">
                                            <label class="required">Service Name</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Service Name"
                                                   name="service"
                                                   class="form-control" required
                                                   value="{{isset($data->service) ? $data->service : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="500"
                                            >
                                        </div>
                                        <div class="col-md-4 mt-1">
                                            <label>Service Name Bangla</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Service Name Bangla"
                                                   name="service_bn"
                                                   class="form-control"
                                                   value="{{isset($data->service_bn) ? $data->service_bn : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="300"
                                            >
                                        </div>
                                        <div class="col-md-4 mt-1">
                                            <div class="form-group">
                                                <label class="mb-1 required">Active?</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="active_yn" id="service_y" checked
                                                               value="{{ \App\Enums\YesNoFlag::YES }}"
                                                               @if(isset($data->active_yn) && $data->active_yn == "Y") checked @endif/>
                                                        <label class="form-check-label">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="active_yn"
                                                               id="service_n" value="{{ \App\Enums\YesNoFlag::NO }}"
                                                               @if(isset($data->active_yn) && $data->active_yn == "N") checked @endif/>
                                                        <label class="form-check-label">No</label>
                                                    </div>
                                                </div>
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

                                                <a type="reset" href="{{route("service-entry-index")}}"
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

    @include('eqms.servicesetup.list')

@endsection

@section('footer-script')

    <script type="text/javascript">

        function serviceList() {
            var url = '{{route('service-entry-datatable')}}';
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
                    {data: 'service', name: 'service', searchable: true},
                    {data: 'active_yn', name: 'active_yn', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            serviceList();
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);
        });

    </script>

@endsection

