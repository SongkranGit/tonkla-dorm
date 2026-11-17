<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{__('shared.title')}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Styles -->
  <link href="{{ asset('/css/app.css') }}" rel="stylesheet"> 
  <link href="{{ asset('/css/admin-lte.css') }}" rel="stylesheet"> 

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box" id="app">
  <div class="login-logo">
    <a href="{{url('login')}}"><i class="fa fa-lock" aria-hidden="true"></i> <b>เข้าสู่ระบบ</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body"> 
  
    <form action="{{url('login')}}" method="post">
 
      {!! csrf_field() !!}

      {{--  <div class="form-group">
        <img src="{{asset('images/logo_tonkla.jpg')}}" alt="logo" class="img-responsive"/>
      </div>  --}}

      <div class="form-group has-feedback {{ $errors->has('username') ? ' has-error' : '' }}">
        <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="ชื่อผู้ใช้">
        
        @if ($errors->has('username'))
            <span class="help-block pull-right">
                <strong>{{ $errors->first('username') }}</strong>
            </span>
        @endif

      </div>

      <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
        <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน">

        @if ($errors->has('password'))
            <span class="help-block pull-right">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif

      </div>
      <div class="row"> 
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">{{__('shared.button_login')}}</button>
        </div>
        <!-- /.col -->
      </div>

    </form> 

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<!-- Scripts --> 
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
