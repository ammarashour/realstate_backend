<?php

namespace Modules\Installer\Middleware;

use Closure;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Installer;
use Modules\Core\Entities\DomainChange;
use Modules\Core\Entities\Project;
use Redirect;

class canInstall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $domainTable = Schema::hasTable('psx_domain_changes');
        if(!$domainTable){
            abort(404);
        }
        if ($this->alreadyInstalled()) {

            $installedRedirect = config('installer.installedAlreadyAction');



            switch ($installedRedirect) {

                case 'route':
                    $routeName = config('installer.installed.redirectOptions.route.name');
                    $data = config('installer.installed.redirectOptions.route.message');

                    return redirect()->route($routeName)->with(['data' => $data]);
                    break;

                case 'abort':
                    abort(config('installer.installed.redirectOptions.abort.type'));
                    break;

                case 'dump':
                    $dump = config('installer.installed.redirectOptions.dump.data');
                    break;

                case '404':
                case 'default':
                default:
                    abort(404);
                    break;
            }
        }


            return $next($request);

    }

    /**
     * If application is already installed.
     *
     * @return bool
     */
    public function alreadyInstalled()
    {
        $installedFile = file_exists(storage_path('installed'));

        $isInstalled = "";
        $current_domain = config("app.base_domain");
        $current_subFolder = config("app.dir");
        $domainTable = Schema::hasTable('psx_domain_changes');
        if(!$domainTable){
            return true;
        }
        $previous_domain = DomainChange::first();
        $installerTable = Schema::hasTable('psx_installer');

        if($installerTable){
            $isInstalled = Installer::first() ? Installer::first()->is_installed : '';
        }

        if ($installedFile || !empty($isInstalled)){
            if($current_subFolder != ''){
                if($previous_domain != null && $current_subFolder != $previous_domain->sub_folder){
                    return false;
                }
                if($previous_domain != null && $current_domain != $previous_domain->domain_name){
                    return false;
                }
            }else{
                if($previous_domain != null && $current_subFolder != $previous_domain->sub_folder){
                    return false;
                }
                if($previous_domain != null && $current_domain != $previous_domain->domain_name){
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
//        return file_exists(storage_path('installed'));
    }
}
