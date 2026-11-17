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
        <li><a href="{{url('/equipment')}}">{{__('shared.equipment_management')}}</a></li>
        <li class="active">{{__('shared.add').' '.__('shared.equipment_management')}}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
      <div class="box box-info">
        <div class="box-header with-border">
                <h3 class="box-title">{{__('shared.add').' '.__('shared.equipment_management')}}</h3>
        </div>
        <form action="{{url('/equipment')}}" method="post" class="form-horizontal" >
            {!!csrf_field()!!}

            <div class="box-body equipment-detail"> 
                <div class="form-group{{ $errors->has('equipment_no') ? ' has-error' : '' }}">
                    <label for="input" class="col-sm-2 control-label">{{__('shared.equipment_no')}}</label>
                    <div class="col-sm-10">
                        <input type="text" name="equipment_no" value="{{ old('equipment_no') }}" class="form-control" placeholder="">

                        @if ($errors->has('equipment_no'))
                            <span class="help-block">
                                <strong>{{ $errors->first('equipment_no') }}</strong>
                            </span> 
                        @endif
                    </div> 
                </div>

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="input" class="col-sm-2 control-label">{{__('shared.equipment_name')}}</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="">

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span> 
                        @endif
                    </div> 
                </div>

                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="input" class="col-sm-2 control-label">{{__('shared.equipment_description')}}</label>
                    <div class="col-sm-10">
                    <input type="text" name="description" value="{{ old('description') }}" class="form-control" placeholder="">

                        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span> 
                        @endif
                    </div> 
                </div>

                <div class="form-group{{ $errors->has('total_quantity') ? ' has-error' : '' }}">
                    <label for="input" class="col-md-2 control-label">{{__('shared.equipment_quantity')}}</label>
                    <div class="col-sm-10">
                        
                        <input type="text" name="total_quantity_show"  id="total_quantity_show" value="{{ old('total_quantity') }}" class="form-control"> 
                        <input type="hidden" name="total_quantity"  id="total_quantity_hidden" value="{{ old('total_quantity') }}" class="form-control">

                        @if ($errors->has('total_quantity'))
                            <span class="help-block">
                                <strong>{{ $errors->first('total_quantity') }}</strong>
                            </span> 
                        @endif
                    </div> 
                </div>
            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <a href="{{url('/equipment')}}" class="btn btn-default">{{__('shared.button_cancel')}}</a>
                <button type="submit" class="btn btn-primary pull-right">{{__('shared.button_add')}}</button>
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
        var inputElem = new AutoNumeric('#total_quantity_show', {
            decimalPlaces: 0,
            minimumValue: 0,
            modifyValueOnWheel: false,
        });
        $("form").on("submit", function(){
           $("#total_quantity_hidden").val(inputElem.get());
        })
    });
</script>
@endsection