@extends('layouts.default')

@section('title')
	Service Engineer Visit Result
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
		<div class="card">
			<div class="card-header text-uppercase pb-0">
				<h5 class="card-title text-uppercase">{{ $data && isset($data->action_no)?'Edit':'Add' }}Service Ticket action </h5>
			</div>
			<div class="card-body">
				<form action="@if ($data && $data->action_no) {{route('service_ticket_action.update',['id' => $data->action_no])}} @else {{route('service_ticket_action.store')}} @endif" method="post">
					@if ($data && $data->action_no)
					@method('PUT')
					<input type="hidden" name="action_no" value="{{$data->action_no}}">
					@endif
					@csrf
					<div class="row">
						<div class="col-lg-6">
							<div class="row">
								<div class="col-md-12">
									<label for="action_id" class="form-control-label text-uppercase">action Id</label>
									<input type="text"
									autofocus
									readonly
									name="action_id"
									value="{{ old('action_id', ($data)?$data->action_id:$gen_uniq_id) }}"
									oninput="this.value = this.value.toUpperCase()"
									id="action_id"
									class="form-control"
									>
									@if($errors->has("action_id"))
									<span class="help-block">{{$errors->first("action_id")}}</span>
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row">
								<div class="col-md-12">
									<label for="action_description" class="form-control-label text-uppercase">action description</label>
									<input type="text"
									name="action_description"
									id="action_description"
									value="{{ old('action_description', ($data)?$data->action_description:'') }}"
									oninput="this.value = this.value.toUpperCase()"
									class="form-control">
									@if($errors->has("action_description"))
									<span class="help-block">{{$errors->first("action_description")}}</span>
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
										<button type="submit" name="submit" class="btn btn-sm btn-secondary">Update</button>
										<button type="reset" class="btn btn-sm btn-outline-secondary ml-2"
										onClick="window.location.href='{{ route('service_ticket_action.index') }}'">Back</button>
										@else
										<button type="submit" name="submit" class="btn btn-sm btn-secondary">Save</button>
										<button type="reset" class="btn btn-sm btn-outline-secondary ml-2">Cancel</button>
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
		<h4 class="card-title"> Service Ticket action List</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-12">
				<div class="card-content">
					<div class="table-responsive">
						<table id="" class="table table-sm datatable table-bordered mdl-data-table dataTable text-uppercase" data-url="{{ route('service_ticket_action.list') }}"
						data-csrf="{{ csrf_token() }}" data-page="10">
						<thead class="text-uppercase">
							<tr>
								<th data-col="DT_RowIndex">SL</th>
								<th data-col="action_id">Action ID</th>
								<th data-col="action_description">Action Description</th>
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
