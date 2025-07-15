<div class="sidebar" style="margin-top: 25px;">
    <nav class="sidebar-nav">
        <ul class="nav"> {{-- mb-5 --}}
            @if (Gate::allows('requisition_view') || Gate::allows('requisition_enter')
                || Gate::allows('invoice_view') || Gate::allows('invoice_enter')
                || Gate::allows('history_resubmit'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        บันทึกขอเบิก
                    </a>
                    <ul class="nav-dropdown-items">
                        @if (Gate::allows('requisition_view') || Gate::allows('requisition_enter'))
                            <li class="nav-item">
                                <a class="nav-title nav-link" href="{{ route('expense.requisition.index') }}">
                                    <i class="nav-icon fa fa-shopping-basket fa-lg"></i> เอกสารส่งเบิก
                                </a>
                            </li>
                        @endif
                        @if (Gate::allows('invoice_view') || Gate::allows('invoice_enter'))
                            <li class="nav-item">
                                <a class="nav-title nav-link" href="{{ route('expense.invoice.index') }}">
                                    <i class="nav-icon fa fa-credit-card fa-lg"></i> เอกสารขอเบิก
                                </a>
                            </li>
                        @endif
                        @if (Gate::allows('history_resubmit'))
                            <li class="nav-item">
                                <a class="nav-title nav-link" href="{{ route('expense.interface-log') }}">
                                    <i class="nav-icon fa fa-history fa-lg"></i> ประวัติการอินเทอร์เฟซ
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (Gate::allows('report_enter'))
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
            @endif
            @if (Gate::allows('setting_enter'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        การตั้งค่า
                    </a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-title nav-link" href="{{ route('expense.settings.user.index') }}">
                                <i class="nav-icon fa fa-users "></i> ผู้ใช้งาน
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-title nav-link" href="{{ route('expense.settings.permission.index') }}">
                                <i class="nav-icon fa fa-user-secret "></i> สิทธิ์เข้าถึงการใช้งาน
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
</div>