<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);
        if (!app()->environment(['testing']) && in_array($response->getStatusCode(), [500, 503, 404, 403, 419])) {

            $messages = [
                404 => 'Page not found.',
                419 => 'Page expired',
                500 => 'Server error',
                403 => strlen($e->getMessage()) > 0 ? $e->getMessage() : 'Forbidden',

            ];

            $data['code'] = $response->getStatusCode();
            $data['message'] = $messages[$response->getStatusCode()] ?? $e->getMessage();
            return response()->view('errors.errors', compact('data'));
        }

        return $response;
    }
}
