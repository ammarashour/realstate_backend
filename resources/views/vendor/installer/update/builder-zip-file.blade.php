@extends('vendor.installer.layouts.master-update')

@section('title', trans('installer_messages.updater.builderZipFile.title'))
@section('container')
    <p class="paragraph text-center">
    	{{ trans('installer_messages.updater.builderZipFile.message') }}
    </p>
    <div class="" style="margin-bottom: 5px">
        <form action="{{ route('LaravelUpdater::builderZipFile') }}" enctype="multipart/form-data" method="post" style="display: flex; justify-content: space-between;">
            @csrf
            <div class="">
                <label for="">{{ trans('import_builder_exported_zip') }}</label>
                <input type="file" name="zipFile">
            </div>
            <div class="buttons">
                <button class="button" style="font-size:12px;">{{ trans('import') }}</button>
            </div>
        </form>
    </div>
    @if(!empty($updaterData))
    <div class="buttons">
        <a href="{{ route('table.index') }}" class="button">{{ trans('installer_messages.updater.final.exit') }}</a>
    </div>
    @endif
@stop
