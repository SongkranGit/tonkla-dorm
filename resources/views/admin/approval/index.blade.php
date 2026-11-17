@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                จัดการอนุมัติเช็คอิน-เช็คเอ้าท์
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</a></li>
                <li class="active">อนุมัติเช็คอิน-เช็คเอ้าท์</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            <div class="box box-info">

                <div class="box-header with-border">
                    <h3 class="box-title">รายการรออนุมัติเช็คอิน-เช็คเอ้าท์</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                    <th width="5%" style="text-align: center">ลำดับ</th>
                                    <th width="25%">ห้อง</th>
                                    <th width="20%">สถานะการอนุมัติเช็คอิน</th>
                                    <th width="20%">สถานะการอนุมัติเช็คเอ้าท์</th>
                                    <th width="30%" style="text-align: center">จัดการ</th>
                                    </tr>
                                    @if(sizeof($roomApprovals) > 0)
                                        @php $count = 0 ;@endphp
                                        @foreach($roomApprovals as $key => $approval)
                                            @php $count++; @endphp
                                            <tr>
                                                <td align="center">{{$count}}</td>
                                                <td>{{$approval->room->room_no}}</td>
                                                <td>
                                                    @if(boolval($approval->is_checkin_approved))
                                                        <span class="label label-success">อนุมัติแล้ว</span>
                                                    @else
                                                        <span class="label label-default">ยังไม่ได้อนุมัติ</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(boolval($approval->is_checkout_approved))
                                                        <span class="label label-success">อนุมัติแล้ว</span>
                                                    @else
                                                        <span class="label label-default">ยังไม่ได้อนุมัติ</span>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if(boolval($approval->is_request_approve_checkin))
                                                        <a href="javascript:void(0)" class="btn btn-primary" onclick="approve('{{$approval->room_id}}' , 'check_in')" ><i class="fa fa-edit" aria-hidden="true"></i> อนุมัติเช็คอิน</a>&nbsp; &nbsp;
                                                    @endif
                                                    @if(boolval($approval->is_request_approve_checkout))
                                                        <a href="javascript:void(0)" class="btn btn-primary" onclick="approve('{{$approval->room_id}}' , 'check_out')"  ><i class="fa fa-edit" aria-hidden="true"></i> อนุมัติเช็คเอ้าท์</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
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
@endsection
@section('script')
    <script>
        function approve(roomId , checkingType) {
            var token = $('meta[name="csrf-token"]').attr('content');
            var targetUrl = '{{url('admin/approval/approve')}}' + '/' + roomId;
            $.ajax({
                url: targetUrl,
                type: 'POST',
                data: {
                    _token: token,
                    checking_type: checkingType
                },
                success: function (result) {
                    console.log(result)
                    location.reload();
                }
            });
        }

    </script>
@endsection