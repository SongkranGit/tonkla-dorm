@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                จัดการให้สิทธิการอนุมัติ
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</a></li>
                <li class="active">ให้สิทธิการอนุมัติ</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            <div class="box box-info">

                <div class="box-header with-border">
                    <h3 class="box-title">รายการให้สิทธิผู้ใช้ในการอนุมัติ</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <th width="5%" style="text-align: center">ลำดับ</th>
                                        <th width="20%">ผู้ใช้</th>
                                        <th width="20%">อีเมล์</th>
                                        <th width="20%">สถานะ</th>
                                        <th width="20%">จัดการ</th>
                                    </tr>
                                    @if(sizeof($approvalPrivileges) > 0)
                                        @php $count = 0 ;@endphp
                                        @foreach($approvalPrivileges as $key => $privilege)
                                            @php $count++; @endphp
                                            <tr>
                                                <td align="center">{{$count}}</td>
                                                <td>{{$privilege->user->username}}</td>
                                                <td>{{$privilege->user->email}}</td>
                                                <td>
                                                    @if(boolval($privilege->can_approve))
                                                        <span class="label label-success">มีสิทธิอนุมัติ</span>
                                                    @else
                                                         <span class="label label-default">ไม่มีสิทธิอนุมัติ</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" onclick="updateStatusCanApprove('{{$privilege->id}}' , 1)"
                                                       class="btn btn-primary ">
                                                        <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Approve
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a href="javascript:void(0)" onclick="updateStatusCanApprove('{{$privilege->id}}' , 0)"
                                                       class="btn btn-danger ">
                                                        <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Reject
                                                    </a>
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
        function updateStatusCanApprove(approvalPrivilegeId , canApprove) {
            var token = $('meta[name="csrf-token"]').attr('content');
            var targetUrl = '{{url('admin/privilege/approve')}}' + '/' + approvalPrivilegeId;
            $.ajax({
                url: targetUrl,
                type: 'POST',
                data: {
                    _token: token,
                    can_approve: canApprove
                },
                success: function (result) {
                    console.log(result)
                    location.reload();
                }
            });
        }

    </script>
@endsection