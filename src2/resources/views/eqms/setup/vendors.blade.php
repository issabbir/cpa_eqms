@extends('layouts.default')

@section('title')
    Manage vendors
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
        @include('ccms/setup/partials/vendor_form')
        <!--List-->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title text-uppercase">Vendor Register List</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="row float-right">
                                <button id="show_form" type="button" onclick="$('#vendor_info').toggle('slow')" class="btn btn-secondary mb-1 ml-1 hvr-underline-reveal">
                                    <i class="bx bx-plus"></i> Add New</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable"
                                   data-url="{{ route('vendors.data')}}"
                                   data-csrf="{{ csrf_token() }}" data-page="10">
                                <thead>
                                <tr>
                                    <th data-col="DT_RowIndex">SL</th>
                                    <th data-col="vendor_name">VENDOR NAME</th>
                                    <th data-col="mobile">MOBILE</th>
                                    <th data-col="vendor_address">VENDOR ADDRESS</th>
                                    <th data-col="action">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer-script')
    <!--Load custom script-->
    <script>
        $(document).ready(function () {
            $('#enlistment_date').datetimepicker(
                {
                    format: 'DD-MM-YYYY',
                }
            );
        });
    </script>
@endsection
