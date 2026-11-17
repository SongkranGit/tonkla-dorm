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
        <li class="active">{{__('shared.vat_rate_monthly')}}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Your Page Content Here -->
        <div class="box box-info"> 
        
            <div class="box-header with-border">
                <h3 class="box-title">{{__('shared.vat_rate_monthly')}}</h3>
                
                <div class="pull-right">
                    <a href="{{url('admin/settings/vatspecial/edit')}}" class="btn btn-warning">{{__('shared.button_edit')}}</a>
                </div>
            </div>
            <div class="box-body"> 
                <div class="row">
                    <label class="col-sm-4 control-label">{{__('shared.vat_rate_with_percent')}}</label>
                    <div class="col-sm-4">
                        {{number_format($vat,2)}}
                    </div> 
                    <label class="col-sm-4 control-label"> 
                    </label>
                </div>
            </div>
            
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection