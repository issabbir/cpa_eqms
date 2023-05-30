@extends('layouts.default')

@section('title')
    Vendor Type
@endsection

@section('header-style')
	<style type="text/css">
		.table td:nth-last-child(1), th:nth-last-child(1) {
		    text-align: center;
		}
	</style>
@endsection
@section('content')
<div class="row">
	<div class="col-12">
		<div class="card" id="vendor_type" style="@if(\Request::get('id')) display: block @else display: none @endif">
			<div class="card-header text-uppercase pb-0">
				<h5 class="card-title text-uppercase">{{ $data && isset($data->vendor_type_no)?'Edit':'Add' }} vendor Type</h5>
			</div>
			<div class="card-body">
				<form action="@if ($data && $data->vendor_type_no) {{route('vendor_type.update',['id' => $data->vendor_type_no])}} @else {{route('vendor_type.store')}} @endif" method="post">
					@if ($data && $data->vendor_type_no)
					@method('PUT')
					<input type="hidden" name="vendor_type_no" value="{{$data->vendor_type_no}}">
					@endif
					@csrf
					<div class="row">
						<div class="col-lg-6">
							<div class="row">
								<div class="col-md-12">
									<label for="vendor_type_id" class="form-control-label text-uppercase">vendor type Id</label>
									<input type="text"
									autofocus
									readonly
									name="vendor_type_id"
									value="{{ old('vendor_type_id', ($data)?$data->vendor_type_id:$gen_uniq_id) }}"
									oninput="this.value = this.value.toUpperCase()"
									id="vendor_type_id"
									class="form-control"
									>
									@if($errors->has("vendor_type_id"))
									<span class="help-block">{{$errors->first("vendor_type_id")}}</span>
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row">
								<div class="col-md-12">
									<label for="vendor_type_name" class="form-control-label text-uppercase">vendor type name</label>
									<input type="text"
									name="vendor_type_name"
									id="vendor_type_name"
									value="{{ old('vendor_type_name', ($data)?$data->vendor_type_name:'') }}"
									oninput="this.value = this.value.toUpperCase()"
									class="form-control">
									@if($errors->has("vendor_type_name"))
									<span class="help-block">{{$errors->first("vendor_type_name")}}</span>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 mt-2">
							<div class="row my-1">
								<div class="col-md-12">
									<div class="d-flex justify-content-end">
                                        @if (\Request::get('id'))
                                            <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                                                <i class="bx bx-sync"></i> Update</button>
                                            <a href="{{ route('vendor_type.index') }}" class="btn btn-sm btn-outline-secondary mb-1" style="font-weight: 900;">
                                                <i class="bx bx-arrow-back"></i> Back</a>
                                        @else
                                            <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                                                <i class="bx bx-save"></i> SAVE  </button>
                                            <button type="button" onclick="$('#vendor_type').hide('slow')" class="btn btn btn-outline-dark  mb-1">
                                                <i class="bx bx-window-close"></i> Cancel  </button>
                                        @endif
									</div>
								</div>
							</div>
						</div>
					</div>
			</form>
		</div>
	</div>
</div>
</div>

<div class="card">
	<div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="card-title text-uppercase">vendor Type List</h4>
            </div>
            <div class="col-md-6">
                <div class="row float-right">
                    <button id="show_form" type="button" onclick="$('#vendor_type').toggle('slow')" class="btn btn-secondary mb-1 ml-1 hvr-underline-reveal">
                        <i class="bx bx-plus"></i> Add New</button>
                </div>
            </div>
        </div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-12">
				<div class="card-content">
					<div class="table-responsive">
						<table id="" class="table table-sm datatable table-bordered mdl-data-table dataTable text-uppercase" data-url="{{ route('vendor_type.list') }}"
						data-csrf="{{ csrf_token() }}" data-page="10">
						<thead class="text-uppercase">
							<tr>
								<th data-col="DT_RowIndex">SL</th>
								<th data-col="vendor_type_id">Vendor Type ID</th>
								<th data-col="vendor_type_name">Vendor Type Name</th>
								<th data-col="action">Action</th>
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
<script type="text/javascript">
	$(document).ready(function () {

	});
</script>

@endsection
