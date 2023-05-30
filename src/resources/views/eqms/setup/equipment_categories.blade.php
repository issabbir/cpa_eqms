@extends('layouts.default')

@section('title')
    Equipment Category
@endsection

@section('header-style')
<!--Load custom style link or css-->
<style type="text/css">
    .table td:nth-last-child(1), th:nth-last-child(1) {
        text-align: center;
    }

    @media (min-width: 992px){
        .modal-lg, .modal-xl {
            max-width: 75%;

        }
    }

    .error{
      color: red;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body" style="@if(\Request::get('id')) display: block @else display: none @endif" id="equipment_category_form">
                    <h4 class="card-title"> {{ $data && isset($data->catagory_no)?'Edit':'Add' }} Equipment Category</h4>
                    <form class="category_form" method="POST" action="@if ($data && $data->catagory_no) {{route('category.update',['id' => $data->catagory_no])}} @else {{route('category.store')}} @endif">
                        {{ ($data && isset($data->catagory_no))?method_field('PUT'):'' }}
                        {!! csrf_field() !!}
                        @if ($data && $data->catagory_no)
                             <input type="hidden" name="catagory_no" value="{{$data->catagory_no}}">
                        @endif
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <label for="catagory_id" class="input-required required">CATEGORY ID</label>
                                        <input type="text"
                                               readonly required
                                               name="catagory_id"
                                               id="catagory_id"
                                               class="form-control"
                                               value="{{ old('catagory_id', ($gen_cat_id)?$gen_cat_id:'') }}">
                                        @if($errors->has("catagory_id"))
                                            <span class="help-block">{{$errors->first("catagory_id")}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <label for="catagory_name" class="input-required required">Category Name</span></label>
                                        <input type="text"
                                               required
                                               autofocus
                                               name="catagory_name"
                                               id="catagory_name"
                                               value="{{ old('catagory_name', ($data)?$data->catagory_name:'') }}"
                                               class="form-control"
                                        >
                                        @if($errors->has("catagory_name"))
                                          <span class="help-block">{{$errors->first("catagory_name")}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <label for="catagory_name_bn" class="input-required required">Category Name Bangla</span></label>
                                        <input type="text"
                                               name="catagory_name_bn"
                                               id="catagory_name_bn"
                                               value="{{ old('catagory_name_bn', ($data)?$data->catagory_name_bn:'') }}"
                                               class="form-control"
                                        >
                                        @if($errors->has("catagory_name_bn"))
                                          <span class="help-block">{{$errors->first("catagory_name_bn")}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row my-1 ml-auto">
                                    <div class="col-md-12" style="margin-top: 20px">
                                        <div class="d-flex justify-content-end col">
                                            @if (\Request::get('id'))
                                                <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                                                    <i class="bx bx-sync"></i> Update</button>
                                                <a href="{{ route('category.index') }}" class="btn btn-sm btn-outline-secondary mb-1" style="padding-top: 10px; font-weight: 900;">
                                                    <i class="bx bx-arrow-back"></i> Back</a>
                                            @else
                                                <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                                                    <i class="bx bx-save"></i> SAVE  </button>
                                                <button type="button" onclick="$('#equipment_category_form').hide('slow')" class="btn btn btn-outline-dark  mb-1">
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

        <!--List-->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Equipment Category List</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="row float-right">
                            <button id="show_form" type="button" onclick="$('#equipment_category_form').toggle('slow')" class="btn btn-secondary mb-1 ml-1 hvr-underline-reveal">
                                <i class="bx bx-plus"></i> Add New</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table
                        class="table table-sm table-bordered table-hover table-striped datatable"
                        data-url="{{ route('category.data') }}" data-csrf="{{ csrf_token() }}" data-page="10">
                            <thead>
                                <tr>
                                    <th data-col="DT_RowIndex">SL</th>
                                    <th data-col="catagory_id">CATEGORY ID</th>
                                    <th data-col="catagory_name">CATEGORY NAME</th>
                                    <th data-col="catagory_name_bn">CATEGORY NAME BN</th>
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
</div>
@endsection


@section('footer-script')
<!--Load custom script-->
<script>
    $(document).ready(function() {

      // $('.datatable').DataTable({
      //   processing: true,
      //   serverSide: true,
      //   pageLength: 20,
      //   lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
      //   initComplete: function(settings, json) {
      //     $('body').find('.dataTables_scrollBody').css("height", "auto");
      //     $('body').find('.dataTables_scrollBody').css("max-height", "300px");
      //   },
      //   ajax: {
      //     url:'{{ route('category.data')}}',
      //     type:'POST',
      //     'headers': {
      //       'X-CSRF-TOKEN': '{{ csrf_token() }}'
      //     }
      //   },
      //   "columns": [
      //   {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
      //   {"data": "catagory_id"},
      //   {"data": "catagory_name"},
      //   {"data": "catagory_name_bn"},

      //   {data: 'action', name: 'action', orderable: false, searchable: false}
      //   ],
      //   language: {
      //     paginate: {
      //       next: '<i class="bx bx-chevron-right">',
      //       previous: '<i class="bx bx-chevron-left">'
      //     }
      //   }
      // });

        // $('#category_form').on('submit', function (e) {
        //     e.preventDefault();
        //     $.post('/categories/store', $(this).serialize(), function (data) {
        //         console.log(data);
        //     });
        // });

        // $('.sub-category-modal').on('click', function () {
        //     let that = $(this);
        //     //$(this).find('form').reset();
        //     $.get('/setup/sub-categories/' + $(this).data('id'), $(this).serialize(), function (data) {
        //         $("#showsubcat").find('#f_category_no').val(that.data('id'));
        //         $('#showsubcat').modal('show');
        //         $('#showsubcat').find('table tbody').html(data);
        //     });
        // });


        // $('#sub_category_form').on('submit', function (e) {
        //     e.preventDefault();
        //     $.post('sub-categories/'+ $(this).find('#f_category_no').val(), $(this).serialize(), function (data) {
        //         console.log(data);
        //     });
        // });

    });
</script>

@endsection
