<div class="container-fluid" style="background-color: red">
    <div class="row">
        <!-- Left Sidebar start-->
        <div class="side-menu-fixed" id="sidbar">

            @if (auth('web')->check())
                @include('layouts.main-sidebar.admin-main-sidebar')
            @endif

            @if (auth('student')->check())
                @include('layouts.main-sidebar.student-main-sidebar')
            @endif

            @if (auth('teacher')->check())
                @include('layouts.main-sidebar.teacher-main-sidebar')
            @endif

            @if (auth('parent')->check())
                @include('layouts.main-sidebar.parent-main-sidebar')
            @endif

        </div>

        <!-- Left Sidebar End-->

        <!--=================================
