<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" data-toggle="sidebar-show" type="button">
        <span class="navbar-toggler-icon">
        </span>
    </button>
    <a class="navbar-brand" href="/OAGWXP">
        <img alt="OAG Logo" src="/images/oag-logo.png" style="width: 130px; padding: 5px;"></img>
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" data-toggle="sidebar-lg-show" type="button">
        <span class="navbar-toggler-icon">
        </span>
    </button>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item px-2 text-white">
            <strong> ชื่อผู้ใช้งาน : </strong> {{ auth()->user()->name }}: {{ auth()->user()->hrEmployee->full_name }}
        </li>
        <li class="nav-item px-2 text-white">
            <strong> สำนักงาน : </strong> {{ auth()->user()->location->location_code}}
        </li> 
        <li class="nav-item px-2">
            <a class="nav-link text-white" href="/OAGWXP/logout">
                <i class="fa fa-sign-out"></i> <strong> ออกจากระบบ </strong>    
            </a>
        </li>
    </ul>
</header>
