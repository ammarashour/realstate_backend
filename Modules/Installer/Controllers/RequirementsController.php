<?php

namespace Modules\Installer\Controllers;

use App\Mail\TestingMail;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Installer\Helpers\RequirementsChecker;

class RequirementsController extends Controller
{
    /**
     * @var RequirementsChecker
     */
    protected $requirements;

    /**
     * @param RequirementsChecker $checker
     */
    public function __construct(RequirementsChecker $checker)
    {
        $this->requirements = $checker;
    }

    /**
     * Display the requirements page.
     *
     * @return \Illuminate\View\View
     */
    public function requirements()
    {

//        $details = [
//            'title' => 'Mail from Panacea-Soft',
//            'body' => 'Mail Configuration is finished successfully.'
//        ];
//
//        try {
//            Mail::to('kyawzinlat43021@gmail.com')->send(new TestingMail($details));
//        } catch (\Throwable $e){
//            return "fail";
//        }


        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('installer.core.minPhpVersion')
        );
        $requirements = $this->requirements->check(
            config('installer.requirements')
        );
        return view('vendor.installer.requirements', compact('requirements', 'phpSupportInfo'));
    }
}
