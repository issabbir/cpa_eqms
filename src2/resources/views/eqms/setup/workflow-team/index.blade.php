@extends('layouts.default')

@section('title')
Work Flow Setup
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            @if(Session::has('message'))
                <div class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                        role="alert" style="margin-bottom: 5px;">
                    {{ Session::get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">

                <!-- Table Start -->
                {{--                @dd($data->inspector_type_id);--}}
                <div class="card-body">
                    <h4 class="card-title">Workflow Team Form</h4>
                    <hr>


                    <form action="{{route('workflow-team-post')}}" method="post" onsubmit="return checkForm();">
                        @csrf
                        <input type="hidden" value="{{$data->team_id}}" name="team_id">


                        <div class="row">
                            <div class="col-md-4">
                                <label class="required">Select Department for Workflow</label>
                                <select class="form-control select2" name="department_id" id="department_id">
                                    <option value="">Select One</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->department_id}}"
                                            @if(isset($data->department_id) && $data->department_id == $department->department_id)
                                            selected @else
                                            @if($dpt_department_id == $department->department_id) selected
                                            @endif
                                            @endif
                                            >{{$department->department_name}}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="col-md-4">
                                <label class="required">Select Department for Employee Search</label>
                                <select class="form-control select2" name="emp_department_id" id="emp_department_id">
                                    <option value="">Select One</option>
                                    @foreach($alldepartments as $alldepartment)
                                        <option value="{{$alldepartment->department_id}}" @if(isset($data->emp_department_id) && $data->emp_department_id == $alldepartment->department_id)
                                        selected @endif>{{$alldepartment->department_name}}</option>
                                    @endforeach
                                </select>
                                @error('emp_department_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="col-md-4">
                                <label class="required">Select Employee</label><br/>
                                <select class="form-control select2" name="p_employee_id" id="p_employee_id">
                                    <option value="">Select Employee</option>
                                    @if(old('p_employee_id',$data->emp_id))
                                        <option value="{{old('p_employee_id',$data->emp_id)}}"
                                                selected>{{isset($data) ? $data->emp_code.' '.$data->emp_name :''}}</option>
                                    @endif
                                </select>
                                @error('p_employee_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            {{-- <div class="col-md-4">
                                <label class="required">Sequence No</label>
                                <input type="number" placeholder=" Enter Sequence Number"
                                        name="sequence_no" class="form-control"   value="{{old('sequence_no',$data->seq_no)}}"
                                        id="sequence_no" maxlength="255">

                                @error('sequence_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> --}}

                            <div class="col-md-4 mt-1">
                                <label class="">Active</label>
                                <div class="ml-2 custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="active_yes" name="active_yn" value="Y" {{old('active_yn',$data->active_yn) != 'N' ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="active_yes">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="active_no" name="active_yn" value="N" {{old('active_yn',$data->active_yn) == 'N' ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="active_no">No</label>
                                </div>
                                @error('active_yn')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>

                        {{-- <div class="row">
                            <div class="col-md-4"  style="margin-top: 27px">
                                <label class="">Active</label>
                                <div class="ml-2 custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="active_yes" name="active_yn" value="Y" {{old('active_yn',$data->active_yn) != 'N' ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="active_yes">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="active_no" name="active_yn" value="N" {{old('active_yn',$data->active_yn) == 'N' ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="active_no">No</label>
                                </div>
                                @error('active_yn')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div> --}}


                        <div class="row mt-2">
                            <div class="col-md-12 text-right" id="add">
                                @if($data->team_id)
                                    <button type="submit" id="add"
                                            class="btn btn btn-dark shadow mr-1 mb-1 btn-info"><i class="bx bx-save"></i> Update
                                    </button>
                                    <a href="{{ url()->previous() }}">
                                        <button type="button" id="add" class="btn btn btn-dark shadow mr-1 mb-1 btn-danger"><i class="bx bx-sync"></i> Cancel
                                        </button>
                                    </a>
                                @else
                                <button type="submit" id="add"
                                        class="btn btn btn-dark shadow mr-1 mb-1 btn-info"><i class="bx bx-save"></i> Save
                                </button>
                                <button type="reset" id="reset"
                                        class="btn btn btn-outline shadow mb-1 btn-danger"><i class="bx bx-sync"></i> Reset
                                </button>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>

            </div>
            @include('eqms.setup.workflow-team.list')
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">


        $('#p_employee_id').select2({
            minimumInputLength: 1,
            dropdownPosition: 'below',
            ajax: {
                url: '{{route('searchEmpByCurrentDepartment')}}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        emp_name: params.term,
                        emp_depart_id: $('#emp_department_id').val()
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.results, function (obj) {
                            return {
                                id: obj.employee_id,
                                text: obj.emp_code + '-' + obj.emp_name,
                                department: obj.department
                            };
                        })
                    };
                },
                cache: false
            },

        });

        function requisitionList() {
            var url = '{{route('workflow-team-datatable-list')}}';
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
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex', searchable: true},
                    {data: 'employee_name', name: 'employee_name', searchable: true},
                    // {data: 'seq_no', name: 'seq_no', searchable: true},
                    {data: 'department_name', name: 'department_name', searchable: true},
                    {data: 'active_yn', name: 'active_yn', searchable: true},
                    {data: 'action', name: 'action', searchable: false,class:"text-center"},
                ]
            });
        };


        $(document).ready(function() {
            requisitionList();

        });





        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
                return false;

            return true;
        }


    </script>

@endsection
