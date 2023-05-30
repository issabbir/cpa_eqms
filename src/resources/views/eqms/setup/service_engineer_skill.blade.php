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
		<div class="card" style="@if(\Request::get('id')) display: block @else display: none @endif" id="engineer_skill_form">
			<div class="card-header pb-0">
				<h4 class="card-title text-uppercase"> {{ $data && isset($data->service_skill_id)?'Edit':'Add' }} Service Engineer SKILL</h4>
			</div>
			<div class="card-body">
				<form  action="@if ($data && $data->service_skill_id) {{route('engineer_skill.update',['id' => $data->service_skill_id])}} @else {{route('engineer_skill.store')}} @endif" method="post">
					@if ($data && $data->service_skill_id)
					    @method('PUT')
					    <input type="hidden" name="service_skill_id" value="{{$data->service_skill_id}}">
					@endif
					@csrf
					<div class="row">
						<div class="col-lg-6">
							<div class="row">
								<div class="col-md-12">
									<label for="service_skill_name" class="form-control-label text-uppercase">Service Skill Name</label>
									<input type="text"
									autofocus autocomplete="off"
									name="service_skill_name"
									value="{{ old('service_skill_name', ($data)?$data->service_skill_name:'') }}"
									oninput="this.value = this.value.toUpperCase()"
									id="service_skill_name"
									class="form-control"
									>
									@if($errors->has("service_skill_name"))
									<span class="help-block">{{$errors->first("service_skill_name")}}</span>
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row">
								<div class="col-md-12">
									<label for="description" class="form-control-label text-uppercase">Description</label>
									<textarea name="description"
									style="height: 37px"
									id="description"
									oninput="this.value = this.value.toUpperCase()"
									class="form-control"
									cols="30">{{ old('description', ($data)?$data->description:'') }}</textarea>
									@if($errors->has("description"))
									<span class="help-block">{{$errors->first("description")}}</span>
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
                                                <a href="{{ route('engineer_skill.index') }}" class="btn btn-sm btn-outline-secondary mb-1" style="font-weight: 900;">
                                                    <i class="bx bx-arrow-back"></i> Back</a>
                                            @else
                                                <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                                                    <i class="bx bx-save"></i> SAVE  </button>
                                                <button type="button" onclick="$('#engineer_skill_form').hide('slow')" class="btn btn btn-outline-dark  mb-1">
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

	<div class="card">
		<div class="card-header pb-0">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Service Engineer SKILL List</h4>
                </div>
                <div class="col-md-6">
                    <div class="row float-right">
                        <button id="show_form" type="button" onclick="$('#engineer_skill_form').toggle('slow')" class="btn btn-secondary mb-1 ml-1 hvr-underline-reveal">
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
	                        <table id="" class="table table-sm datatable table-bordered mdl-data-table dataTable text-uppercase" data-url="{{ route('engineer_skill.list') }}"
	                        	data-csrf="{{ csrf_token() }}" data-page="10">
	                            <thead class="text-uppercase">
		                            <tr>
		                                <th data-col="DT_RowIndex">SL</th>
		                                <th data-col="service_skill_name">Service skill name</th>
		                                <th data-col="description">Description</th>
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
</div>
@endsection

@section('footer-script')
<script type="text/javascript">
	$(document).ready(function () {

	});
</script>

@endsection
