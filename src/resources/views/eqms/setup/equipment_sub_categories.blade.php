@extends('layouts.default')

@section('title')
    Equipment Sub Category
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
        <div class="card-body" style="@if(\Request::get('id')) display: block @else display: none @endif" id="equipment_sub_category_form">
          <h4 class="card-title"> {{ $subCategories && isset($subCategories->sub_catagory_no)?'Edit':'Add' }} Equipment Sub Category</h4>
          <form method="POST" action="@if ($subCategories && $subCategories->sub_catagory_no) {{route('sub_category.update', [\Request::get('catagory_no'), 'id' => $subCategories->sub_catagory_no])}} @else {{route('sub_category.store', \Request::get('catagory_no'))}} @endif" id="sub_category_form">
            {{ ($subCategories && isset($subCategories->sub_catagory_no))?method_field('PUT'):'' }}
            @csrf
            <div class="row">
                <input type="hidden"
                       name="sub_category_no"
                       id="sub_category_no"
                       placeholder="Sub Category ID"
                       value="{{ \Request::get('id') }}"
                       class="form-control"
                >
                <input type="hidden"
                       name="catagory_no"
                       id="category_no"
                       placeholder="Category No"
                       value="{{ \Request::get('catagory_no') }}"
                       class="form-control"
                >
              <div class="col-md-4">
                <label for="sub_catagory_id" class="input-required required">Sub Category ID</label>
                <input type="text"
                       name="sub_catagory_id"
                       id="sub_catagory_id"
                       placeholder="Sub Category ID"
                       value="{{ old('sub_catagory_id', ($subCategories)?$subCategories->sub_catagory_id:$gen_cat_id) }}"
                       class="form-control"
                       readonly
                >
                @if($errors->has("sub_catagory_id"))
                    <span class="help-block">{{$errors->first("sub_catagory_id")}}</span>
                @endif
              </div>
              <div class="col-md-4">
                <label for="catagory_name" class="input-required required">Sub Category Name</label>
                <input type="text"
                       required
                       placeholder="Sub Category Name"
                       name="catagory_name"
                       value="{{ old('sub_catagory_name', ($subCategories)?$subCategories->sub_catagory_name:'') }}"
                       id="catagory_name"
                       class="form-control text-uppercase"
                >
                @if($errors->has("catagory_name"))
                  <span class="help-block">{{$errors->first("catagory_name")}}</span>
                @endif
              </div>
              <div class="col-md-4">
                <label for="catagory_name_bn" class="input-required">Sub Category Name Bangla</label>
                <input type="text"
                       name="catagory_name_bn"
                       placeholder="বাংলায় লিখুন.."
                       id="catagory_name_bn"
                       value="{{ old('sub_catagory_name_bn', ($subCategories)?$subCategories->sub_catagory_name_bn:'') }}"
                       class="form-control"
                >
                @if($errors->has("catagory_name_bn"))
                  <span class="help-block">{{$errors->first("sub_catagory_name_bn")}}</span>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="mt-2 d-flex justify-content-end">
                    @if (\Request::get('id'))
                        <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                            <i class="bx bx-sync"></i> Update</button>
                        <a href="{{ route('sub_category.index', ['catagory_no' => \Request::get('catagory_no')]) }}" class="btn btn-sm btn-outline-secondary mb-1" style="font-weight: 900;">
                            <i class="bx bx-arrow-back"></i> Back</a>
                    @else
                        <button type="submit" name="save" class="btn btn btn-dark shadow mr-1 mb-1">
                            <i class="bx bx-save"></i> SAVE  </button>
                        <a onclick="$('#equipment_sub_category_form').hide('slow')" href="{{ route('category.index') }}" class="btn btn btn-outline-dark  mb-1">
                            <i class="bx bx-window-close"></i> Cancel  </a>
                    @endif
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
                  <h4 class="card-title text-uppercase">Equipment Sub Category List</h4>
              </div>
              <div class="col-md-6">
                  <div class="row float-right">
                      <button id="show_form" type="button" onclick="$('#equipment_sub_category_form').toggle('slow')" class="btn btn-secondary mb-1 ml-1 hvr-underline-reveal">
                          <i class="bx bx-plus"></i> Add New</button>
                  </div>
              </div>
          </div>
      </div>
      <div class="card-content">
        <div class="card-body card-dashboard">
          <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover table-striped datatable"
            data-url="{{ route('sub_category.list', \Request::get('catagory_no')) }}"
              data-csrf="{{ csrf_token() }}" data-page="5">
              <thead>
                <tr>
                  <th data-col="DT_RowIndex">SL</th>
                  <th data-col="sub_catagory_id">SUB CATEGORY ID</th>
                  <th data-col="sub_catagory_name">SUB CATEGORY NAME</th>
                  <th data-col="sub_catagory_name_bn">SUB CATEGORY NAME BN</th>
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
    $(document).ready(function(){

    })
  </script>

@endsection
