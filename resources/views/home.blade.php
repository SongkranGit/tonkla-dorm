@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-user" aria-hidden="true"></i>
            </h1>
            <ol class="breadcrumb">
                <li class="active"><i class="fa fa-dashboard"></i> {{__('shared.home')}}</li>
                {{--  <li class="active">Here</li>  --}}
            </ol>
        </section>
        <style>

            /*--CSS--*/
            .block {
                text-align: center;
                vertical-align: middle;
            }

            .circle {
                background: #3C8DBC;
                border-radius: 50%;
                color: white;
                height: 160px;
                font-weight: 500;
                font-size: 16px;
                width: 160px;
                display: table;
                margin: 20px auto;
                padding: 10px;
                box-shadow: 0 0 0 5px #cbcfd4;
                border: 4px solid #fff;
            }

            a:hover .circle {
                background-color: #21c249;
            }

            .circle p {
                vertical-align: middle;
                display: table-cell;
            }
        </style>
        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    @if(session('should_show_branch_list') && !empty($branches)  )
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-9" >
                                    @foreach($branches as $branch)
                                        <div class="col-md-3 block">
                                            <a href="{{route('branch' , $branch->id)}}" title="คลิกเลือกสาขา">
                                                <div class="circle">
                                                    <p>{{$branch->name}}</p>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                        </div>
                    @else
                        <img class="profile-user-img img-responsive img-circle"
                             src="https://placehold.it/160x160/3c8dbc/ffffff/&text={{ mb_substr(strtoupper(Auth::user()->username), 0, 1) }}"
                             alt="User profile picture">

                        <h3 class="profile-username text-center">{{Auth::user()->username}}</h3>

                        <p class="text-muted text-center">{{Auth::user()->email}}</p>

                        <p class="text-muted text-center">สาขา :: {{session("current_branch_name")}}</p>
                    @endif

                </div>
                <!-- /.box-body -->
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
