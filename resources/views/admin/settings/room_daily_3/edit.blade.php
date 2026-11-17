@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         {{__('shared.menu_settings')}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</a></li>
        <li><a href="{{url('/admin/settings/roomratedaily3')}}">{{__('shared.room_daily_rate')}}</a></li>
        <li class="active">{{__('shared.edit').' '.__('shared.room_daily_rate')}}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
      <div class="box box-info">
        <div class="box-header with-border">
                <h3 class="box-title">{{__('shared.edit').' '.__('shared.room_daily_rate')}}</h3>
        </div>
        <form action="{{url('/admin/settings/roomratedaily3')}}" method="post" class="form-horizontal" >
            {!!csrf_field()!!}

            <div class="box-body"> 
                <div class="form-group{{ $errors->has('value') ? ' has-error' : '' }}">
                    <label for="input" class="col-sm-2 control-label">{{__('shared.room_daily_rate_per_unit')}}</label>
                    <div class="col-sm-10">
                        <input type="text" name="valueShow" value="{{  old('roomDaily')  != null ?  old('roomDaily') : $roomDaily }}" class="form-control" id="input" placeholder="">
                        <input type="hidden" name="value" value="{{  old('roomDaily')  != null ?  old('roomDaily') : $roomDaily }}" id="inputHidden">

                        @if ($errors->has('value'))
                            <span class="help-block">
                                <strong>{{ $errors->first('value') }}</strong>
                            </span> 
                        @endif
                    </div> 
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <a href="{{url('/admin/settings/roomratedaily3')}}" class="btn btn-default">{{__('shared.button_cancel')}}</a>
                <button type="submit" class="btn btn-warning pull-right">{{__('shared.button_edit')}}</button>
            </div>
            <!-- /.box-footer -->
        </form> 
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
@section('script')
<script>
    $(document).ready(function(){
        var inputElem = new AutoNumeric('#input');
        $("form").on("submit", function(){
            $("#inputHidden").val(inputElem.get());
        })
    });
</script>
@endsection