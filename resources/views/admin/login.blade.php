@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>

                <div class="panel-body">
		@if (session('status'))
		    <div class="alert alert-success">
		        {{ session('status') }}
		    </div>
		@endif
                    <form class="form-horizontal" method="POST" action="{{ route('admin.login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"  autofocus>

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" >

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('role_id') ? ' has-error' : '' }}">
                            <label for="role_id" class="col-md-4 control-label">Role</label>
                            <div class="col-md-6">
                                <select id="role_id" name="role_id" class="form-control" >
                                    <option value="">--- Select Role ---</option>
                                    @foreach ($roles as $key => $value)
                                    <option value="{{ $value['id'] }}" {{ ( $value['id'] == old('role_id')) ? 'selected' : '' }} >{{ $value['title'] }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('role_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('role_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                                <a class="btn btn-link" href="{{ route('admin.password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                    <br><hr>
                    <center id='social-frm'>
                        <div class="row">
                            <input type="radio" name="social_role" value='1' checked><b>Client</b> &nbsp;&nbsp;
                            <input type="radio" name="social_role" value='2' ><b>Designer</b>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-2 row-block"></div>
                            <div class="col-md-4 row-block">
                                <a  onclick="event.preventDefault(); document.getElementById('google-form').submit();" class="btn btn-lg btn-primary btn-block">
                                    <strong>Login With Google</strong>
                                </a> 
                            </div>
                            <div class="col-md-4 row-block">
                                <a  onclick="event.preventDefault(); document.getElementById('fb-form').submit();" class="btn btn-lg btn-primary btn-block">
                                    <strong>Login With Facebook</strong>
                                </a> 
                            </div>
                            <div class="col-md-2 row-block"></div>
                        </div>
                        <form id="google-form" action="{{ url('admin/login/google') }}" method="POST" style="display: none;">
                            <input type="hidden" id="hidden_role_id" value ='1' name="hidden_role_id">
                            {{ csrf_field() }}
                        </form>
                        <form id="fb-form" action="{{ url('admin/login/facebook') }}" method="POST" style="display: none;">
                            <input type="hidden" id="hidden_role_id_fb" value ='1' name="hidden_role_id_fb">
                            {{ csrf_field() }}
                        </form>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
