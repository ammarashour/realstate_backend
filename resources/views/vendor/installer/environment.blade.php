@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.environment.menu.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-cog fa-fw" aria-hidden="true"></i>
    {!! trans('installer_messages.environment.title') !!}
@endsection

@section('container')

    <p class="text-center">
        {!! trans('installer_messages.environment.desc') !!}
    </p>
    <div class="buttons btn-display-inline" >
        <a href="{{ route('LaravelInstaller::environmentWizard') }}" class="button" style="width:100%">
            <i class="fa fa-sliders fa-fw" aria-hidden="true"></i> {{ trans('installer_messages.environment.wizard-button') }}
        </a>
        {{-- <a href="{{ route('LaravelInstaller::environmentClassic') }}" class="button">
            <i class="fa fa-code fa-fw" aria-hidden="true"></i> {{ trans('installer_messages.environment.classic-button') }}
        </a> --}}
    </div>

@endsection
