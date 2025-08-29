<?php

namespace App\Http\Controllers;

use App\Config\ps_config;
use App\Exceptions\PermissionDeniedException;
use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Exception;
use Illuminate\Routing\Controller;

class PsController extends Controller
{
    protected $permissionService;
    public function __construct()
    {
        $this->permissionService = app(PermissionServiceInterface::class);
    }

    function handlePermissionWithModel($model = null, $ability = null, $redirectPath = ps_config::redirectPathForNoPermission, $msg = null) {
        assert($this->permissionService !== null, "Child class must call parent constructor.");

        $redirectView = $this->permissionService->checkingForPermissionWithModel($ability, $model, $redirectPath, $msg);

        if (!empty($redirectView)){
            throw new PermissionDeniedException($redirectView);
        }
    }

    function handlePermissionWithoutModel($moduleId = null, $permissionId = null, $loginUserId, $redirectPath = ps_config::redirectPathForNoPermission, $msg = null) {
        assert($this->permissionService !== null, "Child class must call parent constructor.");

        $redirectView = $this->permissionService->checkingForPermissionWithoutModel($moduleId, $permissionId, $loginUserId, $redirectPath, $msg);

        if (!empty($redirectView)){
            throw new PermissionDeniedException($redirectView);
        }
    }

    function handleVendorPermission($moduleId, $permissionId, $vendorId, $loginUserId = null, $redirectPath = ps_config::redirectPathForNoPermission, $msg = null) {
        assert($this->permissionService !== null, "Child class must call parent constructor.");

        $redirectView = $this->permissionService->checkingForVendorPermission($moduleId, $permissionId, $vendorId, $loginUserId, $redirectPath, $msg);

        if (!empty($redirectView)){
            throw new PermissionDeniedException($redirectView);
        }
    }



}
