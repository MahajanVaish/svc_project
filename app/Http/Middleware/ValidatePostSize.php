<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom ValidatePostSize that overrides the default 256M limit.
 * This replaces the framework's built-in middleware to handle
 * large diet chart form submissions.
 */
class ValidatePostSize
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $max = $this->getPostMaxSize();

        if ($max > 0 && $request->server('CONTENT_LENGTH') > $max) {
            throw new \Illuminate\Http\Exceptions\PostTooLargeException;
        }

        return $next($request);
    }

    /**
     * Determine the server 'post_max_size' as bytes.
     * We override to use 256M to handle large diet chart payloads.
     */
    protected function getPostMaxSize(): int
    {
        // Force 256MB limit regardless of php.ini setting
        return 256 * 1024 * 1024; // 256M
    }
}
