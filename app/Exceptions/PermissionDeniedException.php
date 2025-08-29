<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class PermissionDeniedException extends Exception
{
    protected $redirectView;

    public function __construct($redirectView)
    {
        $this->redirectView = $redirectView;
        parent::__construct('Permission denied');
    }

    public function getRedirectView(): RedirectResponse
    {
        return $this->redirectView;
    }
}