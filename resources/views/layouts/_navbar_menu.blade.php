<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
        <div class="">
            <div class="main-menu-header">
                <div class="user-details">
                    <span>{{  auth()->guard(session()->get('guard'))->check()
                        ? auth()->guard(session()->get('guard'))->user()->firstname . ' ' . auth()->guard(session()->get('guard'))->user()->lastname
                        : 'guest' }}</span>
                    <span id="more-details">{{auth()->guard(session()->get('guard'))->user()->role}}<i class="ti-angle-down"></i></span>
                </div>
            </div>

            <div class="main-menu-content">
                <ul>
                    <li class="more-details">
                        <a href="{{ route('user-profile.index') }}"><i class="ti-user"></i>View Profile</a>
                        <a href="{{ route('user-profile.changePasswordForm') }}"><i class="ti-settings"></i>Settings</a>
                        <a href="/logout"><i class="ti-layout-sidebar-left"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation">School</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="pcoded-hasmenu">
                <a href="#">
                    <span class="pcoded-micon"><i class="ti-file"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">ASAP</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li>
                        <a href="{{ route('asap.index', ['undergraduate']) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.default"> undergraduate </span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
                <ul class="pcoded-submenu">
                    <li>
                        <a href="{{ route('asap.index', ['graduate']) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.default"> graduate </span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
                <ul class="pcoded-submenu">
                    <li>
                        <a href="{{ route('asap.index', ['administrative']) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.default"> administrative </span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="pcoded-hasmenu">
                <a href="#">
                    <span class="pcoded-micon"><i class="ti-file"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">Project</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li>
                        <a href="{{ route('project.index', ['undergraduate']) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.default"> undergraduate </span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
                <ul class="pcoded-submenu">
                    <li>
                        <a href="{{ route('project.index', ['graduate']) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.default"> graduate </span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
                <ul class="pcoded-submenu">
                    <li>
                        <a href="{{ route('project.index', ['administrative']) }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.default"> administrative </span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a href="{{-- {{ route("school.asap.index") }} --}}">
                    <span class="pcoded-micon"><i class="ti-file"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">นอกเหนือ</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route("school.strategies.index") }}">
                    <span class="pcoded-micon"><i class="ti-file"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">Strategies</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.other">E-expenses</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="pcoded-hasmenu">
                <a href="#">
                    <span class="pcoded-micon"><i class="ti-file"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">E-expenses</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li>
                        <a href="{{ route('request-form.index') }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.default"> เบิกทั่วไป </span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
                <ul class="pcoded-submenu">
                    <li>
                        <a href="{{ route('request-form.index') }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.default"> เบิกล่วงหน้า</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.other">University</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="">
                <a href="{{ route('university.strategies.index') }}">
                    <span class="pcoded-micon"><i class="ti-medall"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">
                        Strategies
                    </span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.other">Receipt</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="">
                <a href="{{ route('misc-receipt.create') }}">
                    <span class="pcoded-micon"><i class="ti-money"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">
                        Receipt
                    </span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.other">Setup</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="">
                <a href="{{route('user.index')}}">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">User</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('university.strategies.index') }}">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">School</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('setting.campus.create') }}">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">Campus</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('setting.research.create') }}">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">Research</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('setting.program.create') }}">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">School/Program</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('setting.misc-receipt-groups.create') }}">
                    <span class="pcoded-micon"><i class="ti-user"></i><b>N</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.navigate.main">Receipt</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </div>
</nav>
