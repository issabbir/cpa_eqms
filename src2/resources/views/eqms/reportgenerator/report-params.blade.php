<div class="col-12">
    <div class="row">
        @if($report)
            @if($report->params)
                @foreach($report->params as $reportParam)
                    @if($reportParam->component == 'equipment_no')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($equipment)
                                    @foreach($equipment as $value)
                                        <option
                                            value="{{$value->equip_id}}">{{$value->equip_no.'-'.$value->equip_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'bath_operator')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($berthoperator)
                                    @foreach($berthoperator as $operator)
                                        <option value="{{$operator->bo_id}}">{{$operator->bo_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'nothiNumber')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($nothiNumber)
                                    @foreach($nothiNumber as $nothi)
                                        <option value="{{$nothi->nothi_no}}">{{$nothi->nothi_no}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'requester')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($requester)
                                    @foreach($requester as $data)
                                        <option value="{{$data->requester_id}}">{{$data->requester}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'estimate_no')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($estimate_no)
                                    @foreach($estimate_no as $data)
                                        <option value="{{$data->estimate_no}}">{{$data->estimate_no}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'request_status')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($requestShift)
                                    @foreach($requestShift as $data)
                                        <option value="{{$data->rs_id}}">{{$data->rs_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'operator')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($operator)
                                    @foreach($operator as $data)
                                        <option
                                            value="{{$data->emp_id}}">{{$data->emp_code.'-'.$data->emp_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'workShopType')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($workShopType)
                                    @foreach($workShopType as $data)
                                        <option
                                            value="{{$data->w_t_id}}">{{$data->w_t_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'service')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($service)
                                    @foreach($service as $data)
                                        <option
                                            value="{{$data->s_m_id}}">{{$data->service_no}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'location')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($location)
                                    @foreach($location as $data)
                                        <option
                                            value="{{$data->location_id}}">{{$data->location}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'procurement_year')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <input name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                   class="form-control"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                        </div>
                    @elseif($reportParam->component == 'equipmentType')
                        <div class="col-md-3">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <select name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                    class="form-control select2"
                                    @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif>
                                <option value="">Select One</option>
                                @if($equipmentType)
                                    @foreach($equipmentType as $data)
                                        <option
                                            value="{{$data->equip_type_id}}">{{$data->equip_type}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @elseif($reportParam->component == 'date_component')
                        <div class="col-md-3">
                            <label for="p_start_date"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <div class="input-group date datePiker" id="p_start_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_start_date"
                                       data-toggle="datetimepicker"
                                       data-target="#p_start_date"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_start_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    @elseif($reportParam->component == 'date_components')
                        <div class="col-md-3">
                            <label for="p_end_date"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <div class="input-group date datePiker" id="p_end_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_end_date"
                                       data-toggle="datetimepicker"
                                       data-target="#p_end_date"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_end_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>
                        </div>

                    @elseif($reportParam->component == 'send_date_component')
                        <div class="col-md-3">
                            <label for="p_send_date"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <div class="input-group date datePiker" id="p_send_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_send_date"
                                       data-toggle="datetimepicker"
                                       data-target="#p_send_date"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_send_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    @elseif($reportParam->component == 'ser_date_component')
                        <div class="col-md-3">
                            <label for="p_service_date"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <div class="input-group date datePiker" id="p_service_date"
                                 data-target-input="nearest">
                                <input type="text" autocomplete="off"
                                       class="form-control datetimepicker-input"
                                       value="" name="p_service_date"
                                       data-toggle="datetimepicker"
                                       data-target="#p_service_date"
                                       @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required
                                       @endif onautocomplete="off"/>
                                <div class="input-group-append" data-target="#p_service_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="bx bxs-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col">
                            <label for="{{$reportParam->param_name}}"
                                   class="@if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif">{{$reportParam->param_label}}</label>
                            <input type="text" name="{{$reportParam->param_name}}" id="{{$reportParam->param_name}}"
                                   class="form-control"
                                   @if($reportParam->requied_yn==\App\Enums\YesNoFlag::YES) required @endif />
                        </div>
                    @endif
                @endforeach
            @endif
            <div class="col-md-3">
                <label for="type">Report Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="pdf">PDF</option>
                    <option value="xlsx">Excel</option>
                </select>
                <input type="hidden" value="{{$report->report_xdo_path}}" name="xdo"/>
                <input type="hidden" value="{{$report->report_id}}" name="rid"/>
                <input type="hidden" value="{{$report->report_name}}" name="filename"/>
            </div>
            <div class="col-md-3 mt-2">
                <button type="submit" class="btn btn btn-dark shadow mr-1 mb-1 btn-secondary">Generate Report</button>
            </div>
        @endif
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".select2").select2();
    });


    $('.datePiker').datetimepicker({
        format: 'DD-MM-YYYY',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            date: 'bx bxs-calendar',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right'
        }
    });

    $("#p_start_date").on("change.datetimepicker", function (e) {
        $('#p_end_date').datetimepicker('minDate', e.date);
    });
    $("#p_end_date").on("change.datetimepicker", function (e) {
        $('#p_start_date').datetimepicker('maxDate', e.date);
    });


</script>
