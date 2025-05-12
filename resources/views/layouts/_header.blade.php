<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" data-toggle="sidebar-show" type="button">
        <span class="navbar-toggler-icon">
        </span>
    </button>
    <a class="navbar-brand" href="/home">
        <img alt="Abac Logo" src="/images/oag-logo.png" style="width: 130px; padding: 5px;"></img>
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" data-toggle="sidebar-lg-show" type="button">
        <span class="navbar-toggler-icon">
        </span>
    </button>
    <ul class="nav navbar-nav ml-auto">
        {{-- @if (isWebTest())
            <li class="nav-item mr-4">
                <span class="navbar-text tw-text-white">
                    <h4><i class="fas fa-database"></i>: {{ currentDB() }}</h4>
                </span>
            </li>
        @endif
        <li class="nav-item mr-2">
            <span class="navbar-text tw-text-white">
                {{ session('operating_unit_name') }}, INV: {{ userOrganization() ? userOrganization()->organization_name : '' }}
                <a class="tw-text-white tw-underline" href="{{ url('select-organization') }}">
                    change
                </a>
            </span>
        </li>
        <li class="nav-item px-3 dropdown">
            <a aria-expanded="true" aria-haspopup="true" class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
                {{ auth()->user()->firstname . ' ' . auth()->guard(session()->get('guard'))->user()->lastname }}
            </a>
            <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('user-profile.index') }}">
                    <i class="ti-user">
                    </i>
                    View Profile
                </a>
                <a class="dropdown-item" href="{{ route('user-profile.changePasswordForm') }}">
                    <i class="ti-settings">
                    </i>
                    Change Password
                </a>
                <a class="dropdown-item" href="/logout">
                    <i class="ti-layout-sidebar-left">
                    </i>
                    Logout
                </a>
            </div>
        </li>
        <li class="c-header-nav-item dropdown d-md-down-none mx-2">
            <a aria-expanded="false" aria-haspopup="true" class="c-header-nav-link" data-toggle="dropdown" href="#" title="Notification">
                <i class="far fa-bell text-secondary mt-2 mb-0"></i><span class="badge badge-pill badge-info mb-2">{{ count(notificationApprovals()) }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg pt-0" style="width: 400px;">
                <div class="dropdown-header bg-light">
                    <strong>
                        Please Submit ASAP
                    </strong>
                </div>
                @forelse (notificationApprovals()->take(6) as $approvalHeader)
                    <a class="dropdown-item" href="{{ $approvalHeader->approvableUrl }}">
                        <div class="message">
                            <div class="py-3 mfe-3 float-left">
                                <div class="c-avatar">
                                </div>
                            </div>
                            <div>
                                <small class="text-muted">
                                    Submited By : {{ $approvalHeader->submittedBy->full_name }}
                                </small>
                                <small class="text-muted float-right mt-1">
                                    <span class="badge {{  $approvalHeader->approvable->badgeClass() }}">{{ $approvalHeader->status == 'in process' ? 'Wating Submit' : $approvalHeader->status}}</span>

                                </small>
                            </div>
                            <div class="text-truncate font-weight-bold">
                                {{ $approvalHeader->approvable->name }} - {{ $approvalHeader->approvable->year }}
                            </div>
                            <div class="text-muted text-truncate">
                               {{ $approvalHeader->departmentName }}
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-center">Not Found</p>
                @endforelse

                <a class="c-header-nav-item dropdown d-md-down-none mx-2" href="{{ route('approve.notification') }}">
                    <strong>
                        View all messages
                    </strong>
                </a>
            </div>
        </li>
        <li class="nav-item px-3 dropdown">
            <a aria-expanded="true" class="nav-link " data-toggle="dropdown" href="#" role="button" >
                <a href="{{ route('approve.history') }}" title="History">
                    <i class="fas fa-history text-secondary">
                    </i>
                </a>
            </a>
            <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
            </div>
        </li>

        <li class="nav-item px-3">
            <a class="nav-link" href="{{ route('user-profile.index') }}">
                <i class="ti-user">
                </i>
                Profile
            </a>
        </li>
        <li class="nav-item px-3">
            <a class="nav-link" href="/logout">
                <i class="ti-layout-sidebar-left">
                </i>
                Logout
            </a>
        </li> --}}
    </ul>
</header>
