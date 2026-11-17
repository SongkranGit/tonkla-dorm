<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{session('current_branch_name') != null ? session('current_branch_name') : ""}}</title>

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/admin-lte.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/custom.css') }}" rel="stylesheet">

    @yield('style')

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" id="app">
@php
    $branch_hotel = Config::get('constants.branches.hotel');
    $branch_tonthong = Config::get('constants.branches.boutique_tonthong');
    $branch_id = session('current_branch_id');
@endphp
    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="{{url('')}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>{{mb_substr(session('current_branch_name') != null ? session('current_branch_name') : "",0,1)}}</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">{{session('current_branch_name') != null ? session('current_branch_name') : ""}}</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    {{--                    @if( Auth::user()->hasRole('owner'))--}}
                    {{--                        <li><a href="{{url("/admin/permission")}}"><i class="glyphicon glyphicon-check"></i> &nbsp; Approve&nbsp;</a></li>--}}
                    {{--                    @endif--}}
                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner'))
                        <li><a href="{{url("/allbranch")}}"><i class="glyphicon glyphicon-random"></i> &nbsp; {{__('shared.menu_select_branch')}}</a></li>
                @endif
                <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="{{url('/')}}" class="dropdown-toggle">
                            <!-- The user image in the navbar-->
                            <img src="https://placehold.it/160x160/222d32/ffffff/&text={{ mb_substr(strtoupper(Auth::user()->username), 0, 1) }}"
                                 class="user-image" alt="">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{Auth::user()->username}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('logout')}}">{{__('shared.button_logout')}}</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="https://placehold.it/160x160/3c8dbc/ffffff/&text={{ mb_substr(strtoupper(Auth::user()->username), 0, 1) }}" class="img-circle"
                         alt="">
                </div>
                <div class="pull-left info">
                    <p>{{Auth::user()->username}}</p>
                    <!-- Status -->
                    <a href="{{url('')}}"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            @if(\App\Helpers\PermissionHelper::shouldShowMenu())
                <ul class="sidebar-menu">
                    {{--  Room  --}}
                    <li class="header"><h5>{{__('shared.menu_room')}}</h5></li>
                    <li class="{{ Request::segment(1) == 'room' ? 'active' : '' }}">
                        <a href="{{url('/room')}}"><i class="fa fa-bed"></i> <span>{{__('shared.room_management')}}</span></a>
                    </li>

                    {{--  Equipement  --}}
{{--                    <li class="header"><h5>{{__('shared.menu_equipment')}}</h5></li>--}}
{{--                    <li class="{{ Request::segment(1) == 'equipment' ? 'active' : '' }}">--}}
{{--                        <a href="{{url('equipment')}}"><i class="fa fa-wrench"></i><span>{{__('shared.equipment_management')}}</span></a>--}}
{{--                    </li>--}}

                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('owner'))
                        {{--  Approval Privilege   --}}
                        @if(Auth::user()->hasRole('owner'))
                            <li class="header"><h5>{{__('shared.menu_permission')}}</h5></li>
                            <ul class="sidebar-menu">
                                <li class="{{ Request::segment(2) == 'privilege' ? 'active' : '' }}"><a href="{{url('admin/privilege')}}"><i
                                                class="glyphicon glyphicon-pencil"></i> <span>ให้สิทธิการอนุมัติ</span></a></li>
                                <li class="{{ Request::segment(2) == 'approval' ? 'active' : '' }}"><a href="{{url('admin/approval')}}"><i
                                                class="glyphicon glyphicon-check"></i> <span>อนุมัติเช็คอิน-เช็คเอ้าท์</span></a></li>
                            </ul>
                        @endif
                        @if(\App\Helpers\PermissionHelper::shouldShowApprovalMenu())
                            <li class="header"><h5>{{__('shared.menu_permission')}}</h5></li>
                            <ul class="sidebar-menu">
                                <li class="{{ Request::segment(2) == 'approval' ? 'active' : '' }}"><a href="{{url('admin/approval')}}"><i
                                                class="glyphicon glyphicon-check"></i> <span>อนุมัติเช็คอิน-เช็คเอ้าท์</span></a></li>
                            </ul>
                        @endif
                        {{--  Monthly Report   --}}
                        @if(Auth::user()->hasRole('owner'))
                            <li class="header"><h5>{{__('shared.menu_report')}}</h5></li>
                            <li class="{{ Request::segment(3) == 'monthlyreport' ? 'active' : '' }}">
                                <a href="{{url('admin/reports/monthlyreport')}}"><i class="fa fa-line-chart"></i>
                                    <span>{{__('shared.profit_report')}}</span></a>
                            </li>
                        @endif
                        {{--  Settings   --}}
                        @if(Auth::user()->hasRole('owner'))
                            <li class="header"><h5>{{__('shared.menu_settings')}}</h5></li>
                            <li class="{{ Request::segment(3) == 'electric' ? 'active' : '' }}">
                                <a href="{{url('admin/settings/electric')}}"><i class="fa fa-bolt"></i> <span>{{__('shared.electric_rate')}}</span></a>
                            </li>
                            <li class="{{ Request::segment(3) == 'water' ? 'active' : '' }}">
                                <a href="{{url('admin/settings/water')}}"><i class="fa fa-tint"></i> <span>{{__('shared.water_rate')}}</span></a>
                            </li>
                            <li class="{{ Request::segment(3) == 'extra' ? 'active' : '' }}">
                                <a href="{{url('admin/settings/extra')}}"><i class="fa fa-cube"></i> <span>{{__('shared.extra_rate')}}</span></a>
                            </li>
                            <li class="treeview {{ Request::segment(3) == 'vat' || Request::segment(3) == 'vatspecial' ? 'active' : '' || Request::segment(3) == 'vatspecialproperty' ? 'active' : '' }}">
                                <a href="#"><i class="fa fa-dollar"></i> <span>{{__('shared.vat_rate')}}</span>
                                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{ Request::segment(3) == 'vat' ? 'active' : '' }}">
                                        <a href="{{url('admin/settings/vat')}}"><i
                                                    class="fa {{ Request::segment(3) == 'vat' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                            <span>{{__('shared.vat_rate_daily')}}</span>
                                        </a>
                                    </li>
                                    <li class="{{ Request::segment(3) == 'vatspecial' ? 'active' : '' }}">
                                        <a href="{{url('admin/settings/vatspecial')}}"><i
                                                    class="fa {{ Request::segment(3) == 'vatspecial' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                            <span>{{__('shared.vat_rate_monthly')}}</span>
                                        </a>
                                    </li>
                                    <li class="{{ Request::segment(3) == 'vatspecialproperty' ? 'active' : '' }}">
                                        <a href="{{url('admin/settings/vatspecialproperty')}}">
                                            <i class="fa {{ Request::segment(3) == 'vatspecialproperty' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                            <span>{{__('shared.vat_rate_special_property')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="treeview {{ Request::segment(3) == 'roomratemonthlyfan2'
                                    || Request::segment(3) == 'roomratemonthlyair2'
                                    || Request::segment(3) == 'roomratedaily'
                                    || Request::segment(3) == 'roomratedaily2'
                                    || Request::segment(3) == 'roomratedaily3'
                                    || Request::segment(3) == 'roomratemonthlyfan' ? 'active' : ''
                                    || Request::segment(3) == 'roomratemonthlyair' ? 'active' : ''
                                    || Request::segment(3) == 'roomratemonthlyhouse' ? 'active' : '' }}">
                                <a href="#"><i class="fa fa-bars"></i> <span>{{__('shared.room_rate')}}</span><span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @if($branch_id == $branch_hotel)
                                        <li class="{{ Request::segment(3) == 'roomratedaily' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratedaily')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratedaily' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>ค่าห้อง (วัน) แบบที่ 1</span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::segment(3) == 'roomratedaily2' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratedaily2')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratedaily2' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>ค่าห้อง (วัน) แบบที่ 2</span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::segment(3) == 'roomratedaily3' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratedaily3')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratedaily3' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>ค่าห้อง (วัน) แบบที่ 3</span>
                                            </a>
                                        </li>
                                    @elseif($branch_id == $branch_tonthong)
                                        <li class="{{ Request::segment(3) == 'roomratedaily' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratedaily')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratedaily' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>{{__('shared.room_daily_rate')}}</span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::segment(3) == 'roomratemonthlyfan' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratemonthlyfan')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratemonthlyfan' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>{{__('shared.room_monthly_rate_fan')}}</span></a>
                                        </li>
                                        <li class="{{ Request::segment(3) == 'roomratemonthlyair' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratemonthlyair')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratemonthlyair' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>{{__('shared.room_monthly_rate_air')}}</span></a>
                                        </li>
                                    @else
                                        <li class="{{ Request::segment(3) == 'roomratedaily' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratedaily')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratedaily' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>{{__('shared.room_daily_rate')}}</span>
                                            </a>
                                        </li>
                                        <li class="{{ Request::segment(3) == 'roomratemonthlyfan' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratemonthlyfan')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratemonthlyfan' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>{{__('shared.room_monthly_rate_fan')}}</span></a>
                                        </li>
                                        <li class="{{ Request::segment(3) == 'roomratemonthlyfan2' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratemonthlyfan2')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratemonthlyfan2' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>{{__('shared.room_monthly_rate_fan_2')}}</span></a>
                                        </li>
                                        <li class="{{ Request::segment(3) == 'roomratemonthlyair' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratemonthlyair')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratemonthlyair' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>{{__('shared.room_monthly_rate_air')}}</span></a>
                                        </li>
                                        <li class="{{ Request::segment(3) == 'roomratemonthlyair2' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratemonthlyair2')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratemonthlyair2' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>{{__('shared.room_monthly_rate_air_2')}}</span></a>
                                        </li>
                                        <li class="{{ Request::segment(3) == 'roomratemonthlyhouse' ? 'active' : '' }}">
                                            <a href="{{url('admin/settings/roomratemonthlyhouse')}}">
                                                <i class="fa {{ Request::segment(3) == 'roomratemonthlyhouse' ? 'fa-circle' : 'fa-circle-o' }}"></i>
                                                <span>{{__('shared.room_monthly_rate_house')}}</span></a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                    @endif
                </ul>
        @endif
        <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

@yield('content')

<!-- Main Footer -->
    <footer class="main-footer hide-print">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            {{--  Anything you want  --}}
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; {{\Carbon\Carbon::now()->year}}.</strong> All rights reserved.
    </footer>

</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/admin-lte.min.js') }}"></script>
<script src="{{ mix('js/libraries/library.js') }}"></script>
<script src="{{ mix('js/custom.min.js') }}"></script>
<script src="{{ mix('js/angular/app.js') }}"></script>
@yield('script')

</body>
</html>
