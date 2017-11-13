<?php

namespace Glagol\Bridge\Lumen\Exceptions;

use Exception;
use function Glagol\SourceMap\load_map_from_generated_source;
use Glagol\SourceMap\SourceMap;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        // TODO in future we'll report
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $response = [
            'message' => 'Sorry, something went wrong'
        ];

        if (env('APP_DEBUG', config('app.debug', false)))
        {
            $response['error'] = $exception->getMessage();

            $response['trace'] = to_glagol_trace_from_exception($exception);

            if ($this->showOriginalException($request)) {
                $response['php_exception'] = [
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'file' => $exception->getFile(),
                    'trace' => $exception->getTrace()
                ];
            }
        }

        $status = 500;

        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();
        }

        return response()->json($response, $status);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function showOriginalException($request): bool
    {
        return strtolower($request->header('Show-Php-Debug', 'no')) === 'yes';
    }
}
