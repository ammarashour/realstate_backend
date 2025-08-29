@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.permissions.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-key fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.permissions.title') }}
@endsection

@section('container')

    <ul class="list">
        @foreach($permissions['permissions'] as $permission)
        <li class="list__item list__item--permissions {{ $permission['isSet'] ? 'success' : 'error' }}">
            {{ $permission['folder'] }}
            <span>
                {{ $permission['permission'] }}
                <i class="fa fa-fw fa-{{ $permission['isSet'] ? 'check-circle' : 'times-circle' }}"></i>
            </span>
        </li>
        @endforeach
    </ul>
    <div class="buttons">
        @if ( isset($permissions['errors']))
            <a href="{{ route('LaravelInstaller::permissions') }}" class="button" style="background-color: #ef4444;">
                {{ trans('installer_messages.try_again') }}
            </a>
        @else
            <a href="{{ route('LaravelInstaller::environment') }}" class="button">
                {{ trans('installer_messages.permissions.next') }}
                <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
            </a>
        @endif
    </div>

@endsection
