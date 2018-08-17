<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use \App\Traits\ResponseTrait;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
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
        parent::report($e);
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
        if($e instanceof Illuminate\Session\TokenMismatchException){
            if ($request->ajax() || $request->wantsJson()) {
            
                $res = $this->buildResponse('Redirecting to Login Page','danger',true);
                $res->title = 'Session Expired';
                $res->redirect = true;
                $res->redirectState = 'home';
                return response()->json($res,400);
            }
            return parent::render($request, $e);
        }

        if($e->code == 400){
            if ($request->ajax() || $request->wantsJson()) {
            
                $res = $this->buildResponse('Page Not Found','danger',true);
                $res->title = '404!';
                $res->redirect = true;
                $res->redirectState = 'notfound';
                return response()->json($res,400);
            }
            return parent::render($request, $e);
        }
        
        if($request->ajax() || $request->wantsJson()){
            $res = $this->buildResponse('Please try again.<br/>Error: <em class="font-11">'.$e->getMessage().'</em>','danger',true);
            $res->title = '<h3>503!! Server Error</h3>';
            return response()->json($res,400);
        }
        
        return parent::render($request, $e);
    }
}
