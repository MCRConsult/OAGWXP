@php
    // $project = $requisition->lines()
    //                 ->has('project')
    //                 ->first()
    //                 ->project;

    // if (!!$project) {
    //     if ($project->project_type == 'Operating Expenses') {
    //         $departmentCode = $project->asap->department_code;
    //     } else {
    //         $departmentCode =  $project->project_type == 'Disbursement'
    //                                 ? $project->program->department_code
    //                                 : $project->departmentResponsiblePerson($project->department_code);
    //     }
    // } else {
    //     $departmentCode = $requisition->user->getDepartment();
    // }

    // $isPreparer = $requisition->withdraw == 'Withdraw';
    // if ($isPreparer) {
    //     $departmentCode = $requisition->user->getDepartment();
    // }

@endphp
@include('shared.approves._time_line', [
            'departmentCode' =>  $requisition->getDepartmentCode()
        ])
