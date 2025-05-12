@forelse ($approvable->getUsersFromRole($departmentCode) as $user)
    <div><i class="fa fa-user tw-text-grey-dark mr-1"></i> {{ $user->full_name }} {{-- {{ $user->getDepartment() }} --}}</div>
@empty
    <div class="alert alert-danger p-1 pl-2 mt-2">No users {{ $departmentCode }} {{  auth()->user()->getDepartment() }}</div>
@endforelse


