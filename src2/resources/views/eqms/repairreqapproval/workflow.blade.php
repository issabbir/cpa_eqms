<div class="col-md-12 text-center center-block">
    <ul id="progressbar" class="text-center">
     
        @foreach($workflows as $workflow)
            <li class="{{$workflow->current_yn == 'Y' ? 'active' : ''}} @if($workflow->approval_status_id == 1) done @elseif($workflow->approval_status_id == 3) reject @else not_final  @endif" id="step{{$i}}">
                <div class="d-none d-md-block">
                    @if(!empty($workflow->emp_name))
                        {{$workflow->emp_name}}
                        @if(!empty($workflow->emp_code))
                            {{' ('.$workflow->emp_code.')'}}
                        @endif
                    @endif
                    {{-- {{isset($workflow->employee) ? $workflow->employee->emp_name . ' ('.$workflow->employee->emp_code.')' : ''}} --}}
                    <br/>
                    {{$workflow->approve_date? date('d-m-Y', strtotime($workflow->approve_date)): ''}}<br/>
{{--                    {{$workflow->status->status}}--}}
                    @if($workflow->current_yn == 'Y')
                        
                        Pending
                    @elseif($workflow->current_yn == 'N' && $workflow->approval_status_id == 2)
                        
                        @if($workflow->current_yn == 'N' && $workflow->back_yn == 'Y')
                            Backwarded
                        @else
                            Not Received
                        @endif
                    @elseif($workflow->approval_status_id == 1)
                       Approved 
                       @if($workflow->approve_reject_notes)
                       <a data-toggle="modal" data-target="#myModal{{$workflow->approval_info_id}}" href="javascript:void(0)">
                        View
                        </a>
                        @endif
                        <br/>
                        @php
                            $empSig =  \App\Helpers\HelperClass::get_signture($workflow->recipient_emp_id);
                        @endphp
                      
                        {{-- @dd($signature); --}}
                        @if(isset($empSig->signature))
                            {{-- <img style="width: 100px; height: 50px;text-align: center;" src="{{$empSig->signature}}" title="{{isset($workflow->employee) ? $workflow->employee->emp_name . ' ('.$workflow->employee->emp_code.')' : ''}}" alt="{{isset($workflow->employee) ? $workflow->employee->emp_name . ' ('.$workflow->employee->emp_code.')' : ''}}"> --}}
                         @endif
                    @elseif($workflow->approval_status_id == 3)
                       Rejected <a data-toggle="modal" data-target="#myModal{{$workflow->approval_info_id}}" href="javascript:void(0)">View</a><br/>
                       @php
                            $empSig =  \App\Helpers\HelperClass::get_signture($workflow->recipient_emp_id);
                        @endphp
                      
                        {{-- @dd($signature); --}}
                        @if(isset($empSig->signature))
                            {{-- <img style="width: 100px; height: 50px;text-align: center;" src="{{$empSig->signature}}" title="{{isset($workflow->employee) ? $workflow->employee->emp_name . ' ('.$workflow->employee->emp_code.')' : ''}}" alt="{{isset($workflow->employee) ? $workflow->employee->emp_name . ' ('.$workflow->employee->emp_code.')' : ''}}"> --}}
                         @endif
                        
                    @else

                    @endif
                    @if($workflow->note && $workflow->employee->emp_id == Auth()->user()->employee->emp_id)
                        <a data-toggle="modal" data-target="#myModal{{$workflow->approval_info_id}}{{$workflow->approval_seq_number}}" href="javascript:void(0)">
                            | Backward Note
                        </a>
                        @endif
                    <br/>
                </div>
            </li>
            @php
                $i++;
            @endphp
            <!-- Modal -->
            <div id="myModal{{$workflow->approval_info_id}}" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Note</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>{{$workflow->approve_reject_notes}}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>
            
            <div id="myModal{{$workflow->approval_info_id}}{{$workflow->approval_seq_number}}" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Note</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>{{$workflow->note}}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach

{{--        <li class="{{$data->approval_status_id != 2 ? 'active done' : ''}}" id="step{{$i}}"><div class="d-none d-md-block">END</div></li>--}}
        <li class="@if($workflow->approval_status_id == 1) done @elseif($workflow->approval_status_id == 3) reject @else not_final  @endif" id="step{{$i}}"><div class="d-none d-md-block">END</div></li>
    </ul>
</div>

