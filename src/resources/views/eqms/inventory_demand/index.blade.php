@extends('layouts.default')

@section('title')
    :: Inventory Demand
@endsection

@section('header-style')
    <!--Load custom style link or css-->

@endsection
@section('content')

    <div class="content-body">
        <section id="form-repeater-wrapper">
            <!-- form default repeater -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if(Session::has('message'))
                            <div
                                class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                                role="alert">
                                {{ Session::get('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if( !empty($edit_data['is_demand_yn']) && $edit_data['is_demand_yn']== 'Y' )

                            <div
                                class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
                                role="alert">

                                Inventory Demand is already on process.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="card-content">
                            <div class="card-body">
                                <form
                                    action="{{!empty($edit_data['inventory_demand_id']) ? route('inventory-demand-update', [ 'id'=> $edit_data['inventory_demand_id']   ]) :  route('inventory-demand-post')}}"
                                    method="POST">
                                    @csrf

                                    <h5 class="card-title">Inventory Demand</h5>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-4 mt-1">
                                            <label class="required">Requisition Date:</label>
                                            <div class="input-group date" id="datetimepicker"
                                                 data-target-input="nearest">
                                                <input type="text"
                                                       value="{{!empty($edit_data['inventory_demand_id']) ? date('d-m-Y', strtotime($edit_data['requisition_date'])) : '' }}"
                                                       class="form-control datetimepicker-input"
                                                       {{(!empty($edit_data->is_demand_yn) && $edit_data->is_demand_yn == 'Y') ? 'disabled' : ''}}
                                                       data-toggle="datetimepicker" data-target="#datetimepicker"
                                                       id="requisition_date" required
                                                       name="requisition_date"
                                                       autocomplete="off"
                                                />
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-1">
                                            <label class="required">Equipment</label>

                                            <select class="custom-select select2 form-control" required
                                                    name="equipment_id" id="equipment_id"
                                                    @if( $edit_data['is_demand_yn'] == 'Y' || $edit_data['is_demand_yn'] == 'P') disabled @endif

                                            >
                                                <option value="">Select One</option>
                                                @foreach($equipment_dropdown as $value)
                                                    <option data-workshop="{{$value->workshop_name}}"
                                                            data-workshop-id="{{$value->workshop_id}}"
                                                            value="{{$value->equip_id}}"
                                                            @if(!empty($edit_data['equipment_id']) && $value->equip_id ==  $edit_data['equipment_id']) selected @endif
                                                    >
                                                        {{!empty($edit_data['equipment_id']) && $value->equip_id ==  $edit_data['equipment_id']  ?  $edit_data['equipment_no'].' - '. $edit_data['equipment']  : $value->equip_no .' - '.$value->equip_name}}
                                                    </option>

                                                @endforeach

                                            </select>


                                        </div>


                                        <div class="col-md-4 mt-1">
                                            <label class="required">Workshop Name</label>
                                            <input type="text" autocomplete="off"

                                                   name="workshop_name"
                                                   id="workshop_name"
                                                   class="form-control" required readonly
                                                   value="{{!empty($edit_data->inventory_demand_id) ? $edit_data->workshop : ''}}"
                                            >
                                        </div>


                                        <div class="col-md-8 mt-1">
                                            <label for="description">Description</label>
                                            <textarea class="form-control text-align:left" autocomplete="off"
                                                      id="description"
                                                      {{(!empty($edit_data->is_demand_yn) && $edit_data->is_demand_yn == 'Y') ? 'disabled' : ''}}
                                                      placeholder="Description" name="description"
                                                      rows="2">{{!empty($edit_data['inventory_demand_id']) ? $edit_data['description'] : ''}}</textarea>
                                        </div>


                                    </div>


                                    {{----------------------items from inventory-----------------}}
                                    @if($editMode==1)
                                        <div class="row mt-3">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped table-bordered"
                                                           id="table-operator">
                                                        <thead>
                                                        <tr>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="1" class="text-center" width="1%">#
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                Item
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                Item Code
                                                            </th>

                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                Demand QTY
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                APPROVED QTY
                                                            </th>
                                                            <th role="columnheader" scope="col"
                                                                aria-colindex="2" class="text-center" width="20%">
                                                                ISSUED QTY
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="rowgroup" id="comp_body">
                                                        @if(!empty($dData))
                                                            @php
                                                                $count = 1;
                                                            @endphp
                                                            @foreach($dData as $key=>$value)
                                                                <tr role="row">
                                                                    <td aria-colindex="1" role="cell"
                                                                        class="text-center">
                                                                        {{$count++}}
                                                                    </td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->item_name}}</td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{isset($value->item_code) ? $value->item_code : '--'}}</td>

                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->demand_qty}}</td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->approved_qty}}</td>
                                                                    <td aria-colindex="7"
                                                                        role="cell">{{$value->issued_qty}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>

                                                    </table>

                                                </div>
                                            </div>
                                        </div>

                                    @endif
                                    {{---------------------------------------}}


                                    <div class="form-group mt-1">
                                        <div class="col-md-12 pr-0 d-flex justify-content-end">

                                            <div class="form-group">

                                                @if((!empty($edit_data->is_demand_yn) && ($edit_data->is_demand_yn == 'N' ||  $edit_data->is_demand_yn == 'P')) )

                                                    <button type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Update
                                                    </button>

                                                    @php
                                                        $url = App\Entities\Security\Menu::where('menu_id', 42)
                                                                ->where('module_id', 45)
                                                                ->first()
                                                                ->base_url;
                                                    @endphp

                                                    {{--                                                    @if($edit_data['is_demand_yn']== 'N' || $edit_data['is_demand_yn']== 'P' )--}}

                                                    @if($edit_data['is_demand_yn']== 'N')
                                                        <a target="_blank"
                                                           href="{{externalLoginUrl($url, '/create-item-demand?module_id=59&refcode=ID&ref=' . $edit_data->inventory_demand_id.'&equip_id='.$edit_data->equipment_id)}}"
                                                           class="btn btn-primary mr-1 mb-1"> New Demand</a>

                                                    @elseif($edit_data['is_demand_yn']== 'P')
                                                        <a target="_blank"
                                                           href="{{externalLoginUrl($url, '/create-item-demand?module_id=59&refcode=ID&ref=' . $edit_data->inventory_demand_id.'&equip_id='.$edit_data->equipment_id)}}"
                                                           class="btn btn-info mr-1 mb-1"> Update Demand</a>


                                                    @endif

                                                    <a type="reset" href="{{route("inventory-demand-index")}}"
                                                       class="btn btn-light-secondary mb-1"> Back</a>

                                                @elseif( (!empty($edit_data->is_demand_yn) && $edit_data->is_demand_yn == 'Y') )


                                                    <a type="reset" href="{{route("inventory-demand-index")}}"
                                                       class="btn btn-light-secondary mb-1 "> Back</a>

                                                @else
                                                    <button type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Save
                                                    </button>

                                                    <a type="reset" href="{{route("inventory-demand-index")}}"
                                                       class="btn btn-light-secondary mb-1"> Back</a>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </form>
                            </div>
                            @if( (!empty($edit_data->is_demand_yn) && $edit_data->is_demand_yn == 'Y'))
                                <div class="text-center">
                                    <h3><u style="color: #ff4040 ">Item Demand is already on process.</u></h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!--/ form default repeater -->

        </section>
    </div>

    {{--    @if( !empty($edit_data['is_demand_yn']) && ( $edit_data['is_demand_yn']== 'Y' || $edit_data['is_demand_yn']== 'P' ) )--}}
    {{--        --}}{{--        Demanded Items List 2nd--}}
    {{--        @include('eqms.inventory_demand.demadedItemList')--}}
    {{--    @endif--}}


    {{--    @if(   $edit_data['is_demand_yn']=='N' || $edit_data['is_demand_yn']== '' )--}}
    {{--        --}}{{--        Inventory Demand List 1st--}}
    {{--        @include('eqms.inventory_demand.list')--}}
    {{--    @endif--}}

    @if($editMode==0)
        @include('eqms.inventory_demand.demadedItemList')
    @endif

@endsection

@section('footer-script')

    <script type="text/javascript">


        // dont delete inventoryDemandList()
        /*
                function inventoryDemandList() {
{{--var url = '{{route('inventory-demand-datatable')}}';--}}
        var oTable = $('#searchResultTable').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: url,
                'type': 'POST',
                'headers': {
{{--'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
        }
    },
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'equipment', name: 'equipment', searchable: true},
        {data: 'workshop', name: 'workshop', searchable: true},
        {data: 'requisition_date', name: 'requisition_date', searchable: true},

        {data: 'action', name: 'action', searchable: false},
    ]
});
};
*/


        // -----------------New demand list
        function demandedItemList() {

            let url = '{{route('demanded-item-datatable')}}';
            let oTable = $('#newSearchResultTable').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: url,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: function (d) {
                        // d.r_r_mst_id = $('#r_r_mst_id').val();
                        d.inventory_demand_id = '{{$edit_data['inventory_demand_id']}}';
                    }
                },

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},

                    {data: 'demand_no', name: 'demand_no', searchable: true},
                    {data: 'equip_name', name: 'equip_name', searchable: true},
                    {data: 'workshop_name', name: 'workshop_name', searchable: true},

                    {data: 'issued_yn', name: 'issued_yn', searchable: true},
                    {data: 'approval_status', name: 'approval_status', searchable: true},
                    {data: 'requisition_date', name: 'requisition_date', searchable: true},
                    {data: 'approved_date', name: 'approved_date', searchable: true},

                    // date('d-m-Y', strtotime($edit_data['requisition_date']))
                    {data: 'action', name: 'action', searchable: false},
                ]
            });

        };
        // -----------------New demand list ends

        $(document).ready(function () {
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);

            datePicker('#datetimepicker');
            // inventoryDemandList();
            demandedItemList();
        });

        $('#equipment_id').change(function () {
            let workshop_name = $(this).children('option:selected').data('workshop');
            $("#workshop_name").val(workshop_name);
        });
    </script>

@endsection

