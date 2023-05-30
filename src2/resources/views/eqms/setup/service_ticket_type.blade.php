@extends('layouts.default')

@section('title')
    Service Ticket Type
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
            <div class="card" id="service_ticket_type"
                 style="@if(\Request::get('id')) display: block @else display: none @endif">
                <div class="card-header text-uppercase pb-0">
                    <h5 class="card-title text-uppercase">{{ $data && isset($data->ticket_type_no)?'Edit':'Add' }}
                        Service Ticket Type </h5>
                </div>
                <div class="card-body">
                    <form
                        action="@if ($data && $data->ticket_type_no) {{route('ticket_type.update',['id' => $data->ticket_type_no])}} @else {{route('ticket_type.store')}} @endif"
                        method="post">
                        @if ($data && $data->ticket_type_no)
                            @method('PUT')
                            <input type="hidden" name="ticket_type_no" value="{{$data->ticket_type_no}}">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="ticket_type_id" class="form-control-label text-uppercase">ticket
                                            priority Id</label>
                                        <input type="text"
                                               autofocus
                                               readonly
                                               name="ticket_type_id"
                                               value="{{ old('ticket_type_id', ($data)?$data->ticket_type_id:$gen_uniq_id) }}"
                                               oninput="this.value = this.value.toUpperCase()"
                                               id="ticket_type_id"
                                               class="form-control"
                                        >
                                        @if($errors->has("ticket_type_id"))
                                            <span class="help-block">{{$errors->first("ticket_type_id")}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="ticket_type_name" class="form-control-label text-uppercase">ticket
                                            type name</label>
                                        <input type="text"
                                               name="ticket_type_name"
                                               id="ticket_type_name"
                                               value="{{ old('ticket_type_name', ($data)?$data->ticket_type_name:'') }}"
                                               oninput="this.value = this.value.toUpperCase()"
                                               class="form-control">
                                        @if($errors->has("ticket_type_name"))
                                            <span class="help-block">{{$errors->first("ticket_type_name")}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="ticket_type_name_bn" class="form-control-label text-uppercase">ticket
                                            type name bangla</label>
                                        <input type="text"
                                               name="ticket_type_name_bn"
                                               id="ticket_type_name_bn"
                                               value="{{ old('ticket_type_name_bn', ($data)?$data->ticket_type_name_bn:'') }}"
                                               oninput="this.value = this.value.toUpperCase()"
                                               class="form-control">
                                        @if($errors->has("ticket_type_name_bn"))
                                            <span class="help-block">{{$errors->first("ticket_type_name_bn")}}</span>
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
                                                <a href="{{ route('ticket_type.index') }}" class="btn btn-sm btn-outline-secondary mb-1" style="font-weight: 900;">
                                                    <i class="bx bx-arrow-back"></i> Back</a>
                                            @else
                                                <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                                                    <i class="bx bx-save"></i> SAVE  </button>
                                                <button type="button" onclick="$('#service_ticket_type').hide('slow')" class="btn btn btn-outline-dark  mb-1">
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
                    <h4 class="card-title text-uppercase">Service Ticket Type List</h4>
                </div>
                <div class="col-md-6">
                    <div class="row float-right">
                        <button id="show_form" type="button" onclick="$('#service_ticket_type').toggle('slow')"
                                class="btn btn-secondary mb-1 ml-1 hvr-underline-reveal">
                            <i class="bx bx-plus"></i> Add New
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card-content">
                        <div class="table-responsive">
                            <table id=""
                                   class="table table-sm datatable table-bordered mdl-data-table dataTable text-uppercase"
                                   data-url="{{ route('ticket_type.list') }}"
                                   data-csrf="{{ csrf_token() }}" data-page="10">
                                <thead class="text-uppercase">
                                <tr>
                                    <th data-col="DT_RowIndex">SL</th>
                                    <th data-col="ticket_type_id">Ticket Type ID</th>
                                    <th data-col="ticket_type_name">Ticket Type Name</th>
                                    <th data-col="ticket_type_name_bn">Ticket Type Name Bangla</th>
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
