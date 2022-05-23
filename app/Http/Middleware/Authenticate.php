<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            
            $isApiRequest = isApiRequest($request);
            
            if($isApiRequest) {
                $response = array('status' => 400, 'message' => 'You must be logged in to get access', 'data' => []);
                echo json_encode($response);
                exit();
            }
            else
                return route('login');
        }
    }
}
