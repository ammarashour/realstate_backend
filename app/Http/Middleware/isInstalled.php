<?php

namespace App\Http\Middleware;

use App\Config\ps_constant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Installer;
use Modules\Core\Entities\DomainChange;

class isInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $installedFile = file_exists(storage_path('installed'));
        $current_domain = config("app.base_domain");
        $current_subFolder = config("app.dir");

        $installerTable = Schema::hasTable('psx_installer');
        $domainTable = Schema::hasTable('psx_domain_changes');
        $previous_domain = '';
        if($domainTable){
            $previous_domain = DomainChange::first();
        }

        /// ----- add slash, www and https at domain .env start ----
        Cache::remember(ps_constant::checkDoaminCacheKey, ps_constant::cache4HrsDuration, function () use ($current_domain) {
            if(!in_array($current_domain, ps_constant::localDomains)){

                // Remove "http://" or "https://" if present
                Log::info('Cache miss, running domain processing...');

                $new_content = preg_replace('#^https?://#', '', $current_domain);

                $isWwwPresent = strpos($current_domain, 'www.');
                $isHttpsPresent = strpos($new_content, 'https://');
                $lastCharacter = substr($current_domain, -1);

                if(!$isWwwPresent || !$isHttpsPresent || $lastCharacter !== '/'){

                    $file_path = base_path(".env");
                    $old_content = $current_domain;
                    $file_content = file_get_contents($file_path);

                    if ($lastCharacter !== '/') {
                        // Define the content to find and replace
                        $new_content = $current_domain."/";
                    }

                    // Add "www." if not present
                    if ($isWwwPresent === false) {
                        $new_content = 'www.' . $new_content;
                    }

                    // Add "https://" if not present
                    if ($isHttpsPresent === false) {
                        $new_content = 'https://' . $new_content;
                    }
                    // Find and replace the content
                    $new_file_content = str_replace($old_content, $new_content, $file_content);

                    // Write the updated content back to the file
                    return file_put_contents($file_path, $new_file_content);
                }

            }
        });
        /// ----- add slash, www and https at domain .env end -----

        if ($installerTable){

            $isInstalled = Installer::first() ? Installer::first()->is_installed : '';
            if (!$installedFile && empty($isInstalled)){
                // dd("here");


                return redirect()->route('LaravelInstaller::welcome');
            }else{
                if(!empty($previous_domain)){
                    if($current_subFolder != ''){
                        if($current_subFolder != $previous_domain->sub_folder){
                            return redirect()->route('LaravelInstaller::welcome');
                        }
                        if($current_domain != $previous_domain->domain_name){
                            return redirect()->route('LaravelInstaller::welcome');
                        }
                    }
                    else{
                        if($current_subFolder != $previous_domain->sub_folder){
                            return redirect()->route('LaravelInstaller::welcome');
                        }
                        if($current_domain != $previous_domain->domain_name){
                            return redirect()->route('LaravelInstaller::welcome');
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
