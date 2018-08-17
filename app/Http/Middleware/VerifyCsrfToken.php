<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;

use Closure;

class VerifyCsrfToken extends BaseVerifier
{
    use \App\Traits\ResponseTrait;
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/login','/logout','/local/*','/mobile/*'
    ];
    
   
}
