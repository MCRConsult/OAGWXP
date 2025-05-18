<!-- Breadcrumb-->
<ol class="{{ session('db_name') != 'PROD'? 'breadcrumb mb-0': 'breadcrumb mb-3' }}" style="margin-top: 25px;">
    <li class="breadcrumb-item"><strong><a href="/"> หน้าจอหลัก </a></strong></li>
    @yield('breadcrumb')
</ol>
