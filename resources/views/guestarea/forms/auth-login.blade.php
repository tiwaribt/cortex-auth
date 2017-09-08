{{-- Master Layout --}}
@extends('cortex/foundation::guestarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.login') }}
@stop

{{-- Scripts --}}
@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Guestarea\AuthenticationRequest::class)->selector('#guestarea-auth-login-process') !!}

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endpush

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('guestarea.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/fort::common.login') }}</p>

            {{ Form::open(['url' => route('guestarea.auth.login.process'), 'id' => 'guestarea-auth-login-process']) }}

                <div class="form-group has-feedback{{ $errors->has('loginfield') ? ' has-error' : '' }}">
                    {{ Form::text('loginfield', old('loginfield'), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.loginfield'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                    <span class="fa fa-envelope form-control-feedback"></span>

                    @if ($errors->has('loginfield'))
                        <span class="help-block">{{ $errors->first('loginfield') }}</span>
                    @endif
                </div>
                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password'), 'required' => 'required']) }}
                    <span class="fa fa-key form-control-feedback"></span>

                    @if ($errors->has('password'))
                        <span class="help-block">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label for="remember">
                                <input id="remember" name="remember" type="checkbox" autocomplete="off" value="1" @if(old('remember')) checked @endif> {{ trans('cortex/fort::common.remember_me') }}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="col-xs-4">
                        {{ Form::button('<i class="fa fa-sign-in"></i> '.trans('cortex/fort::common.login'), ['class' => 'btn btn-primary btn-block btn-flat', 'type' => 'submit']) }}
                    </div>
                    <!-- /.col -->
                </div>

            {{ Form::close() }}

            {{ Html::link(route('guestarea.passwordreset.request'), trans('cortex/fort::common.forgot_password')) }}<br />
            {{ Html::link(route('guestarea.auth.register'), trans('cortex/fort::common.register')) }}

        </div>
        <!-- /.login-box-body -->
    </div>

@endsection