<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        // Checks if the user has "json" in the Accept header.
        if ($request->wantsJson())
        {
//            We start building the response array which will be the returned JSON data.
            $response = [
                'message' => (string) $e->getMessage(),
                'status'  => 400
            ];
//          Change the message and status code of the exception ie.status = 404, message = Not Found
            if ($e instanceof HttpException)
            {
                $response['message'] = Response::$statusTexts[$e->getStatusCode()];
                $response['status']  = $e->getStatusCode();
            } elseif ($e instanceOf ModelNotFoundException ) {
                $response['message'] = Response::$statusTexts[Response::HTTP_NOT_FOUND];
                $response['status']  = Response::HTTP_NOT_FOUND;
            }
//          If debug mode is enabled, add the exception class and the stack trace
            if ($this->isDebugMode())
            {
                $response['debug'] = [
                    'exception' => get_class($e),
                    'trace'     => $e->getTrace()
                ];
            }
//          Return a JSON response with the assembled data.
            return response()->json(['error' => $response], $response['status']);
        }
        //Skip and call the original method.
        return parent::render($request, $e);
    }

    /**
     * Determine if the application is in debug mode.
     *
     * @return Boolean
     */
    public function isDebugMode()
    {
        return (Boolean) env('APP_DEBUG');
    }
}
