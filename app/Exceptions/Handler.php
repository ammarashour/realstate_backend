<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            // return redirect()->route('showlanding.index');
        });
    }

    public function render($request, Throwable $exception)
    {
        // Catch database query exceptions
        if ($exception instanceof QueryException) {
            // Custom logic to handle database errors
            // dd($exception->getMessage(), $exception->getLine(), $exception->getFile());
            return response()->view('errors.database', [], 500);
        }

        if($exception->getMessage() == "Call to a member function prepare() on null"){
            return response()->view('errors.error', [], 500);
        }

        if ($exception instanceof PermissionDeniedException) {
            return $exception->getRedirectView();
        }

        return parent::render($request, $exception);
    }

}
