@php

    $teams = \App\Helpers\HelperClass::getWorkFlowTeam();
    $step = \App\Helpers\HelperClass::getWorkFlowStepByObjectId(request()->id);

@endphp
<div class="form-group">
    <div class="input-group" style="justify-content: center;">
        <input type="hidden" class="sequenceData" value="@php echo implode(',',array_keys($step)) @endphp" name="sequenceData">
        <select id='callbacks' class="searchable"  multiple='multiple' name="workflow_team[]" required>
            @if(filled($teams))
                @php
                    $i=1;
                    $selected = [];
                @endphp
                @foreach($step as $emp_code => $emp_name)
                    <option selected
                            value='{{$emp_code}}'>{{$emp_name}}
                    </option>
                    @php
                        $selected[] = $emp_code;
                    @endphp
                @endforeach

                @foreach($teams as $team)

                    @if (!in_array($team->employee->emp_code,$selected))
                        <option
                                value='{{$team->employee->emp_code}}'>{{($team->employee?$team->employee->emp_name:'') . ' ('.$team->employee->emp_code.')'}}</option>
                    @else
                        @continue;
                    @endif
                @endforeach
            @endif
        </select>
    </div>
    <span class="text-danger"></span>
</div>
