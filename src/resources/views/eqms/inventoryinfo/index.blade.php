@extends('layouts.default')

@section('title')
    :: Inventory Info
@endsection

@section('header-style')
    <!--Load custom style link or css-->

@endsection
@section('content')
    <div class="card">
        <!-- Table Start -->
        <div class="card-body">
            <h4 class="card-title">Inventory Info</h4>
            <hr>
            <form method="POST" id="search-form" name="search-form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Equipment</label>
                            <select class="custom-select form-control select2" id="equip_id"
                                    name="equip_id">
                                <option value="">Select One</option>
                                @if(isset($equipList))
                                    @foreach($equipList as $value)
                                        <option
                                            value="{{$value->equip_id}}">{{$value->equip_no.' - '.$value->equip_name}}</option>
{{--                                      value="{{isset($value->equip_id) ? $value->equip_no.' - '.$value->equip_name : '' }}"</option>--}}
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Repair Request No</label>
                            <select class="custom-select form-control select2" id="r_r_mst_id"
                                    name="r_r_mst_id">
                                <option value="">Select One</option>
                                @if(isset($repreqList))
                                    @foreach($repreqList as $value)
                                        <option value="{{$value->r_r_mst_id}}">{{$value->r_r_no}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mt-2">
                        <div class="d-flex justify-content-start col">
                            <button type="submit" class="btn btn btn-dark shadow mb-1 btn-secondary">
                                <i class="bx bx-search"></i> Search
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @include('eqms.inventoryinfo.list')
    @if(isset($data))
        <div class="content-body">
            <section id="form-repeater-wrapper">
                <!-- form default repeater -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <form enctype="multipart/form-data">

                                        <h5 class="card-title">Inventory Info Detail</h5>{{--{{dd($data)}}--}}
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3 mt-1">
                                                <label>Repair Request No</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($data->r_r_no) ? $data->r_r_no : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Demand No</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($data->demand_no) ? $data->demand_no : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Equipment</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($data->equip_name) ? $data->equip_name.'('.$data->equip_no.')' : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Repair Request Approve Date</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($approve_date) && $approve_date!=null ? date('d-m-Y', strtotime($approve_date)) : '---'}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>MANAGEMENT Workshop</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($data->workshop_name) ? $data->workshop_name : '---'}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Demand APPROVAL STATUS</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($data->approval_status) ? $data->approval_status : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Demand ISSUE STATUS</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($data->issued_yn) ? $data->issued_yn : ''}}"
                                                >
                                            </div>
                                            <div class="col-md-3 mt-1">
                                                <label>Demand APPROVAL DATE</label>
                                                <input type="text" disabled
                                                       class="form-control"
                                                       value="{{isset($data->approved_date) ? date('d-m-Y', strtotime($data->approved_date)) : '---'}}"
                                                >
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped table-bordered"
                                                           id="table-operator">
                                                        <thead>
                                                        <tr>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="1" class="text-center" width="1%">SL
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                Item
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                Item Code
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                Demand QTY
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                APPROVED QTY
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                ISSUED QTY
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">
                                                        @if(!empty($dData))
                                                            @php

                                                             $count = 1;
                                                            @endphp
                                                            @foreach($dData as $key=>$value)
                                                                <tr role="row">
                                                                    <td aria-colindex="1" role="cell"
                                                                        class="text-center">
                                                                        {{$count++}}
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->item_name}}</td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{ $value->item_code }}</td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->demand_qty}}</td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->approved_qty}}</td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->issued_qty}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mt-1">
                                            <div class="col-md-12 pr-0 d-flex justify-content-end">
                                                <div class="form-group">
                                                    @if(isset($data))
                                                        <a type="reset" href="{{route("inventory-info-index")}}"
                                                           class="btn btn-light-secondary mb-1"> Back</a>
                                                    @endif


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
    @endif


@endsection

@section('footer-script')

    <script type="text/javascript">

        let url = '{{route('inventory-info-datatable')}}';
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
                    d.r_r_mst_id = $('#r_r_mst_id').val();
                    d.equip_id = $('#equip_id').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'r_r_no', name: 'r_r_no', searchable: true},
                {data: 'demand_no', name: 'demand_no', searchable: true},
                {data: 'equip_name', name: 'equip_name', searchable: true},
                {data: 'workshop_name', name: 'workshop_name', searchable: true},
                {data: 'issued_yn', name: 'issued_yn', searchable: true},
                {data: 'approval_status', name: 'approval_status', searchable: true},
                {data: 'approved_date', name: 'approved_date', searchable: true},
                {data: 'action', name: 'action', searchable: true},
            ]
        });

        $(document).ready(function () {
            var item_demand_mst_id = '{{isset($data->item_demand_mst_id) ? $data->item_demand_mst_id : ''}}';

            if (item_demand_mst_id) {
                $("html, body").animate({scrollTop: $(document).height()}, 1000);
            }
            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                oTable.draw();
            });
        });

    </script>

@endsection

