<?php

namespace Kernel\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface ;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class NotFoundMiddleware implements MiddlewareInterface{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface {

        $response = $handler->handle($request);
        if ($response->getStatusCode() == 404) {
            $contents = file_get_contents(__DIR__ . '../../views/404.php');
            $response->getBody()->write($contents);
            return $response->withStatus(404);
        }

        return $response;
    }
    // public function __invoke(Request $request, Response $response, $next)
    // {
    //     $response = $next($request, $response);
    //     
    //     return $response;
    // }
}
