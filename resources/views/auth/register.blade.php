@extends('layouts.frontend')

@section('contenido')
<div class="register-box">
<div class="login-logo">
    <a href=""><b>Admin</b>CP</a>
  </div>
<div class="register-box-body">
    <p class="login-box-msg">Registrar una nueva cuenta de usuario</p>

     <form role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}
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
	<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
			<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			@if ($errors->has('email'))
				<span class="help-block">
					<strong>{{ $errors->first('email') }}</strong>
				</span>
			@endif
	</div>
	<div class="form-group{{ $errors->has('clave') ? ' has-error' : '' }} has-feedback">
		<input id="clave" type="clave" class="form-control" name="clave" placeholder="Clave" required>
		<span class="glyphicon glyphicon-lock form-control-feedback"></span>

		@if ($errors->has('clave'))
			<span class="help-block">
				<strong>{{ $errors->first('clave') }}</strong>
			</span>
		@endif
	</div>
	<div class="form-group{{ $errors->has('clave_confirmation') ? ' has-error' : '' }} has-feedback">
		<input id="clave_confirmation" type="password" class="form-control" name="clave_confirmation" placeholder="Confirmar clave" required>
		<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
		@if ($errors->has('clave_confirmation'))
			<span class="help-block">
				<strong>{{ $errors->first('clave_confirmation') }}</strong>
			</span>
		@endif
	</div>
      <div class="row">
        <div class="col-xs-8">

        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="{{Url('/login')}}" class="text-center">Ya tiene cuenta? inicie session.</a>
  </div>
  </div>
@endsection
