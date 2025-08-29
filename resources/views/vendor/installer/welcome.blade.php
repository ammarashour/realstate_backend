@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.welcome.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-download fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.welcome.title') }}
@endsection

@section('container')
    <p class="text-center">
      {{ trans('installer_messages.welcome.message') }}
    </p>
    <p class="text-center">
        @if(count($errors) > 0)
            <a href="{{ route('LaravelInstaller::purchasedCode') }}" class="button" style="background-color: #ef4444;">
                {{ trans('installer_messages.try_again') }}
            </a>
        @else
            <a href="{{ route('LaravelInstaller::purchasedCode') }}" class="button">
                {{ trans('installer_messages.welcome.start_btn') }}
                <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
            </a>
        @endif
    </p>
@endsection
