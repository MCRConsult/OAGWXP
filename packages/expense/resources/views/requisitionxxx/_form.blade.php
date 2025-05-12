@php
    $user = auth()->user();
    // $allowPettyCash = $user->allowToPettyCash();
    // $allowWithdraw = $user->allowToWithdraw();
    // $campus = $requisition->user ? $requisition->user->campus()->flex_value : $user->campus()->flex_value;
    // $department = $requisition->user ? $requisition->user->getDepartment() : $user->getDepartment();
@endphp
{{-- <requisition-create-component class="card-body" --}}
    {{-- :p-department="{{ json_encode($department) }}"
    :departments="{{ json_encode($departments) }}"
    :requisition="{{ json_encode($requisition) }}"
    :p-campus="{{ json_encode($campus) }}"
    :cashier-points="{{ json_encode($cashierPoint) }}"
    :p-campuses="{{ json_encode($campuses) }}"
    :ou="{{ json_encode(session('operating_unit_name')) }}"
    :p-projects="{{ json_encode($projects) }}"
    :employee-number="{{ json_encode($requisition->user
        ? $requisition->user->employee->employee_number
        : $user->employee->employee_number) }}"
    :allow-to-pettycash="{{ json_encode($allowPettyCash) }}"
    :allow-to-withdraw="{{ json_encode($allowWithdraw) }}"
    :old="{{ json_encode(Session::getOldInput()) }}" --}}
{{-- ></requisition-create-component> --}}
