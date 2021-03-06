@extends('layouts.frontend')

@section('contenido')
  <link rel="stylesheet" href="{{url('/css/fonts.css')}}">
  <style>
      .login-logo .subtitle::after {
      content: "-";
      margin-left: 5px;
      display: inline-block;
    }
    .login-logo .subtitle::before {
    content: "-";
    margin-right: 5px;
    display: inline-block;
  }
  .login-logo a {
    letter-spacing: 7px;
}
  </style>
<div class="login-box">
  <div class="login-logo">
    <a href="">DECO<strong>WOOD</strong></a>
    <div class="subtitle" style="text-transform: uppercase;font-size: 14px;display: block;margin-top: -6px;">ADMIN</div>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Identifícate para iniciar sesión.</p>

	 <form role="form" method="POST" action="{{ url('/login') }}">
	 {{ csrf_field() }}
      <div class="form-group{{ $errors->has('apodo') ? ' has-error' : '' }} has-feedback">
		<input id="apodo" type="text" class="form-control" name="apodo" value="{{ old('apodo') }}" placeholder="Apodo" required autofocus>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
		@if ($errors->has('apodo'))
			<span class="help-block">
				<strong>{{ $errors->first('apodo') }}</strong>
			</span>
		@endif
      </div>

      <div class="form-group{{ $errors->has('clave') ? ' has-error' : '' }} has-feedback">
		<input id="clave" type="password" class="form-control" name="clave" placeholder="Clave" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
		@if($errors->has('clave'))
			<span class="help-block">
				<strong>{{ $errors->first('clave') }}</strong>
			</span>
		@endif
      </div>


      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember"> Recordar
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Aceptar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <!-- /.social-auth-links -->

    <a href="{{url('/password/reset')}}">Restablecer contraseña.</a><br>
    {{-- <a href="{{url('/register')}}" class="text-center">Registrar nuevo usuario.</a>--}}

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@endsection
