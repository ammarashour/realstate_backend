@extends('vendor.installer.layouts.master-update')

@section('title', trans('installer_messages.updater.addNewLangString.title'))
@section('container')
    <p class="paragraph text-center">
    	{{ trans('installer_messages.updater.addNewLangString.message') }}
    </p>
    <div class="" style="margin-bottom: 5px">
        <form action="{{ route('LaravelUpdater::addNewLangString') }}" enctype="multipart/form-data" method="post" style="display: flex; justify-content: space-between;">
            @csrf
            <div class="">
                <label for="">{{ trans('import_lang_csv_zip_file') }}</label>
                <input type="file" name="csvFileZip">
            </div>
            <div class="buttons">
                <button class="button" style="font-size:12px;">{{ trans('import') }}</button>
            </div>
        </form>
    </div>
    <div class="buttons">
        <a href="{{ route('LaravelUpdater::addNewFeLangString') }}" class="button">{{ trans('installer_messages.next') }}</a>
    </div>
@stop
