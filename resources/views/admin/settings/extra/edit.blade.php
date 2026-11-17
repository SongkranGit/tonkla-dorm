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
        <li><a href="{{url('/admin/settings/extra')}}">{{__('shared.extra_rate_management')}}</a></li>
        <li class="active">{{__('shared.edit').' '.__('shared.extra_rate_management')}}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Your Page Content Here -->
      <div class="box box-info">
        <div class="box-header with-border">
                <h3 class="box-title">{{__('shared.edit').' '.__('shared.extra_rate_management')}}</h3>
        </div>
        <form action="{{url('/admin/settings/extra/'.$extra->id)}}" method="post" class="form-horizontal" >
            {!!csrf_field()!!}
            {{ method_field('PUT') }}

            <div class="box-body"> 
                <div class="form-group{{ $errors->has('extra_key') ? ' has-error' : '' }}">
                    <label for="input" class="col-sm-2 control-label">{{__('shared.extra_rate_extra_key')}}</label>
                    <div class="col-sm-10">
                        <input type="text" name="extra_key" value="{{ old('extra_key') != null ?  old('extra_key') : $extra->extra_key }}" class="form-control" placeholder=""> 
                        <input type="hidden" name="key" value="{{  $extra->key  }}" >
                        @if ($errors->has('extra_key'))
                            <span class="help-block">
                                <strong>{{ $errors->first('extra_key') }}</strong>
                            </span> 
                        @endif
                    </div> 
                </div>

                <div class="form-group{{ $errors->has('value') ? ' has-error' : '' }}">
                    <label for="input" class="col-sm-2 control-label">{{__('shared.extra_rate_value')}}</label>
                    <div class="col-sm-10">
                        <input type="text" name="valueShow" value="{{  old('value')  != null ?  old('value') : $extra->value  }}" class="form-control" id="input" placeholder="">
                        <input type="hidden" name="value" value="{{  old('value')  != null ?  old('value') : $extra->value  }}" id="inputHidden">
                        
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
                <a href="{{url('/admin/settings/extra')}}" class="btn btn-default">{{__('shared.button_cancel')}}</a>
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