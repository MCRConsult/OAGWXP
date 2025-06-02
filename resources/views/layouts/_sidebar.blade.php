<div class="sidebar" style="margin-top: 25px;">
    <nav class="sidebar-nav">
        <ul class="nav mb-5">
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    บันทึกขอเบิก
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-title nav-link" href="{{ route('expense.requisition.index') }}">
                            <i class="nav-icon fa fa-book fa-lg"></i> เอกสารส่งเบิก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-title nav-link" href="{{ route('expense.invoice.index') }}">
                            <i class="nav-icon fa fa-laptop fa-lg"></i> เอกสารขอเบิก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-title nav-link" href="{{ route('expense.invoice.interface-log') }}">
                            <i class="nav-icon fa fa-history fa-lg"></i> ประวัติการอินเตอร์เฟซ
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    รายงาน
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-title nav-link" href="{{ route('expense.report.index') }}">
                            <i class="nav-icon cui-calculator"></i> ทะเบียนคุมหลักฐานขอเบิก
                        </a>
                    </li>
                </ul>
            </li>
            {{-- <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    การตั้งค่า
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-title nav-link" href="{{ route('expense.report.index') }}">
                            <i class="nav-icon fa fa-users "></i> ผู้ใช้งาน
                        </a>
                    </li>
                </ul>
            </li> --}}
        </ul>
    </nav>
</div>