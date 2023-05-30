@extends('layouts.default')

@section('title')
    :: Equipment Activities
@endsection

@section('header-style')
    <!--Load custom style link or css-->
    <style>
        .empId span {
            width: 100% !important;
        }

        .empId span b {
            margin-left: 160px !important;
        }
    </style>
@endsection
@section('content')

    <div class="content-body">
        <section id="form-repeater-wrapper">
            <!-- form default repeater -->
            <div class="row">
                <div class="col-12">

                    @include('eqms.equipactivities.list')
                    @if(isset($mData))
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <form enctype="multipart/form-data"
                                          @if(isset($mData->r_d_id)) action="{{route('equip-activities-update',[$mData->r_d_id])}}"
                                          @endif method="post">
                                        @csrf
                                        @if (isset($mData->r_d_id))
                                            @method('PUT')
                                            <input type="hidden" id="r_d_id" name="r_d_id"
                                                   value="{{isset($mData->r_d_id) ? $mData->r_d_id : ''}}">
                                            <input type="hidden" id="equip_id" name="equip_id"
                                                   value="{{isset($mData->equip_id) ? $mData->equip_id : ''}}">
                                        @endif

                                        <h5 class="card-title">Equipment Activities</h5>
                                        <hr>

                                        <div class="row">
                                            <div class="col-md-3 mt-1">
                                                <label>Roster Name</label>
                                                <input type="text" disabled
                                                       name="r_name"
                                                       class="form-control"
                                                       value="{{isset($mData->r_name) ? $mData->r_name : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Equipment No</label>
                                                <input type="text" disabled
                                                       name="equip_no"
                                                       class="form-control"
                                                       value="{{isset($mData->equip_no) ? $mData->equip_no : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Equipment Name</label>
                                                <input type="text" disabled
                                                       name="equip_name"
                                                       class="form-control"
                                                       value="{{isset($mData->equip_name) ? $mData->equip_name : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Operator Name</label>
                                                <input type="text" disabled
                                                       name="operator_name"
                                                       class="form-control"
                                                       value="{{isset($mData->operator_name) ? $mData->operator_name : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Diesel Issue <small>(Litter)</small></label>
                                                <input type="number"
                                                       @if(isset($mData)) @if($mData->diesel_issue!=null) readonly
                                                       @else required @endif @endif
                                                       name="diesel_issue"
                                                       class="form-control"
                                                       value="{{isset($mData->diesel_issue) ? $mData->diesel_issue : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Equipment Meter Start
                                                    <small>(Hour)</small></label>
                                                <input type="number"
                                                       @if(isset($mData)) @if($mData->equip_meter_start!=null) readonly
                                                       @else required @endif @endif
                                                       name="equip_meter_start"
                                                       class="form-control"
                                                       value="{{isset($mData->equip_meter_start) ? $mData->equip_meter_start : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Equipment Meter End
                                                    <small>(Hour) </small></label>
                                                <input type="number"
                                                       @if(isset($mData)) @if($mData->equip_meter_start==null) readonly
                                                       @else required
                                                       @endif @endif
                                                       name="equip_meter_end"
                                                       class="form-control"
                                                       value="{{isset($mData->equip_meter_end) ? $mData->equip_meter_end : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label class="required">Equipment Return Time</label>
                                                {{--<input type="text"
                                                       @if(isset($mData)) @if($mData->equip_meter_start==null) readonly
                                                       @endif @endif
                                                       name="equip_return_time"
                                                       class="form-control"
                                                       value="{{isset($mData->equip_return_time) ? $mData->equip_return_time : ''}}"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="10"
                                                >--}}

                                                <input type="time" autocomplete="off" class="form-control"
                                                       id="equip_return_time"
                                                       @if(isset($mData)) @if($mData->equip_meter_start==null) readonly
                                                       @else required
                                                       @endif @endif
                                                       name="equip_return_time"
                                                       value="{{isset($mData->equip_return_time) ? date('H:i A', strtotime($mData->equip_return_time)) : ''}}"
                                                />
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Extra Time <small>(Hour)</small></label>
                                                <input type="text" readonly
                                                       name="extra_time_show"
                                                       id="extra_time_show"
                                                       class="form-control"
                                                       {{--value="{{isset($mData->extra_time) ? $mData->extra_time : ''}}"--}}
                                                >
                                                <input type="hidden" name="extra_time" id="extra_time">
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Handle Container 20 </label>
                                                <input type="number"
                                                       @if(isset($mData)) @if($mData->equip_meter_start==null) readonly
                                                       @endif @endif
                                                       name="handle_container_20"
                                                       class="form-control"
                                                       value="{{isset($mData->handle_container_20) ? $mData->handle_container_20 : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Handle Container 40 </label>
                                                <input type="number"
                                                       @if(isset($mData)) @if($mData->equip_meter_start==null) readonly
                                                       @endif @endif
                                                       name="handle_container_40"
                                                       class="form-control"
                                                       value="{{isset($mData->handle_container_40) ? $mData->handle_container_40 : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Handle RS </label>
                                                <input type="number"
                                                       @if(isset($mData)) @if($mData->equip_meter_start==null) readonly
                                                       @endif @endif
                                                       name="handle_rs"
                                                       class="form-control"
                                                       value="{{isset($mData->handle_rs) ? $mData->handle_rs : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-6 mt-1">
                                                <label for="description"
                                                       class="form-control-label text-uppercase">Comments</label>
                                                <textarea name="comments"
                                                          @if(isset($mData)) @if($mData->equip_meter_start==null) readonly
                                                          @endif @endif
                                                          style="height: 37px"
                                                          id="comments"
                                                          oninput="this.value = this.value.toUpperCase()"
                                                          class="form-control"
                                                          cols="30">{{isset($mData->comments) ? $mData->comments : ''}}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group mt-1">
                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                <div class="form-group">
                                                    @if(isset($mData))
                                                        @if($mData->equip_meter_end==null)
                                                            <button id="eq-req-approval" type="submit"
                                                                    class="btn btn-primary mr-1 mb-1">Save
                                                            </button>
                                                        @endif
                                                        <a type="reset" href="{{route("equip-activities-index")}}"
                                                           class="btn btn-light-secondary mb-1"> Back</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>



@endsection

@section('footer-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('input[type=time]').change(function () {
                let time = $('#equip_return_time').val();
                time = convertTime(time);
                let equip_id = $('#equip_id').val();
                if (time) {
                    $.ajax({
                        url: APP_URL + '/show-extra-time/' + time + '/' + equip_id,
                        success: function (msg) {
                            let getTime = JSON.parse(msg);
                            getTime = getTime.result;
                            //alert(t.name[0])

                            let data = getTime.split(':');
                            let hour = data[0];
                            let minute = data[1];
                            console.log(hour);
                            let showTime = hour + ' Hour ' + minute + ' Minute';
                            $('#extra_time_show').val(showTime);
                            $('#extra_time').val(getTime);
                            //console.log(msg.result+'fasdasdasd');
                        }
                    });
                }
            });

        });

        function convertTime(timeString) {
            let H = +timeString.substr(0, 2);
            let h = (H % 12) || 12;
            let ampm = H < 12 ? " AM" : " PM";
            timeString = h + timeString.substr(2, 3) + ampm;
            return timeString;
            //console.log(timeString);
        }

        function eqReqList() {
            let url = '{{route('equip-activities-datatable')}}';
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
                columnDefs: [
                    {className: 'text-center', targets: [6]},
                ],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'r_name', name: 'r_name', searchable: true},
                    {data: 'equip_no', name: 'equip_no', searchable: true},
                    {data: 'equip_name', name: 'equip_name', searchable: false},
                    {data: 'operator_name', name: 'operator_name', searchable: false},
                    {data: 'location', name: 'location', searchable: false},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {

            // var time  = dateTimePicker('#equip_return_time');

            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);

            var r_d_id = '{{isset($mData->r_d_id) ? $mData->r_d_id : ''}}';

            if (r_d_id) {
                $("html, body").animate({scrollTop: $(document).height()}, 1000);
            }
            eqReqList();
        });

    </script>

@endsection

