@extends('layouts.default')

@section('title')
	Service Ticket Priority
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
		<div class="card" id="ticket_priority" style="@if(\Request::get('id')) display: block @else display: none @endif">
			<div class="card-header text-uppercase pb-0">
				<h5 class="card-title text-uppercase">{{ $data && isset($data->ticket_priority_no)?'Edit':'Add' }} Service Ticket Priority</h5>
			</div>
			<div class="card-body">
					<form action="@if ($data && $data->ticket_priority_no) {{route('ticket_priority.update',['id' => $data->ticket_priority_no])}} @else {{route('ticket_priority.store')}} @endif" method="post">
						@if ($data && $data->ticket_priority_no)
						@method('PUT')
						<input type="hidden" name="ticket_priority_no" value="{{$data->ticket_priority_no}}">
						@endif
						@csrf
						<div class="row">
							<div class="col-lg-6">
								<div class="row">
									<div class="col-md-12">
										<label for="ticket_priority_id" class="form-control-label text-uppercase">ticket priority Id</label>
										<input type="text"
										autofocus
										readonly
										name="ticket_priority_id"
										value="{{ old('ticket_priority_id', ($data)?$data->ticket_priority_id:$gen_uniq_id) }}"
										oninput="this.value = this.value.toUpperCase()"
										id="ticket_priority_id"
										class="form-control"
										>
										@if($errors->has("ticket_priority_id"))
										<span class="help-block">{{$errors->first("ticket_priority_id")}}</span>
										@endif
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="row">
									<div class="col-md-12">
										<label for="remarks" class="form-control-label text-uppercase">remarks</label>
										<input type="text"
										name="remarks"
										id="remarks"
										value="{{ old('remarks', ($data)?$data->remarks:'') }}"
										oninput="this.value = this.value.toUpperCase()"
										class="form-control">
										@if($errors->has("remarks"))
										<span class="help-block">{{$errors->first("remarks")}}</span>
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
                                                <a href="{{ route('ticket_priority.index') }}" class="btn btn-sm btn-outline-secondary mb-1" style="font-weight: 900;">
                                                    <i class="bx bx-arrow-back"></i> Back</a>
                                            @else
                                                <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                                                    <i class="bx bx-save"></i> SAVE  </button>
                                                <button type="button" onclick="$('#ticket_priority').hide('slow')" class="btn btn btn-outline-dark  mb-1">
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
	<div class="card-header text-uppercase">
        <div class="row">
            <div class="col-md-6">
                <h4 class="card-title text-uppercase">Service Ticket Priority List</h4>
            </div>
            <div class="col-md-6">
                <div class="row float-right">
                    <button id="show_form" type="button" onclick="$('#ticket_priority').toggle('slow')" class="btn btn-secondary mb-1 ml-1 hvr-underline-reveal">
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
						<table id="" class="table table-sm datatable table-bordered mdl-data-table dataTable text-uppercase" data-url="{{ route('ticket_priority.list') }}"
						data-csrf="{{ csrf_token() }}" data-page="10">
						<thead class="text-uppercase">
							<tr>
								<th data-col="DT_RowIndex">SL</th>
								<th data-col="ticket_priority_id">Ticket Priority ID</th>
								<th data-col="remarks">Remarks</th>
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
