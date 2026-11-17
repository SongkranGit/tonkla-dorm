@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{__('shared.menu_change_history')}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</a></li>
        <li><a href="{{url('/equipment/')}}">{{__('shared.equipment_management')}}</a></li>
        <li><a href="{{url('/equipment/'.$equipment->id.'/edit')}}">{{__('shared.equipment_information').' ('.$equipment->equipment_no.')'}}</a></li>
        <li class="active">{{__('shared.equipment_change_history_management').' ('.$equipment->equipment_no.')'}}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Your Page Content Here -->
        <div class="box box-info"> 
        
            <div class="box-header with-border">
                <h3 class="box-title">{{__('shared.equipment_change_history_management').' ('.$equipment->equipment_no.')'}}</h3>
            </div>

            <div class="box-body"> 
                <div class="row">
                        <div class="col-xs-12"> 
                            <div class="box-body table-responsive no-padding">
                            <table class="table table-hover extra-setting-table">
                                    <tbody> 
                                        <tr>
                                            <th>
                                                {!!PaginationHelper::getPaginationColumn(
                                                    'admin/equipmenthistory',
                                                    'id',
                                                    __('shared.equipment_change_history_column_id'),
                                                    $equipmentHistories->currentPage().'&EquipmentId='.$equipment->id,
                                                    'id'
                                                )!!}
                                            </th>
                                            <th>
                                                {!!PaginationHelper::getPaginationColumn(
                                                    'admin/equipmenthistory',
                                                    'log_name',
                                                    __('shared.equipment_change_history_column_change_type'),
                                                    $equipmentHistories->currentPage().'&EquipmentId='.$equipment->id,
                                                    'id'
                                                )!!}
                                            </th>
                                            <th>
                                                {!!PaginationHelper::getPaginationColumn(
                                                    'admin/equipmenthistory',
                                                    'created_at',
                                                    __('shared.equipment_change_history_column_created_at'),
                                                    $equipmentHistories->currentPage().'&EquipmentId='.$equipment->id,
                                                    'id'
                                                )!!}
                                            </th>
                                            <th>
                                                {!!PaginationHelper::getPaginationColumn(
                                                    'admin/equipmenthistory',
                                                    'updated_at',
                                                    __('shared.equipment_change_history_column_updated_at'),
                                                    $equipmentHistories->currentPage().'&EquipmentId='.$equipment->id,
                                                    'id'
                                                )!!}
                                            </th>
                                            <th></th>
                                        </tr>
                                        @if(sizeof($equipmentHistories) > 0)
                                            @foreach($equipmentHistories as $key => $item)
                                                <tr>
                                                    <td><a href="{{url('admin/equipmenthistory/'.$item->id.'/edit')}}">{{  $item->id }}</a></td>
                                                    <td><a href="{{url('admin/equipmenthistory/'.$item->id.'/edit')}}">{{  $item->log_name == 'equipment_updated' ? __('shared.equipment_change_history_equipment_updated')  : '' }}</a></td> 
                                                    <td><a href="{{url('admin/equipmenthistory/'.$item->id.'/edit')}}">{{  \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</a></td> 
                                                    <td><a href="{{url('admin/equipmenthistory/'.$item->id.'/edit')}}">{{  \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i:s')  }}</a></td> 
                                                    <td class="text-center"><button type="button" class="btn btn-danger" onclick="deleteEquipmentHistory('{{url("admin/equipmenthistory/".$item->id)}}')">{{__('shared.button_delete')}}</button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>

                                </table>
                                <div class="pull-right">
                                    {{$equipmentHistories->links(
                                        'vendor.pagination.default',
                                        [
                                            'customParameters' => PaginationHelper::getPaginationCustomParameters().'&EquipmentId='.$equipment->id
                                        ]
                                    )}}
                                </div>
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
    @include('partials.modal')
@endsection
@section('script')
<script>      
    function deleteEquipmentHistory(url)
    {
        var token = $('meta[name="csrf-token"]').attr('content'); 
        modal.confirm("{{__('shared.modal_delete_title')}}",
            "{{__('shared.modal_delete_body')}}",
            "{{__('shared.button_cancel')}}",
            "{{__('shared.button_delete')}}",
        function(){
            $.ajax({
                url: url,
                type: 'POST',
                data: {_method: 'delete', _token :token},
                success: function(result) {
                    location.reload();
                }
            });
        }); 
    } 
</script>
@endsection