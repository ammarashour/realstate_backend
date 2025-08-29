@extends('vendor.installer.layouts.master-update')

@section('title', trans('installer_messages.updater.final.title'))
@section('container')
    <p class="paragraph text-center">{{ session('message')['message'] }}</p>
    <div class="buttons">
        <a href="{{ route('LaravelUpdater::addNewLangString') }}" class="button">{{ trans('installer_messages.next') }}</a>
    </div>
@stop
