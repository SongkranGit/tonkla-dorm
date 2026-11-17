@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{__('shared.extra_rate_menu')}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</a></li>
        <li class="active">{{__('shared.extra_rate_management')}}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Your Page Content Here -->
        <div class="box box-info"> 
        
            <div class="box-header with-border">
                <h3 class="box-title">{{__('shared.extra_rate_management')}}</h3>
                
                <div class="pull-right">
                    <a href="{{url('admin/settings/extra/create')}}" class="btn btn-primary">{{__('shared.button_add')}}</a>
                </div>
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
                                                    'admin/settings/extra',
                                                    'extra_sort_no',
                                                    __('shared.extra_rate_column_sort_no'),
                                                    $extras->currentPage(),
                                                    'extra_sort_no'
                                                )!!}
                                            </th>
                                            <th>
                                                {!!PaginationHelper::getPaginationColumn(
                                                    'admin/settings/extra',
                                                    'extra_key',
                                                    __('shared.extra_rate_column_extra_key'),
                                                    $extras->currentPage(),
                                                    'extra_sort_no'
                                                )!!}
                                            </th>
                                            <th>
                                                {!!PaginationHelper::getPaginationColumn(
                                                    'admin/settings/extra',
                                                    'value',
                                                    __('shared.extra_rate_column_value'),
                                                    $extras->currentPage(),
                                                    'extra_sort_no'
                                                )!!}
                                            </th>
                                            <th></th>
                                        </tr>
                                        @if(sizeof($extras) > 0)
                                            @foreach($extras as $key => $extra)
                                                <tr>
                                                    <td><a href="{{url('admin/settings/extra/'.$extra->id.'/edit')}}">{{  $extra->extra_sort_no }}</a></td>
                                                    <td><a href="{{url('admin/settings/extra/'.$extra->id.'/edit')}}">{{  $extra->extra_key }}</a></td>
                                                    <td><a href="{{url('admin/settings/extra/'.$extra->id.'/edit')}}">{{ number_format($extra->value,2) }}</a></td>
                                                    
                                                    @if($extra->canDelete())
                                                        <td class="text-center"><button type="button" class="btn btn-danger" onclick="deleteSetting('{{url("admin/settings/extra/$extra->id")}}')">{{__('shared.button_delete')}}</button></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>

                                </table>
                                <div class="pull-right">
                                    {{$extras->links(
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
    function deleteSetting(url)
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