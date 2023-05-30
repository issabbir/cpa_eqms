@extends('layouts.default')

@section('title')
    :: Parts Stock
@endsection

@section('header-style')
    <!--Load custom style link or css-->

@endsection
@section('content')

    @include('eqms.partsstock.list')
@if(isset($data))
    <div class="content-body">
        <section id="form-repeater-wrapper">
            <!-- form default repeater -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <form enctype="multipart/form-data"                                      >

                                    <h5 class="card-title">Part Detail</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 mt-1">
                                            <label>Part No</label>
                                            <input type="text" disabled
                                                   class="form-control"
                                                   value="{{isset($data->part_no) ? $data->part_no : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Part Name</label>
                                            <input type="text" disabled
                                                   class="form-control"
                                                   value="{{isset($data->part_name) ? $data->part_name : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Brand</label>
                                            <input type="text" disabled
                                                   class="form-control"
                                                   value="{{isset($data->part_brand) ? $data->part_brand : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Origin Country</label>
                                            <input type="text" disabled
                                                   class="form-control"
                                                   value="{{isset($data->part_made) ? $data->part_made : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Supplier</label>
                                            <input type="text" disabled
                                                   class="form-control"
                                                   value="{{isset($data->supplier) ? $data->supplier : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Variant</label>
                                            <input type="text" disabled
                                                   class="form-control"
                                                   value="{{isset($data->varient) ? $data->varient : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Part Category</label>
                                            <input type="text" disabled
                                                   class="form-control"
                                                   value="{{isset($data->part_category) ? $data->part_category : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>Purchase Date:</label>
                                            <input type="text" disabled
                                                   class="form-control"
                                                   value="{{isset($data->purchase_date) ? date('d-m-Y', strtotime($data->purchase_date)) : ''}}"
                                            >
                                        </div>
                                        <div class="col-md-3 mt-1">
                                            <label>In Stock</label>
                                            <input type="text" disabled
                                                   class="form-control"
                                                   value="{{isset($data->stock_qty) ? $data->stock_qty : ''}}"
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">
                                            <div class="form-group">
                                                @if(isset($data))
                                                    <a type="reset" href="{{route("parts-stock-index")}}"
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

        function partsList() {
            var url = '{{route('parts-stock-datatable')}}';
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
                    {data: 'part_name', name: 'part_name', searchable: true},
                    {data: 'part_made', name: 'part_made', searchable: true},
                    {data: 'stock_qty', name: 'stock_qty', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            var s_p_stock_id = '{{isset($data->s_p_stock_id) ? $data->s_p_stock_id : ''}}';

            if (s_p_stock_id) {
                $("html, body").animate({scrollTop: $(document).height()}, 1000);
            }
            partsList();
        });

    </script>

@endsection

