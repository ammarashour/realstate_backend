@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.requirements.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-shield fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.purchased_code.title') }}
@endsection

@section('container')
    <form id="myForm" action="{{ route('LaravelInstaller::purchasedCodeStore') }}" method="post">
        @csrf
        <div class="form-group {{ $errors->has('backend_url') ? ' has-error ' : '' }}">
            <label for="backend_url">{{ trans('installer_messages.environment.wizard.form.base_domain_label') }}</label>
            <input type="url" name="backend_url" id="backend_url" value="{{ old('backend_url', $project->project_url) }}" placeholder="Enter Backend Url" />
            @if ($errors->has('backend_url'))
                <span class="error-block">
                <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                {{ $errors->first('backend_url') }}
            </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('purchased_code') || !empty(session('purchased_code')) ? ' has-error ' : '' }}">
            <label for="purchased_code">{{ trans('installer_messages.purchased_code.purchased_code') }}</label>
            <input type="text" name="purchased_code" id="purchased_code" value="{{ old('purchased_code', $project->project_code) }}" placeholder="Enter Purchased Code" />
            @if ($errors->has('purchased_code') || !empty(session('purchased_code')))
                <span class="error-block">
                <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                @if($errors->has('purchased_code'))
                    {{ $errors->first('purchased_code') }}
                @endif
                @if(!empty(session('purchased_code')))
                    {{ session('purchased_code') }}
                @endif
            </span>
            @endif

        </div>
        <div class="buttons">
            @if(count($errors) > 0)
                <button type="submit" class="button-btn" style="background-color: #ef4444;">
                    {{ trans('installer_messages.try_again') }}
                </button>
            @else
                <button type="submit" class="button-btn">
                    {{ trans('installer_messages.purchased_code.update_conf_btn') }}
                    <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                </button>
            @endif
        </div>
    </form>

@endsection

@section("scripts")
    <script>
        // Intercept form submission
        document.getElementById('myForm').addEventListener('submit', function(event) {
            // Get input value
            var url = document.getElementById('backend_url').value;
            if (url.endsWith('/')) {
                // Remove the last character (which should be the slash)
                url = url.slice(0, -1);
            }

            document.getElementById('backend_url').value = url;

            // Submit the form programmatically
            this.submit();
        });
    </script>
@endsection
