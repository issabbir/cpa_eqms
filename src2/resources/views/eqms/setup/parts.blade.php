@extends('layouts.default')

@section('title')
    Parts Entry
@endsection

@section('header-style')
    <!--Load custom style link or css-->
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mt-0 mb-0">
                <div class="card-content">
                    <div class="card-body" id="vendor_register" >
                        <h4 class="card-title">Parts Entry </h4>
                        <hr>
                        <form id="parts_entry"  method="POST"
                                action="{{ route('equipment-setup.equipments-parts-store') }}">


                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label class="required"> Parts No</label>
                                        <input type="text" class="form-control"
                                        name="parts_no" id="parts_no" placeholder="PARTS NO" value="">
                                        <span class="text-danger">{{ $errors->first('parts_no') }}</span>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="required"> Parts Name</label>
                                        <input type="text" class="form-control"
                                               name="parts_name" id="parts_name" placeholder="PARTS NAME" value="">
                                        <span class="text-danger">{{ $errors->first('parts_name') }}</span>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="required"> Parts No</label>
                                        <input type="text" class="form-control"
                                               name="parts_brand" id="parts_no" placeholder="PARTS BRAND" value="">
                                        <span class="text-danger">{{ $errors->first('parts_brand') }}</span>
                                    </div>

                                </div>
                            </div>
                        </form>

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
