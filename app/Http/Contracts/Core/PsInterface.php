<?php

namespace App\Http\Contracts\Core;

interface PsInterface
{
    function checkPermission($ability = null, $model = null, $routeName = null, $msg = null);
}