<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Auth\Middleware\EnsureEmailIsVerified as Middleware;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;

class VerifyUserEmailPhone extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function __construct(protected BackendSettingServiceInterface $backendSettingService)
    {

    }

    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        $backendSetting = $this->backendSettingService->get();
        if($request->user() && $backendSetting->email_verification_enabled == 1){
            $verifyTypeStr = $request->user()->verify_type;
            $verifyType = explode(",", $verifyTypeStr);

            if(in_array('2',$verifyType) || in_array('4',$verifyType) || in_array('3',$verifyType) || in_array('5',$verifyType)){
            //if verified with google, apple, facebook ,phone

                return $next($request);

            }else
            if($request->user() instanceof MustVerifyEmail &&
                ! $request->user()->hasVerifiedEmail()){
            //esle if not verified with phone and email

                // if($request->user()->user_phone != null){
                // //if has phone

                //     return $request->expectsJson()
                //         ? abort(403, 'Your phone is not verified.')
                //         : Redirect::guest(URL::route('verifyPhone'));
                // }else
                if($request->user()->email != null){
                //if has email

                    return $request->expectsJson()
                        ? abort(403, 'Your email address is not verified.')
                        : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
                }

            }
        }

        // if (! $request->user() ||
        //     ($request->user() instanceof MustVerifyEmail &&
        //     )) {
        //     return $request->expectsJson()
        //             ? abort(403, 'Your email address is not verified.')
        //             : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
        // }

        return $next($request);
    }
}
