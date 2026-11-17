@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{__('shared.menu_equipment')}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</a></li>
        <li class="active">{{__('shared.equipment_management')}}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Your Page Content Here -->
        <div class="box box-info"> 
        
            <div class="box-header with-border">
                <h3 class="box-title">{{__('shared.equipment_management')}}</h3>
                <div class="pull-right">
                    @if(Auth::user()->hasRole('admin'))
                    <a href="{{url('equipment/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hiden="true"></i> {{__('shared.equipment_add')}}</a>
                    @endif
                </div>
            </div>

            <div class="box-body"> 
                <div class="row">
                        <div class="col-xs-12"> 
                            <div class="box-body table-responsive no-padding">
                            <table class="table table-hover equipment-table">
                                <tbody>
                                    <tr>
                                        <th>
                                            {!!PaginationHelper::getPaginationColumn(
                                                'equipment',
                                                'equipment_no',
                                                __('shared.equipment_no'),
                                                $equipments->currentPage(),
                                                'equipment_no'
                                            )!!}
                                        </th>
                                        <th>
                                            {!!PaginationHelper::getPaginationColumn(
                                                'equipment',
                                                'name',
                                                __('shared.equipment_name'),
                                                $equipments->currentPage(),
                                                'equipment_no'
                                            )!!}
                                        </th>
                                        <th>
                                        {!!PaginationHelper::getPaginationColumn(
                                                'equipment',
                                                'total_quantity',
                                                __('shared.equipment_quantity_total'),
                                                $equipments->currentPage(),
                                                'equipment_no'
                                            )!!}
                                        </th>
                                        <th>
                                        {!!PaginationHelper::getPaginationColumn(
                                                'equipment',
                                                'available_quantity',
                                                __('shared.equipment_quantity_left'),
                                                $equipments->currentPage(),
                                                'equipment_no'
                                            )!!}
                                        </th>
                                        @if(Auth::user()->hasRole('admin'))
                                            <th class="text-center"></th> 
                                        @endif
                                    </tr>
                                    @if(sizeof($equipments) > 0)
                                        @foreach($equipments as $key => $equipment)
                                            <tr>
                                                <td><a href="{{url('/equipment/'.$equipment->id.'/edit')}}">{{$equipment->equipment_no}}</a></td>
                                                <td><a href="{{url('/equipment/'.$equipment->id.'/edit')}}">{{$equipment->name}}</a></td>
                                                <td><a href="{{url('/equipment/'.$equipment->id.'/edit')}}">{{number_format($equipment->total_quantity,2)}}</a></td>
                                                <td><a href="{{url('/equipment/'.$equipment->id.'/edit')}}">{{number_format($equipment->available_quantity,2)}}</a></td>
                                                @if(Auth::user()->hasRole('admin') && $equipment->canDelete())
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger" onclick="deleteEquipment('{{url("equipment/$equipment->id")}}')">{{__('shared.button_delete')}}</button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div class="pull-right">
                                    {{$equipments->links(
                                        'vendor.pagination.default',
                                        [
                                        'customParameters' => PaginationHelper::getPaginationCustomParameters()
                                        ]
                                    )}}
                                </div>
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
    function deleteEquipment(url)
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