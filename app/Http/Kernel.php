<?php

namespace App\Http;

use App\Http\Middleware\AdminMobileToken;
use App\Http\Middleware\BuilderToken;
use App\Http\Middleware\CheckAuthUser;
use App\Http\Middleware\CheckDashboardPermission;
use App\Http\Middleware\IsFeSettingEnable;
use App\Http\Middleware\UploadSetting;
use App\Http\Middleware\CheckSupportedApiVersion;
use App\Http\Middleware\DeliboyMobileToken;
// use App\Http\Middleware\HandleUpdaterData;
use App\Http\Middleware\SystemCode2;
use App\Http\Middleware\isInstalled;
use App\Http\Middleware\UserAndDeliboyMobileToken;
use App\Http\Middleware\CheckUser;
// use App\Http\Middleware\handleTimeout;
use App\Http\Middleware\UserWebsiteToken;
use App\Http\Middleware\UserMobileToken;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsEditor;
use App\Http\Middleware\VendorAcces;
use App\Http\Middleware\isVendorSettingOn;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Modules\Installer\Middleware\canInstall;
use Modules\Installer\Middleware\canUpdate;

// use Illuminate\Console\Scheduling\Schedule;
// use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Laravel\Jetstream\Http\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\SetLocale::class,
        ],

        'api' => [
             \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        // 'verified' => \App\Http\Middleware\VerifyUserEmailPhone::class,
        'isAdmin' => IsAdmin::class,
        'isEditor' => IsEditor::class,
        'hasUser' => CheckUser::class,
        'checkAuthUserForApi' => CheckAuthUser::class,
        'isAdminMobileToken' => AdminMobileToken::class,
        'isUserMobileToken' => UserMobileToken::class,
        'isUserWebsiteToken' => UserWebsiteToken::class,
        'isUserAndDeliboyMobileToken' => UserAndDeliboyMobileToken::class,
        'isDeliboyMobileToken' => DeliboyMobileToken::class,
        'isBuilderToken' => BuilderToken::class,
        'checkSupportedApiVersion' => CheckSupportedApiVersion::class,
        'install' => CanInstall::class,
        'update' => CanUpdate::class,
        'system' => SystemCode2::class,
        'isInstall' => isInstalled::class,
        // 'checkUpdater' => HandleUpdaterData::class,
        'checkDashboardPermission' => CheckDashboardPermission::class,
        'isFeSettingEnable' => IsFeSettingEnable::class,
        'uploadSetting' => UploadSetting::class,
        'vendorAccess' => VendorAcces::class,
        'isVendorSettingOn' => isVendorSettingOn::class
        // 'handleTimeout' => handleTimeout::class
    ];

    protected $commands = [
        \App\Console\Commands\GenerateSitemap::class,
    ];

    // protected function schedule(Schedule $schedule)
    // {
    //     $schedule->command('generate:sitemap')->daily();
    // }

    // /**
    //  * Register the commands for the application.
    //  *
    //  * @return void
    //  */
    // protected function commands()
    // {
    //     $this->load(__DIR__.'/Commands');

    //     require base_path('routes/console.php');
    // }
}

