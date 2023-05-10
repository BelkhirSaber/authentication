<?php

namespace Kernel\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Headers;

class RedirectAuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        // Check if the user is authenticated
        if (isset($_SESSION['user_id'])) {
            // Redirect the user to the profile page
            return new \Slim\Psr7\Response(302, new Headers(['Location' => '/']));
        }
        return $response;
    }
}
