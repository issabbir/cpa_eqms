@extends('layouts.default')

@section('title')
    Service Status
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
		<div class="card" style="@if(\Request::get('id')) display: block @else display: none @endif" id="service_status_form">
			<div class="card-header text-uppercase pb-0">
				<h5 class="card-title text-uppercase">{{ $data && isset($data->status_no)?'Edit':'Add' }} Service Status</h5>
			</div>
			<div class="card-body">
				<form  mathod="Post"  action="@if ($data && $data->status_no) {{route('service_status.update',['id' => $data->status_no])}} @else {{route('service_status.store')}} @endif" method="post">
					@if ($data && $data->status_no)
					@method('PUT')
					<input type="hidden" name="status_no" value="{{$data->status_no}}">
					@endif
					@csrf
					<div class="row">
						<div class="col-lg-6">
							<div class="row">
								<div class="col-md-12">
									<label for="status_id" class="form-control-label text-uppercase">Status Id</label>
									<input type="text"
									readonly
									name="status_id"
									value="{{ old('status_id', ($data)?$data->status_id:$gen_uniq_id) }}"
									id="status_id"
									class="form-control"
									>
									@if($errors->has("status_id"))
									<span class="help-block">{{$errors->first("status_id")}}</span>
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row">
								<div class="col-md-12">
									<label for="status_name" class="form-control-label text-uppercase">status name</label>
									<input type="text" autofocus
									name="status_name" autocomplete="off"
									id="status_name"
									value="{{ old('status_name', ($data)?$data->status_name:'') }}"
									oninput="this.value = this.value.toUpperCase()"
									class="form-control">
									@if($errors->has("status_name"))
									<span class="help-block">{{$errors->first("status_name")}}</span>
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
                                            <a href="{{ route('sub_category.index') }}" class="btn btn-sm btn-outline-secondary mb-1" style="padding-top: 10px; font-weight: 900;">
                                                <i class="bx bx-arrow-back"></i> Back</a>
                                        @else
                                            <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                                                <i class="bx bx-save"></i> SAVE  </button>
                                            <button type="button" onclick="$('#service_status_form').hide('slow')" class="btn btn btn-outline-dark  mb-1">
                                                <i class="bx bx-window-close"></i> Cancel  </button>
                                        @endif
									</div>
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
                <h4 class="card-title text-uppercase">Service Status List</h4>
            </div>
            <div class="col-md-6">
                <div class="row float-right">
                    <button id="show_form" type="button" onclick="$('#service_status_form').toggle('slow')" class="btn btn-secondary mb-1 ml-1 hvr-underline-reveal">
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
						<table id="" class="table table-sm datatable table-bordered mdl-data-table dataTable text-uppercase" data-url="{{ route('service_status.list') }}"
						data-csrf="{{ csrf_token() }}" data-page="10">
						<thead class="text-uppercase">
							<tr>
								<th data-col="DT_RowIndex">SL</th>
								<th data-col="status_id">Status ID</th>
								<th data-col="status_name">Status Name</th>
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
