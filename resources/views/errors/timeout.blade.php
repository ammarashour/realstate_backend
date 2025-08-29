<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Insufficient Execution Time</title>

        <link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-16x16.png') }}" sizes="16x16"/>
        <link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-32x32.png') }}" sizes="32x32"/>
        <link rel="icon" type="image/png" href="{{ asset('installer/img/favicon/favicon-96x96.png') }}" sizes="96x96"/>
        <link href="{{ asset('installer/css/style.css') }}" rel="stylesheet"/>

        @yield('style')
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body>
        <div class="master">
            <div class="box">
                <div class="header">

                    <i class="fa fa-fw fa-solid fa-exclamation-triangle" style="color: red; font-size: 40px;" aria-hidden="true"></i>

                </div>

                <div class="main">
                    <div class="" style="text-align: center;">
                        <p style="font-size: 13px; font-weight: bold; color:red;">
                            {{ trans("Your server's current execution time is set to ") . ini_get('max_execution_time') . trans(', which is insufficient for the version update process. To proceed, you may need to increase the execution time to 30000 in the php.ini configuration file on your server.') }}
                        </p>

                        <p>
                            For more details, please refer to the
                            <a href="https://doc.clickup.com/24312566/p/h/q5yqp-200698/d2fb39731b23e27" style="text-decoration: underline;" target="_blank">
                                documentation
                            </a>
                        </p>

                    </div>

                    <p class="text-center" style="padding-top: 13px;">
                        <a href="{{ route('NextLaravelUpdater::welcome') }}" class="button">
                            {{ trans('check_again_btn') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
