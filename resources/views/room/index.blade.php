@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{__('shared.menu_room')}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</a></li>
        <li class="active">{{__('shared.room_management')}}</li>
      </ol>
    </section>
  @php
      $branch_hotel = Config::get('constants.branches.hotel');
      $current_branch_id = session('current_branch_id');
  @endphp
    <!-- Main content -->
    <section class="content">
        <!-- Your Page Content Here -->
        <div class="box box-info"> 
        
            <div class="box-header with-border">
                <h3 class="box-title">{{__('shared.room_management')}}</h3>
            </div>

            <div class="box-body"> 
                <div class="row">
                        <div class="col-xs-12"> 
                            <div class="box-body table-responsive no-padding">
                            <table class="table table-hover room-table">
                                <tbody>

                                    <tr>
                                        <th>{{__('shared.room_column_room_number')}}</th>
                                        <th>{{__('shared.room_column_check_in')}}</th>
                                        <th>{{__('shared.room_column_check_out')}}</th>
                                        <th class="text-center">{{__('shared.room_column_status')}}</th>
                                    </tr>
                                    @if(sizeof($rooms) > 0)
                                        @foreach($rooms as $key => $room)
                                            <tr>
                                                <td><a href="{{url('/room/'.$room->id.'/edit')}}">{{$room->room_no}}</a></td>
                                                <td><a href="{{url('/room/'.$room->id.'/edit')}}">{{$room->getCurrentCheckedInRoomStayHistroy() != null ? $room->getCurrentCheckedInRoomStayHistroy()->check_in : ''}}</a></td>
                                                <td><a href="{{url('/room/'.$room->id.'/edit')}}">{{$room->getCurrentCheckedInRoomStayHistroy() != null ? $room->getCurrentCheckedInRoomStayHistroy()->check_out: ''}}</a></td>
                                                <td class="text-center">
                                                    <a href="{{url('/room/'.$room->id.'/edit')}}">
                                                    		@if($room->status == 'daily')
                                                                <span class="label label-danger">
                                                                  @if($current_branch_id == $branch_hotel)
                                                                        ห้องรายวัน แบบที่ 1 ({{$roomRateDaily}})
                                                                      @else
                                                                        {{__('shared.room_status_daily')}}
                                                                    @endif
                                                                </span>
                                                            @elseif($room->status == 'daily_2')
                                                                <span class="label label-danger">ห้องรายวัน แบบที่ 2 ({{$roomRateDaily2}})</span>
                                                            @elseif($room->status == 'daily_3')
                                                                <span class="label label-danger">ห้องรายวัน แบบที่ 3 ({{$roomRateDaily3}})</span>
                                                            @elseif($room->status == 'monthly_air')
                                                                <span class="label label-danger">{{__('shared.room_status_monthly_air')}}</span>
                                                            @elseif($room->status == 'monthly_air_2')
                                                                <span class="label label-danger">{{__('shared.room_status_monthly_air_2')}}</span>
                                                            @elseif($room->status == 'monthly_fan')
                                                                <span class="label label-danger">{{__('shared.room_status_monthly_fan')}}</span>
                                                            @elseif($room->status == 'monthly_fan_2')
                                                                <span class="label label-danger">{{__('shared.room_status_monthly_fan_2')}}</span>
                                                            @elseif($room->status == 'monthly_house')
                                                                <span class="label label-danger">{{__('shared.room_status_monthly_house')}}</span>
                                                            @else 
                                                                <span class="label label-success">{{__('shared.room_status_available')}}</span>
                                                            @endif
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            </div>
                            <!-- /.box-body --> 
                        </div>
                    </div>
            </div>
            
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection