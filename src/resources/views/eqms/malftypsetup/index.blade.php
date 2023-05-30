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
                                      @if(isset($data->malfunction_id)) action="{{route('malfunction-type-update',[$data->malfunction_id])}}"
                                      @else action="{{route('malfunction-type-post')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->malfunction_id))
                                        @method('PUT')
                                        <input type="hidden" id="malfunction_id" name="malfunction_id"
                                               value="{{isset($data->malfunction_id) ? $data->malfunction_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Malfunction Type Setup</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Malfunction</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Malfunction"
                                                   name="malfunction"
                                                   class="form-control" required
                                                   value="{{isset($data->malfunction) ? $data->malfunction : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="100"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label class="required">Malfunction(BN)</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Malfunction(Bangla)"
                                                   name="malfunction_bn"
                                                   class="form-control" required
                                                   value="{{isset($data->malfunction_bn) ? $data->malfunction_bn : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="3000"
                                            >
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label class="required">Description</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Description"
                                                   name="description"
                                                   class="form-control"
                                                   value="{{isset($data->description) ? $data->description : ''}}"
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

    @include('eqms.malftypsetup.list')

@endsection

@section('footer-script')

    <script type="text/javascript">

        function partsList() {
            var url = '{{route('malfunction-type-datatable')}}';
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
                    {data: 'malfunction', name: 'malfunction', searchable: true},
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

