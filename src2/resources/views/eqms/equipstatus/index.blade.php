@extends('layouts.default')

@section('title')
    :: Equipment Status
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="card">
        <!-- Table Start -->
        <div class="card-body">
            <h4 class="card-title">Equipment Status</h4>
            <hr>
            <form method="POST" id="search-form" name="search-form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="custom-select form-control select2" id="status"
                                    name="status">
                                <option value="">Select One</option>
                                <option value="IDLE">IDLE</option>
                                <option value="WORKING">WORKING</option>
                                <option value="BREAKDOWN">BREAKDOWN</option>
                                <option value="SCHEDULED SERVICE">SCHEDULED SERVICE</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Load Capacity</label>
                            <select class="custom-select form-control select2" id="load_capacity_id"
                                    name="load_capacity_id">
                                <option value="">Select One</option>
                                @foreach($loadCapacty as $value)
                                    <option value="{{$value->load_capacity_id}}">{{$value->load_capacity}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mt-2">
                        <div class="d-flex justify-content-start col">
                            <button type="submit" class="btn btn btn-dark shadow mb-1 btn-secondary">
                                <i class="bx bx-search"></i> Search
                            </button>
                            <a class="btn btn-dark btn-secondary ml-1 mb-1" target="_blank"
                               href="{{url('/report/render/RPT_THIRD_PARTY_SERVICE_DETAILS?xdo=/~weblogic/CCMS/RPT_THIRD_PARTY_SERVICE_DETAILS.xdo&P_THIRD_PARTY_SERVICE_ID='.\Request::get('id').'&type=pdf&filename=RPT_THIRD_PARTY_SERVICE_DETAILS')}}">
                                <i class="bx bx-printer"></i> Print</a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @include('eqms.equipstatus.list')
@endsection

@section('footer-script')

    <script type="text/javascript">

        let url = '{{route('equipment-status-datatable')}}';
        let oTable = $('#searchResultTable').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: url,
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    d.status = $('#status').val();
                    d.load_capacity_id = $('#load_capacity_id').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'load_capacity', name: 'load_capacity', searchable: true},
                {data: 'equip_name', name: 'equip_name', searchable: true},
                {data: 'manufacturer_name', name: 'manufacturer_name', searchable: true},
                {data: 'equip_model', name: 'equip_model', searchable: true},
                {data: 'action', name: 'action', searchable: true},
            ]
        });

        $(document).ready(function () {
            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                oTable.draw();
                $('#hide_show').show();
                let status = $('#status').val();
                let load = $('#load_capacity_id').val();

                let processUrl = '{{url('/report/render/RPT_EQUIPMENT_STATUS?xdo=/~weblogic/EQMS/RPT_EQUIPMENT_STATUS.xdo&p_status=:param1&p_load_capacity_id=:param2&type=pdf&filename=RPT_EQUIPMENT_STATUS')}}';
                processUrl = processUrl.replace(':param1', status);
                processUrl = processUrl.replace(':param2', load);
                let urlString = processUrl.replace(/&amp;/g, '&');
                $("#go_there").attr("href", urlString);


                //let newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + "?status="+ status+ "&load="+ load;
                //window.history.pushState({ path: newurl }, '', newurl);
            });
        });

    </script>

@endsection

