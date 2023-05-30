@extends('layouts.default')

@section('title')
    :: Work Type Setup
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
                                      @if(isset($data->work_type_id)) action="{{route('work-type-update',[$data->work_type_id])}}"
                                      @else action="{{route('work-type-post')}}" @endif method="post">
                                    @csrf
                                    @if (isset($data->work_type_id))
                                        @method('PUT')
                                        <input type="hidden" id="work_type_id" name="work_type_id"
                                               value="{{isset($data->work_type_id) ? $data->work_type_id : ''}}">
                                    @endif

                                    <h5 class="card-title">Work Type Setup</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mt-1">
                                            <label class="required">Work Type</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Work Type"
                                                   name="work_type"
                                                   class="form-control"
                                                   value="{{isset($data->work_type) ? $data->work_type : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="50"
                                            >
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <label class="required">Work Type (Bangla)</label>
                                            <input type="text" autocomplete="off"
                                                   placeholder="Work Type (Bangla)"
                                                   name="work_type_bn"
                                                   class="form-control"
                                                   value="{{isset($data->work_type_bn) ? $data->work_type_bn : ''}}"
                                                   oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                   maxlength="3000"
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

                                                <a type="reset" href="{{route("work-type-index")}}"
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

    @include('eqms.worktypesetup.list')

@endsection

@section('footer-script')

    <script type="text/javascript">

        function worktypeList() {
            let url = '{{route('work-type-datatable')}}';
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
                    {data: 'work_type', name: 'work_type', searchable: true},
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
            worktypeList();
        });

    </script>

@endsection

