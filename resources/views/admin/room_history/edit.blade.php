@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper room-detail" ng-controller="RoomHistoryDetailController">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{__('shared.menu_room')}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</a></li>
        <li><a href="{{url('/room')}}">{{__('shared.room_management')}}</a></li>
        <li><a href="{{url('/room/'.$currentRoomHistory->room->id.'/edit')}}">{{__('shared.room_infomation').' ('.$currentRoomHistory->room->room_no.')'}}</a></li>
        <li><a href="{{url('/admin/roomhistory?RoomId='.$currentRoomHistory->room->id)}}">{{__('shared.room_change_history_management').' ('.$currentRoomHistory->room->room_no.')'}}</a></li>
        <li class="active">{{__('shared.room_change_history').' ('.$currentRoomHistory->room->room_no.')'}}</li>
      </ol>
    </section>
<?php
    $branch_hotel = Config::get('constants.branches.hotel');
    $branch_tonthong = Config::get('constants.branches.boutique_tonthong');
?>

    <!-- Main content -->
    <section class="content">
        <!-- Your Page Content Here -->
        <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{__('shared.room_change_history').' ('.$currentRoomHistory->room->room_no.')'}}</h3>
            <div class="pull-right">
                @if($currentRoomHistory->room->status != 'available' &&  isset($currentRoomHistory->currentCheckedInRoomStayHistroy->id))
                    <div class="form-group language-selection">
                        <label>{{__('shared.language_print')}}</label>
                        <select class="form-control" name="language" ng-model="language" ng-change="onLanguageChange()">
                            <option value='th'>ไทย</option>
                            <option value='en'>อังกฤษ</option>
                        </select>
                    </div>
                    |
                    <a href="javascript:void(0)" class="btn btn-success" ng-click="showPrintRoomModal({{$roomHistory->id}},true);"><i class="fa fa-print" aria-hidden="true"></i> {{__('shared.print')}}</a>
                    @if(session('current_branch_id') != $branch_hotel)
                    |
                    <a href="javascript:void(0)" class="btn btn-success" ng-click="showPrintRoomModal({{$roomHistory->id}},false);"><i class="fa fa-print" aria-hidden="true"></i> {{__('shared.print_check_in')}}</a>
                     @endif
                    |
                    <a href="javascript:void(0)" class="btn btn-success" ng-click="showPrintCheckoutRoomModal({{$roomHistory->id}});"><i class="fa fa-print" aria-hidden="true"></i> {{__('shared.print').' '.__('shared.room_checkout')}}</a>
                @endif
            </div>
        </div>
        <form action="{{url('admin/roomhistory/'.$roomHistory->id)}}" method="post" class="form-horizontal" id="edit-room-form">
            {!!csrf_field()!!}
            {{ method_field('PUT') }}
            <div class="box-body">

                <div class="form-group">
                    <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_number')}}</label>
                    <div class="col-md-9 col-sm-8">
                        <h4 class="show-data">{{$currentRoomHistory->room->room_no}}</h4>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_status')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <select class="form-control" ng-model="status" ng-change="onStatusChange();">
                            <option value="available">{{__('shared.room_status_available')}}</option>
                            @if(session('current_branch_id') == $branch_hotel)
                                <option value="daily">ห้องรายวัน แบบที่ 1 ({{$roomRateDaily}})</option>
                                <option value="daily_2">ห้องรายวัน แบบที่ 2 ({{$roomRateDaily2}})</option>
                                <option value="daily_3">ห้องรายวัน แบบที่ 3 ({{$roomRateDaily3}})</option>
                            @elseif (session('current_branch_id') == $branch_tonthong)
                                <option value="daily">{{__('shared.room_status_daily')}}</option>
                                <option value="monthly_air">{{__('shared.room_status_monthly_air')}}</option>
                                <option value="monthly_fan">{{__('shared.room_status_monthly_fan')}}</option>
                            @else
                                <option value="daily">{{__('shared.room_status_daily')}}</option>
                                <option value="monthly_air">{{__('shared.room_status_monthly_air')}}</option>
                                <option value="monthly_air_2">{{__('shared.room_status_monthly_air_2')}}</option>
                                <option value="monthly_fan">{{__('shared.room_status_monthly_fan')}}</option>
                                <option value="monthly_fan_2">{{__('shared.room_status_monthly_fan_2')}}</option>
                                <option value="monthly_house">{{__('shared.room_status_monthly_house')}}</option>
                            @endif
                        </select>

                        @if ($errors->has('status'))
                            <span class="help-block">
                                <strong>{{ $errors->first('status') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_firstname') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_firstname')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" name="renter_firstname" value="{{ old('renter_firstname') != null  ? old('renter_firstname') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_firstname}}" class="form-control"/>

                        @if ($errors->has('renter_firstname'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_firstname') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_lastname') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_lastname')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" name="renter_lastname" value="{{ old('renter_lastname') != null  ? old('renter_lastname') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_lastname}}" class="form-control"/>

                        @if ($errors->has('renter_lastname'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_lastname') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_id_card_no') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_id_card_no')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" name="renter_id_card_no" value="{{ old('renter_id_card_no') != null  ? old('renter_id_card_no') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_id_card_no}}" class="form-control"/>

                        @if ($errors->has('renter_id_card_no'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_id_card_no') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_tax_payer_no') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_tax_payer_no')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" name="renter_tax_payer_no" value="{{ old('renter_tax_payer_no') != null  ? old('renter_tax_payer_no') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_tax_payer_no}}" class="form-control"/>

                        @if ($errors->has('renter_tax_payer_no'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_tax_payer_no') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_school') ? ' has-error' : '' }}" ng-hide="status=='available' ||  status == 'daily'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_school')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" name="renter_school" value="{{ old('renter_school') != null  ? old('renter_school') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_school}}" class="form-control"/>

                        @if ($errors->has('renter_school'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_school') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_school_class') ? ' has-error' : '' }}" ng-hide="status=='available' ||  status == 'daily'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_school_class')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" name="renter_school_class" value="{{ old('renter_school_class') != null  ? old('renter_school_class') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_school_class}}" class="form-control"/>

                        @if ($errors->has('renter_school_class'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_school_class') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_parent_firstname') ? ' has-error' : '' }}" ng-hide="status=='available' ||  status == 'daily'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_parent_firstname')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" name="renter_parent_firstname" value="{{ old('renter_parent_firstname') != null  ? old('renter_parent_firstname') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_parent_firstname}}" class="form-control"/>

                        @if ($errors->has('renter_parent_firstname'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_parent_firstname') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_parent_lastname') ? ' has-error' : '' }}" ng-hide="status=='available' ||  status == 'daily'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_parent_lastname')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" name="renter_parent_lastname" value="{{ old('renter_parent_lastname') != null  ? old('renter_parent_lastname') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_parent_lastname}}" class="form-control"/>

                        @if ($errors->has('renter_parent_lastname'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_parent_lastname') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_parent_id_card_no') ? ' has-error' : '' }}" ng-hide="status=='available' ||  status == 'daily'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_parent_id_card_no')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" name="renter_parent_id_card_no" value="{{ old('renter_parent_id_card_no') != null  ? old('renter_parent_id_card_no') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_parent_id_card_no}}" class="form-control"/>

                        @if ($errors->has('renter_parent_id_card_no'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_parent_id_card_no') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_parent_telephone') ? ' has-error' : '' }}" ng-hide="status=='available' ||  status == 'daily'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_parent_telephone')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" name="renter_parent_telephone" value="{{ old('renter_parent_telephone') != null  ? old('renter_parent_telephone') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_parent_telephone}}" class="form-control"/>

                        @if ($errors->has('renter_parent_telephone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_parent_telephone') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('renter_current_address') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_renter_current_address')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <textarea name="renter_current_address"  class="form-control"/>{{ old('renter_current_address') != null  ? old('renter_current_address') : $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_current_address}}</textarea>

                        @if ($errors->has('renter_current_address'))
                            <span class="help-block">
                                <strong>{{ $errors->first('renter_current_address') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                {{--  DateTime Picker  --}}
                <div class="form-group{{ $errors->has('check_in') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_check_in')}}</label>
                   <div class="col-md-9 col-sm-8 input-group date"  id='check-in-datetime-group'>
                        <input type="text" name="check_in" value="{{ old('check_in') != null  ? old('check_in') : $currentRoomHistory->currentCheckedInRoomStayHistroy->check_in}}" class="form-control"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        @if ($errors->has('check_in'))
                            <span class="help-block">
                                <strong>{{ $errors->first('check_in') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                {{--  DateTime Picker  --}}
                <div class="form-group{{ $errors->has('check_out') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_check_out')}}</label>
                   <div class="col-md-9 col-sm-8 input-group date"  id='check-out-datetime-group'>
                        <input type="text" name="check_out" value="{{ old('check_out') != null  ? old('check_out') : $currentRoomHistory->currentCheckedInRoomStayHistroy->check_out}}" class="form-control"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        @if ($errors->has('check_out'))
                            <span class="help-block">
                                <strong>{{ $errors->first('check_out') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('deposit') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label text-green">
                      {{ \App\Helpers\BranchHelper::isBranchHotel() ? __('shared.room_deposit_inadvance') : __('shared.room_deposit')}}
                  </label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" id="deposit" value="{{ old('deposit') != null  ? old('deposit') : $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit}}" class="form-control"/>

                        @if ($errors->has('deposit'))
                            <span class="help-block">
                                <strong>{{ $errors->first('deposit') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('deposit_other') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label text-green">{{__('shared.room_deposit_other')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" id="deposit_other" value="{{ old('deposit_other') != null  ? old('deposit_other') : $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other}}" class="form-control"/>

                        @if ($errors->has('deposit_other'))
                            <span class="help-block">
                                <strong>{{ $errors->first('deposit_other') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>


                <div class="form-group" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_is_use_custom_rental_fee')}}</label>
                   <div class="col-md-9 col-sm-8">

                        <div class="checkbox">
                            <label>
                            <input type="checkbox" ng-change="isUseCustomRentalFeeChange();" ng-model="isUseCustomRentalFee">
                                {{--  {{__('shared.room_is_use_custom_rental_fee')}}  --}}
                            </label>
                        </div>

                    </div>
                </div>

                <div class="form-group{{ $errors->has('rental_fee') ? ' has-error' : '' }}" ng-hide="status=='available' || isUseCustomRentalFee">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_rental_fee')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" ng-readonly="true"  id="rental_fee" value="{{ old('rental_fee') != null  ? old('rental_fee') : $currentRoomHistory->currentCheckedInRoomStayHistroy->rental_fee}}" class="form-control"/>

                        @if ($errors->has('rental_fee'))
                            <span class="help-block">
                                <strong>{{ $errors->first('rental_fee') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('custom_rental_fee') ? ' has-error' : '' }}" ng-hide="status=='available' || !isUseCustomRentalFee">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_rental_fee')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" id="custom_rental_fee" value="{{ old('custom_rental_fee') != null  ? old('custom_rental_fee') : $currentRoomHistory->currentCheckedInRoomStayHistroy->custom_rental_fee}}" class="form-control"/>

                        @if ($errors->has('custom_rental_fee'))
                            <span class="help-block">
                                <strong>{{ $errors->first('custom_rental_fee') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label text-green">{{__('shared.room_discount')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" id="discount" value="{{ old('discount') != null  ? old('discount') : $currentRoomHistory->currentCheckedInRoomStayHistroy->discount}}" class="form-control"/>

                        @if ($errors->has('discount'))
                            <span class="help-block">
                                <strong>{{ $errors->first('discount') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>


                <div class="form-group{{ $errors->has('discount_remark') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_discount_remark')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <textarea name="discount_remark"  class="form-control"/>{{ old('discount_remark') != null  ? old('discount_remark') : $currentRoomHistory->currentCheckedInRoomStayHistroy->discount_remark}}</textarea>

                        @if ($errors->has('discount_remark'))
                            <span class="help-block">
                                <strong>{{ $errors->first('discount_remark') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>


                <div class="form-group{{ $errors->has('electric_meter_start') ? ' has-error' : '' }}"
                     ng-hide="status=='available' ||  status == 'daily' ||  status == 'daily_2' ||  status == 'daily_3'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_electric_meter_start')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" id="electric_meter_start" value="{{ old('electric_meter_start') != null  ? old('electric_meter_start') : $currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_start}}" class="form-control"/>

                        @if ($errors->has('electric_meter_start'))
                            <span class="help-block">
                                <strong>{{ $errors->first('electric_meter_start') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('electric_meter_end') ? ' has-error' : '' }}"
                     ng-hide="status=='available' ||  status == 'daily' ||  status == 'daily_2' ||  status == 'daily_3'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_electric_meter_end')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" id="electric_meter_end" value="{{ old('electric_meter_end') != null  ? old('electric_meter_end') : $currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_end}}" class="form-control"/>

                        @if ($errors->has('electric_meter_end'))
                            <span class="help-block">
                                <strong>{{ $errors->first('electric_meter_end') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('electric_meter_unit') ? ' has-error' : '' }}"
                     ng-hide="status=='available' ||  status == 'daily' ||  status == 'daily_2' ||  status == 'daily_3'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_electric_meter_unit')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" ng-readonly="true" id="electric_meter_unit" value="{{ old('electric_meter_unit') != null  ? old('electric_meter_unit') : $currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_unit}}" class="form-control"/>

                        @if ($errors->has('electric_meter_unit'))
                            <span class="help-block">
                                <strong>{{ $errors->first('electric_meter_unit') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('electric_meter_fee') ? ' has-error' : '' }}"
                     ng-hide="status=='available' ||  status == 'daily' ||  status == 'daily_2' ||  status == 'daily_3'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_electric_meter_fee')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" ng-readonly="true" id="electric_meter_fee" value="{{ old('electric_meter_fee') != null  ? old('electric_meter_fee') : $currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_fee}}" class="form-control"/>

                        @if ($errors->has('electric_meter_fee'))
                            <span class="help-block">
                                <strong>{{ $errors->first('electric_meter_fee') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('water_meter_start') ? ' has-error' : '' }}"
                     ng-hide="status=='available' ||  status == 'daily' ||  status == 'daily_2' ||  status == 'daily_3'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_water_meter_start')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text"  id="water_meter_start" value="{{ old('water_meter_start') != null  ? old('water_meter_start') : $currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_start}}" class="form-control"/>

                        @if ($errors->has('water_meter_start'))
                            <span class="help-block">
                                <strong>{{ $errors->first('water_meter_start') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('water_meter_end') ? ' has-error' : '' }}"
                     ng-hide="status=='available' ||  status == 'daily' ||  status == 'daily_2' ||  status == 'daily_3'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_water_meter_end')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" id="water_meter_end" value="{{ old('water_meter_end') != null  ? old('water_meter_end') : $currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_end}}" class="form-control"/>

                        @if ($errors->has('water_meter_end'))
                            <span class="help-block">
                                <strong>{{ $errors->first('water_meter_end') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group{{ $errors->has('water_meter_unit') ? ' has-error' : '' }}"
                     ng-hide="status=='available' ||  status == 'daily' ||  status == 'daily_2' ||  status == 'daily_3'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_water_meter_unit')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" ng-readonly="true" id="water_meter_unit" value="{{ old('water_meter_unit') != null  ? old('water_meter_unit') : $currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_unit}}" class="form-control"/>

                        @if ($errors->has('water_meter_unit'))
                            <span class="help-block">
                                <strong>{{ $errors->first('water_meter_unit') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>
                <div class="form-group{{ $errors->has('water_meter_fee') ? ' has-error' : '' }}"
                     ng-hide="status=='available' ||  status == 'daily' ||  status == 'daily_2' ||  status == 'daily_3'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_water_meter_fee')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" ng-readonly="true" id="water_meter_fee" value="{{ old('water_meter_fee') != null  ? old('water_meter_fee') : $currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_fee}}" class="form-control"/>
                        @if ($errors->has('water_meter_fee'))
                            <span class="help-block">
                                <strong>{{ $errors->first('water_meter_fee') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>
                  <div class="form-group" ng-hide="status=='available'">
                     <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_extra')}}</label>
                    <div class="col-md-9 col-sm-8">
                        <div class="checkbox"
                             ng-repeat="extra in utitlities.extra | orderBy : 'extra_sort_no'">
                            <label>
                                <input type="checkbox" ng-change="extraCheckBoxChange(extra,$index)"
                                       ng-model="selectedExtra[$index].is_use">
                                <%extra.extra_key + ' ( ' + getExtraValue(extra.value) + ' )'%>
                            </label>
                        </div>
                    </div>
                {{--Maintenance Cost--}}
                <div class="form-group{{ $errors->has('room_maintenance_cost') ? ' has-error' : '' }}" ng-hide="status=='available'">
                    <label class="col-md-3 col-sm-4 control-label text-green">{{__('shared.room_maintenance_cost')}}</label>
                    <div class="col-md-9 col-sm-8">
                        <input type="text" id="room_maintenance_cost"
                               value="{{ old('room_maintenance_cost') != null  ? old('room_maintenance_cost') : $currentCheckedInRoomStayHistroy->room_maintenance_cost}}"
                               class="form-control"/>
                        @if ($errors->has('room_maintenance_cost'))
                            <span class="help-block">
                                <strong>{{ $errors->first('room_maintenance_cost') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('total_price') ? ' has-error' : '' }}" ng-hide="status=='available'">
                  <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_total_price')}}</label>
                   <div class="col-md-9 col-sm-8">
                        <input type="text" ng-readonly="true" id="total_price" value="{{ old('total_price') != null  ? old('total_price') : $currentRoomHistory->currentCheckedInRoomStayHistroy->total_price}}" class="form-control"/>

                        @if ($errors->has('total_price'))
                            <span class="help-block">
                                <strong>{{ $errors->first('total_price') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                      <div class="form-group{{ $errors->has('deposit_keycard') ? ' has-error' : '' }}"
                           ng-hide="status=='available'">
                          <label class="col-md-3 col-sm-4 control-label text-green">{{__('shared.room_deposit_keycard')}}</label>
                          <div class="col-md-9 col-sm-8">
                              <input type="text" id="deposit_keycard"
                                     value="{{ old('deposit_keycard') != null  ? old('deposit_keycard') : $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard}}"
                                     class="form-control"/>

                              @if ($errors->has('deposit_keycard'))
                                  <span class="help-block">
                            <strong>{{ $errors->first('deposit_keycard') }}</strong>
                        </span>
                              @endif

                          </div>
                      </div>
                      <div class="form-group" ng-hide="status=='available'">
                          <label class="col-md-3 col-sm-4 control-label">{{__('shared.room_keycard')}}</label>
                          <div class="col-md-9 col-sm-8">

                              {{--                        <button type="button" ng-click="onAddKeyCardBtnClick();" class="btn btn-primary add-key-card-btn">{{__('shared.button_add')}}</button>--}}

                              <table class="table table-hover" ng-show="keycards.length > 0 || isAddNewKeycard">
                                  <tbody>
                                  <tr>
                                      <th width="10%">#</th>
                                      <th>{{__('shared.room_keycard_no')}}</th>
                                      <th>{{__('shared.room_keycard_taken_date')}}</th>
                                      <th class="text-center"></th>
                                  </tr>
                                  <tr ng-repeat="keycard in keycards | orderBy : 'sort_no'">
                                      <td><% $index + 1 %></td>
                                      <td><% keycard.key_card_no %></td>
                                      <td><% keycard.taken_date %></td>
                                      <td class="text-center">
                                          {{--                                    <button type="button" ng-click="onDeleteKeyCardBtnClick($index);" class="btn btn-danger">{{__('shared.button_delete')}}</button>--}}
                                      </td>
                                  </tr>

                                  <tr ng-show="isAddNewKeycard">
                                      <td></td>
                                      <td>
                                          <input type="text" class="form-control" ng-model="newKeycard.key_card_no">
                                      </td>
                                      <td>
                                          <div class="input-group date" id="add-keycard-datetime-group">
                                              <input type="text" class="form-control">
                                              <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                          </div>
                                      </td>
                                      <td class="text-center">
                                          {{--                                    <button type="button" ng-click="onSubmitKeyCardBtnClick();" class="btn btn-primary">{{__('shared.button_add')}}</button>--}}
                                          {{--                                    <button type="button" ng-click="onCancelSubmitKeyCardBtnClick();"  class="btn btn-danger">{{__('shared.button_delete')}}</button>--}}
                                      </td>
                                  </tr>

                                  </tbody>
                              </table>

                          </div>
                      </div>

                  </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <a href="{{url('/admin/roomhistory?RoomId='.$currentRoomHistory->room->id)}}" class="btn btn-default">{{__('shared.button_cancel')}}</a>
{{--                <button type="submit" id="submitBtn" class="btn btn-warning pull-right" disabled="disabled">{{__('shared.button_save')}}</button>--}}
            </div>
            <!-- /.box-footer -->

            {{--  Hidden fields  --}}
            <div class="hidden-form sr-only">
                <input type="text" name="status" value="<%status%>" />
                <input type="text" name="deposit" value="<%deposit%>" />
                <input type="text" name="deposit_keycard" value="<%deposit_keycard%>" />
                <input type="text" name="deposit_other" value="<%deposit_other%>" />
                <input type="text" name="rental_fee" value="<%rental_fee%>" />
                <input type="text" name="is_use_custom_rental_fee" value="<%isUseCustomRentalFee%>" />
                <input type="text" name="custom_rental_fee" value="<%custom_rental_fee%>" />
                <input type="text" name="discount" value="<%discount%>" />

                <input type="text" name="electric_meter_start" value="<%electric_meter_start%>" />
                <input type="text" name="electric_meter_end" value="<%electric_meter_end%>" />
                {{--  <input type="text" name="electric_meter_unit" value="<%electric_meter_unit%>" />  --}}
                {{--  <input type="text" name="electric_meter_fee" value="<%electric_meter_fee%>" />  --}}

                <input type="text" name="water_meter_start" value="<%water_meter_start%>" />
                <input type="text" name="water_meter_end" value="<%water_meter_end%>" />
                <input type="text" name="room_maintenance_cost" value="<%room_maintenance_cost%>"/>
                {{--  <input type="text" name="water_meter_unit" value="<%water_meter_unit%>" />  --}}
                {{--  <input type="text" name="water_meter_fee" value="<%water_meter_fee%>" />  --}}

                {{--  <input type="text" name="extra" value="<%extra%>" />  --}}
                {{--  <input type="text" name="total_price" value="<%total_price%>" />  --}}

                <div class="keycards-group" ng-repeat="keycard in keycards | orderBy : 'sort_no'">
                    <input type="text" name="<%'keycards['+$index+'][id]'%>" value="<%keycard.id%>"/>
                    <input type="text" name="<%'keycards['+$index+'][key_card_no]'%>" value="<%keycard.key_card_no%>"/>
                    <input type="text" name="<%'keycards['+$index+'][taken_date]'%>" value="<%keycard.taken_date%>"/>
                    <input type="text" name="<%'keycards['+$index+'][created_at]'%>" value="<%keycard.created_at%>"/>
                    <input type="text" name="<%'keycards['+$index+'][updated_at]'%>" value="<%keycard.updated_at%>"/>
                    <input type="text" name="<%'keycards['+$index+'][sort_no]'%>" value="<% keycard.sort_no ? keycard.sort_no : $index %>"/>
                </div>

                <div class="delete-keycards-group" ng-repeat="keycard in deletedKeycard">
                    <input type="text" name="<%'delete_keycards['+$index+'][id]'%>" value="<%keycard.id%>"/>
                </div>

                <div class="room-utilities-group" ng-repeat="extra in selectedExtra | filter:{is_use:true}">
                    <input type="text" name="<%'extras['+$index+'][id]'%>" value="<%extra.id%>"/>
                    <input type="text" name="<%'extras['+$index+'][setting_id]'%>" value="<%extra.setting_id%>"/>
                    <input type="text" name="<%'extras['+$index+'][is_use]'%>" value="<%extra.is_use%>"/>
                    <input type="text" name="<%'extras['+$index+'][room_stay_history_id]'%>" value="{{isset($currentRoomHistory->currentCheckedInRoomStayHistroy->id) ? $currentRoomHistory->currentCheckedInRoomStayHistroy->id : ''}}"/>
                </div>

                <div class="delete-room-utilities-group" ng-repeat="extra in deletedSelectedExtra">
                    <input type="text" name="<%'delete_extras['+$index+'][id]'%>" value="<%extra.id%>"/>>
                </div>

            </div>
        </form>
    </section>
    <!-- /.content -->


    {{--  Value  --}}
    <span class="sr-only" id="language-span" data-language="{{Request::get('lang')}}"></span>
    <span class="sr-only" id="currency" data-currency="{{__('shared.bath')}}"></span>
    <span class="sr-only" id="room-utitlities-span" data-room-utitlities="{{ json_encode($currentRoomHistory->currentCheckedInRoomStayHistroy->room_utility_history) }}"></span>
    <span class="sr-only" id="utitlities-span" data-utitlities="{{ json_encode($utilities) }}"></span>
    <span class="sr-only" id="status-span" data-status="{{ old('status') != null  ? old('status') : $room->status}}"></span>
      <span class="sr-only" id="keycards-span"
            data-keycards="{{ old('keycards') != null  ? json_encode(old('keycards')) : json_encode($room->keycards) }}"></span>
     <span class="sr-only" id="is-admin-span" data-is-admin="{{Auth::user()->hasRole('admin')}}"></span>
    <span class="sr-only" id="is-use-custom-rental-fee" data-is-use-custom-rental-fee="{{ old('is_use_custom_rental_fee') != null  ? old('is_use_custom_rental_fee') : $currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee}}"></span>
    <span class="sr-only" id="branch" data-branch="{{\App\Helpers\BranchHelper::getCurrentBranchId()}}"></span>

    @if($currentRoomHistory->room->status != 'available' && isset($currentRoomHistory->currentCheckedInRoomStayHistroy->id))
    <div class="modal modal-overide fade print-prev" id="print-preview-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg text-xs-center" role="document">
            <div id="print-preview-wrapper" class="print-preview-wrapper img-wrapper white-background">
                <iframe id="print-preview-iframe" src="{{url('admin/roomhistory/print/'.$roomHistory->id.'?lang='.Request::get('lang'))}}" width="900" height="700" scrolling="yes"></iframe>
                <iframe id="check-in-print-preview-iframe" src="{{url('admin/roomhistory/printcheckin/'.$roomHistory->id.'?lang='.Request::get('lang'))}}" width="900" height="700" scrolling="yes" style="display:none;"></iframe>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    <div class="modal modal-overide fade print-prev" id="check-out-room-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg text-xs-center" role="document">
            <div id="print-preview-wrapper" class="img-wrapper white-background">
                <div id="check-out-print-preview-wrapper" class="print-preview-wrapper img-wrapper white-background">
                    <iframe id="check-out-print-preview-iframe" src="{{url('admin/roomhistory/printcheckout/'.$roomHistory->id.'?lang='.Request::get('lang'))}}" width="900" height="700" scrolling="yes"></iframe>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif

  </div>
{{--{{dd($currentCheckedInRoomStayHistroy)}}--}}
  <!-- /.content-wrapper -->
@endsection
@section('script')
    <script src="{{ mix('js/angular/room-history-detail/room-history-detail.min.js') }}"></script>
    <script>

        $(document).ready(function () {
            disableForm();
        });

        function disableForm() {
            var form = document.getElementById("edit-room-form");
            var allElements = form.elements;
            for (var i = 0, l = allElements.length; i < l; ++i) {
                // allElements[i].readOnly = true;
                allElements[i].disabled = true;
            }
        }

    </script>
@endsection