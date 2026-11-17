@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" ng-controller="EquipmentHistoryDetailController">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{__('shared.menu_equipment')}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</a></li>
        <li><a href="{{url('/equipment/')}}">{{__('shared.equipment_management')}}</a></li>
        <li><a href="{{url('/equipment/'.$equipment->id.'/edit')}}">{{__('shared.equipment_information').' ('.$equipment->equipment_no.')'}}</a></li>
        <li><a href="{{url('/admin/equipmenthistory?EquipmentId='.$equipment->id)}}">{{__('shared.equipment_change_history_management').' ('.$equipment->equipment_no.')'}}</a></li>
        <li class="active">{{__('shared.equipment_change_history').' ('.$equipment->equipment_no.')'}}</li>
      </ol>
    </section>

  <!-- Main content -->
  <section class="content">
  <!-- Your Page Content Here -->
  <div class="box box-info">
  <div class="box-header with-border">
      <h3 class="box-title">{{__('shared.equipment_change_history').' ('.$equipment->equipment_no.')'}}</h3>
  </div>
  <form action="{{url('/admin/equipmenthistory/'.$equipmentHistory->id)}}" method="post" class="form-horizontal" id="edit-equipment-form">
      {!!csrf_field()!!}
      {{ method_field('PUT') }}
      <div class="box-body equipment-detail "> 
      
          <div class="form-group">
              <label class="col-md-3 col-sm-4 control-label">{{__('shared.equipment_no')}}</label>
              <div class="col-md-9 col-sm-8">
                  <h4 class="show-data">{{$equipment->equipment_no}}</h4>
                  
              </div> 
          </div>

          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="col-md-3 col-sm-4 control-label">{{__('shared.equipment_name')}}</label>
              <div class="col-md-9 col-sm-8">
                  @if(Auth::user()->hasRole('admin'))
                      <input type="text" name="name" value="{{ old('name') != null  ? old('name') : $equipment->name}}" class="form-control"/>
                  @else
                      <input type="text" name="name" value="{{ old('name') != null  ? old('name') : $equipment->name}}" class="form-control" readonly="readonly"/>
                  @endif
              </div>
          </div>
          
          <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            <label class="col-md-3 col-sm-4 control-label">{{__('shared.equipment_description')}}</label>
              <div class="col-md-9 col-sm-8">
                  @if(Auth::user()->hasRole('admin'))
                      <input type="text" name="description" value="{{ old('description') != null  ? old('description') : $equipment->description}}" class="form-control">
                  @else
                      <input type="text" name="description" value="{{ old('description') != null  ? old('description') : $equipment->description}}" class="form-control" readonly="readonly">
                  @endif
              </div>
          </div> 
          
          <div class="form-group{{ $errors->has('total_quantity') ? ' has-error' : '' }}">
            <label class="col-md-3 col-sm-4 control-label">{{__('shared.equipment_quantity_total')}}</label>
             <div class="col-md-9 col-sm-8">
             @if(Auth::user()->hasRole('admin'))
                  <input type="text" name="total_quantity_show" id="total_quantity_show" value="{{ old('total_quantity') != null  ? old('total_quantity') : $equipment->total_quantity}}" class="form-control">
             @else
                  <input type="text" name="total_quantity_show" id="total_quantity_show" value="{{ old('total_quantity') != null  ? old('total_quantity') : $equipment->total_quantity}}" class="form-control" readonly="readonly">
             @endif
                  @if ($errors->has('total_quantity'))
                      <span class="help-block">
                          <strong>{{ $errors->first('total_quantity') }}</strong>
                      </span> 
                  @endif

              </div>
          </div>

          <div class="form-group{{ $errors->has('available_quantity') ? ' has-error' : '' }}">
            <label class="col-md-3 col-sm-4 control-label">{{__('shared.equipment_quantity_left')}}</label>
             <div class="col-md-9 col-sm-8">
                  <input type="text" name="available_quantity" id="available_quantity_show"  value="{{ old('available_quantity') != null  ? old('available_quantity') : $equipment->available_quantity}}" class="form-control" readonly="readonly">

                  @if ($errors->has('available_quantity'))
                      <span class="help-block">
                          <strong>{{ $errors->first('available_quantity') }}</strong>
                      </span> 
                  @endif

              </div>
          </div>

          <div class="form-group{{ $errors->has('created_at') ? ' has-error' : '' }}">
            <label class="col-md-3 col-sm-4 control-label">{{__('shared.equipment_created_at')}}</label>
             <div class="col-md-9 col-sm-8">
                  <input type="text" value="{{ old('created_at') != null  ? old('created_at') : $equipment->created_at}}" class="form-control" readonly="readonly">
              </div>
          </div> 

          <div class="form-group">
              <label class="col-md-3 col-sm-4 control-label">{{__('shared.equipment_room')}}</label>
              <div class="col-md-9 col-sm-8">

                  <button type="button" ng-click="onAddRoomEquipmentBtnClick();" class="btn btn-primary add-room-equipment-btn">{{__('shared.button_add')}}</button>
                  
                  <table class="table table-hover" ng-show="roomEquipments.length > 0 || isAddNewRoomEquipment">
                      <tbody>
                      <tr> 
                          <th width="5%">#</th>
                          <th width="25%">{{__('shared.room_number')}}</th>
                          <th width="15%">{{__('shared.equipment_taken_quantity')}}</th>
                          <th>{{__('shared.equipment_taken_date')}}</th>
                          <th class="text-center"></th>
                      </tr>
                      <tr ng-repeat="roomEquipment in roomEquipments | orderBy : 'sort_no'">
                          <td><% $index+1 %></td>
                          <td ng-if="!isEditRoomEquipment || editRoomEquipmentIndex!=$index"><% getRoomNo(roomEquipment.room_id) %></td>
                          <td ng-if="isEditRoomEquipment && editRoomEquipmentIndex==$index">
                              <select class="form-control" name="selectedRoomId" ng-change="onEditRoomEquipmentChangeRoom();" ng-model="selectedEditRoomEquipment">
                                  <option value=""></option>
                                  <option ng-repeat="room in rooms" value="<%room.id%>" ng-hide="validateEditRoomEquipmentExist(room.id)"><%room.room_no%></option>             
                              </select>  
                          </td>
                          <td ng-show="!isEditRoomEquipment || editRoomEquipmentIndex!=$index"><% roomEquipment.taken_quantity_show %></td>
                          <td ng-show="isEditRoomEquipment && editRoomEquipmentIndex==$index">
                              <div class="input-group-edit-room-equipment-taken-quantity">
                                  <input type="text" class="form-control" name="roomEquipmentTakenQuality" id="editRoomEquipmentTakenQuality<%$index%>">
                              </div>
                          </td>
                          <td ng-show="!isEditRoomEquipment || editRoomEquipmentIndex!=$index"><% roomEquipment.taken_date %></td>
                          <td ng-show="isEditRoomEquipment && editRoomEquipmentIndex==$index">
                              <div class="input-group date" id="edit-room-equipment-datetime-group-<%$index%>">
                                  <input type="text" class="form-control">
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                  </span> 
                              </div>
                          </td>
                          <td class="text-center" ng-if="!isEditRoomEquipment || editRoomEquipmentIndex!=$index">
                              <button type="button" ng-if="isAdmin" ng-click="onEditRoomEquipmentBtnClick($index);" class="btn btn-warning">{{__('shared.button_edit')}}</button>
                              <button type="button" ng-if="isAdmin" ng-click="onDeleteRoomEquipmentBtnClick($index);" class="btn btn-danger">{{__('shared.button_delete')}}</button>
                          </td>
                          <td class="text-center" ng-if="isEditRoomEquipment && editRoomEquipmentIndex==$index">
                              <button type="button" ng-if="isAdmin" ng-click="onSaveRoomEquipmentBtnClick();" class="btn btn-primary">{{__('shared.button_save')}}</button>
                              <button type="button" ng-if="isAdmin" ng-click="onCancelEditRoomEquipmentBtnClick();" class="btn btn-default">{{__('shared.button_cancel')}}</button>
                          </td>
                      </tr>
                      
                      <tr ng-show="isAddNewRoomEquipment">
                          <td></td> 
                          <td>
                              <select class="form-control" name="selectedRoomId" ng-change="onNewRoomEquipmentChangeRoom();" ng-model="selectedNewRoomEquipment">
                                  <option value=""></option>
                                  <option ng-repeat="room in rooms" value="<%room.id%>" ng-hide="validateRoomEquipmentExist(room.id)"><%room.room_no%></option>             
                              </select>  
                             
                          </td>
                          <td>
                              <div class="input-group-room-equipment-taken-quantity">
                                  <input type="text" class="form-control" name="roomEquipmentTakenQuality" id="roomEquipmentTakenQuality">
                              </div>
                          </td>
                          <td>
                              <div class="input-group date" id="add-room-equipment-datetime-group">
                                  <input type="text" class="form-control">
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                  </span> 
                              </div>
                          </td> 
                          <td class="text-center">
                              <button type="button" ng-click="onSubmitRoomEquipmentBtnClick();" class="btn btn-primary">{{__('shared.button_add')}}</button>
                              <button type="button" ng-click="onCancelSubmitRoomEquipmentBtnClick();"  class="btn btn-danger">{{__('shared.button_delete')}}</button>
                          </td>
                      </tr> 

                      </tbody>
                  </table>

              </div>
          </div> 

      </div>
      <!-- /.box-body -->
      <div class="box-footer">
          <a href="{{url('/equipment')}}" class="btn btn-default">{{__('shared.button_cancel')}}</a>
          <button type="submit" class="btn btn-warning pull-right">{{__('shared.button_save')}}</button>
      </div>
      <!-- /.box-footer -->

      {{--  Hidden fields  --}}
      <div class="hidden-form sr-only">

          <input type="text" name="total_quantity" value="<%totalQuantity%>" ng-if="isAdmin"/>

          <div class="roomEquipments-group" ng-repeat="roomEquipment in roomEquipments | orderBy : 'sort_no'"> 
              
              <input type="text" name="<%'roomEquipments['+$index+'][id]'%>" value="<%roomEquipment.id%>"/>
              <input type="text" name="<%'roomEquipments['+$index+'][room_id]'%>" value="<%roomEquipment.room_id%>"/>
              <input type="text" name="<%'roomEquipments['+$index+'][taken_quantity_show]'%>" value="<%roomEquipment.taken_quantity_show%>"/>
              <input type="text" name="<%'roomEquipments['+$index+'][taken_quantity]'%>" value="<%roomEquipment.taken_quantity%>"/>
              <input type="text" name="<%'roomEquipments['+$index+'][taken_date]'%>" value="<%roomEquipment.taken_date%>"/>
              <input type="text" name="<%'roomEquipments['+$index+'][sort_no]'%>" value="<% roomEquipment.sort_no ? roomEquipment.sort_no : $index %>"/>
              <input type="text" name="<%'roomEquipments['+$index+'][created_at]'%>" value="<% roomEquipment.created_at %>"/>
              <input type="text" name="<%'roomEquipments['+$index+'][updated_at]'%>" value="<% roomEquipment.updated_at %>"/>
          
          </div>
          
          <div class="delete-roomEquipments-group" ng-repeat="roomEquipment in deletedRoomEquipment"> 
              <input type="text" name="<%'delete_roomEquipments['+$index+'][id]'%>" value="<%roomEquipment.id%>"/> 
          </div>
                                 
      </div>
      

  </form> 
</section>
<!-- /.content -->

{{--  Value  --}}
<span class="sr-only" id="available-quantity-span" data-available-quantity="{{ $equipment->available_quantity }}"></span>
<span class="sr-only" id="rooms-span" data-rooms="{{ json_encode($rooms) }}"></span>
<span class="sr-only" id="room-equipments-span" data-room-equipments="{{ old('roomEquipments') != null  ? json_encode(old('roomEquipments')) : json_encode($equipment->room_equipment) }}"></span>
<span class="sr-only" id="is-admin-span" data-is-admin="{{Auth::user()->hasRole('admin')}}"></span>

<span class="sr-only" id="modal-delete-title" data-modal-delete-title="{{__('shared.modal_delete_title')}}"></span>
<span class="sr-only" id="modal-delete-body" data-modal-delete-body="{{__('shared.modal_delete_body')}}"></span>
<span class="sr-only" id="button-cancel" data-button-cancel="{{__('shared.button_cancel')}}"></span>
<span class="sr-only" id="button-delete" data-button-delete="{{__('shared.button_delete')}}"></span>

</div>
<!-- /.content-wrapper -->

    @include('partials.modal')

@endsection
@section('script') 

<script src="{{ mix('js/angular/equipment-history-detail/equipment-history-detail.min.js') }}"></script>
 
@endsection