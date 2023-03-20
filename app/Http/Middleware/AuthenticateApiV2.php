<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;

class AuthenticateApiV2 extends Authenticate
{
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = ['api_v2'];
        }

        return parent::authenticate($request, $guards);
    }
}
?>