@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.requirements.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-sign-in fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.user_configuration.title') }}
@endsection

@section('container')
    <small class="text-warning fw-bold">
        <span style="color: red;">*</span>
        {{ trans('installer_messages.user_configuration.note') }}
    </small>
    <form action="{{ route('LaravelInstaller::userConfigurationUpdate') }}" method="post">
        @csrf
        <div class="form-group {{ $errors->has('email') ? ' has-error ' : '' }}">
            <label for="email">{{trans('installer_messages.user_configuration.email')}}</label>
            <input type="text" name="email" id="email" value="{{ old('email') }}" placeholder="{{trans('installer_messages.user_configuration.email_placeholder')}}" />
            @if ($errors->has('email'))
                <span class="error-block">
                <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                {{ $errors->first('email') }}
            </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('password') ? ' has-error ' : '' }}">
            <label for="password">{{trans('installer_messages.user_configuration.password')}}</label>
            <input type="password" name="password" id="password" value="{{ old('password') }}" placeholder="{{trans('installer_messages.user_configuration.password_placeholder')}}" />
            @if ($errors->has('password'))
                <span class="error-block">
                <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                {{ $errors->first('password') }}
            </span>
            @endif
        </div>

        <div class="text-center">
            @if(count($errors) > 0)
                <button type="submit" class="button-btn" style="background-color: #ef4444;">
                    {{ trans('installer_messages.try_again') }}
                </button>
            @else
                <button type="submit" class="button-btn">
                    {{trans('installer_messages.user_configuration.save_credential')}}
                    <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                </button>
            @endif
        </div>
    </form>
@endsection
