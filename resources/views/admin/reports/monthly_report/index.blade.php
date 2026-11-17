@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{__('shared.menu_report')}}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</a></li>
        <li class="active">{{__('shared.monthly_report')}}</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content monthly-report-container">
        <!-- Your Page Content Here -->
        <div class="box box-info"> 
        
            <div class="box-header with-border">
                <h3 class="box-title">{{__('shared.profit_report')}}</h3>
                <form action="{{url('admin/reports/monthlyreport')}}" role="form" class="form-horizontal"> 
                    <div class="col-md-12">
                        <h5>{{__('shared.profit_report_select_range')}}</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('month') ? ' has-error' : '' }}">
                            <label for="month" class="col-sm-4 control-label">{{__('shared.profit_report_select_month')}}</label>
                            <div class="col-sm-8 input-group date" id="month">
                                <input type="text" class="form-control" name="month" value="{{ old('month') != null  ? old('month') : $month}}">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                @if ($errors->has('month'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('month') }}</strong>
                                    </span> 
                                @endif

                            </div>
                        </div>
                    </div> 

                    <div class="col-md-6">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-calculator" aria-hidden="true"></i>&nbsp; ดูรายงาน</button>&nbsp;
                            <a type="button" onclick="downloadExcel()" class="btn btn-primary" ><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp; Download Excel</a>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <h5>{{__('shared.profit_report_summary') . ' : ' . number_format($reports['total'], 2)}}</h5>
                    </div>

              </form>
            </div>

            <div class="box-body"> 
                <div class="row">
                        <div class="col-xs-12"> 
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover monthly-report-table">
                                    <tbody>
                                        <tr>
                                            <th class="room-number">{{__('shared.room_column_room_number')}}</th>
                                            @foreach($reports['dates'] as $date)
                                                <th>{{$date->format('d/m/Y')}}</th>
                                            @endforeach
                                        </tr>
                                        @foreach($reports['reports'] as $key => $report) 
                                        <tr>
                                            <td class="room-number">{{$report['room_no']}}</td>
                                            @foreach($report['report_item'] as $item)                                            
                                                <td>
                                                    @foreach($item['items']['roomPrintedResult'] as $printItem)  
                                                        <li><b>{{number_format($printItem['total'], 2)}}</b> {{$printItem['date']}}</li>
                                                    @endforeach
                                                    @foreach($item['items']['roomCheckinPrintedResult'] as $printItem)  
                                                        <li><b>{{number_format($printItem['total'], 2)}}</b> {{$printItem['date']}}</li>
                                                    @endforeach
                                                    @foreach($item['items']['roomCheckOutPrintedResult'] as $printItem)  
                                                        <li><b>{{number_format($printItem['total'], 2)}}</b> {{$printItem['date']}}</li>
                                                    @endforeach
                                                </td>                                 
                                            @endforeach        
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body --> 
                        </div>
                    </div>
            </div>
        </div>
    </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection
@section('script')
<script>
    $(document).ready(function(){
        var month = $('#month');
        var token = $('meta[name="csrf-token"]').attr('content');
        month.datetimepicker({
            format: 'MM/YYYY',
            showTodayButton: true,
            allowInputToggle: true
        });
    });

    function downloadExcel() {
        var token = $('meta[name="csrf-token"]').attr('content');
        var targetUrl = '{{url("admin/reports/monthlyreport/excel")}}';
        $.ajax({
            xhrFields: {responseType: 'blob' },
            url: targetUrl,
            type: 'POST',
            data: {
                _token :token,
            },
            success: function(result, status, xhr) {
                var disposition = xhr.getResponseHeader('content-disposition');
                var matches = /"([^"]*)"/.exec(disposition);
                var filename = (matches != null && matches[1] ? matches[1] : 'salary.xlsx');
                // The actual download
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });
    }
</script>
@endsection