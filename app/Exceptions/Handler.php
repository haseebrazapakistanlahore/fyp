<?php

namespace App\Exceptions;

use Throwable; // Make sure to import Throwable
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public function report(Throwable $e)
    {
        parent::report($e); // Change the variable to $e here
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof UnauthorizedHttpException) {
            $preException = $e->getPrevious(); // Change the variable to $e here
            if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status' => 401, 'message' => 'Token Expired']);
            } else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => 401, 'message' => 'Invalid Token']);
            } else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json(['status' => 401, 'message' => 'Token Blacklisted']);
            }
            if ($e->getMessage() === 'Token not provided') {
                return response()->json(['status' => 401, 'message' => 'Token Not Provided']);
            }
            if ($e->getMessage() === 'User not found') {
                return response()->json(['status' => 401, 'message' => 'User Not Found']);
            }
            if ($e->getMessage() === 'A token is required') {
                return response()->json(['status' => 401, 'message' => 'Token is required']);
            }
        }

        return parent::render($request, $e); // Change the variable to $e here
    }
}
