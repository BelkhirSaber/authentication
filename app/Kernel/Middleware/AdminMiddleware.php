<?php 

namespace Kernel\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Kernel\Models\User;

class AdminMiddleware  implements MiddlewareInterface{
  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler ): ResponseInterface{
    $response = $handler->handle($request);

    if(isset($_SESSION['user_id'])){
      $user = new User();
      $u = $user->find($_SESSION['user_id']);
      if(!$u->isAdmin()){
        $response = $response->withStatus(302)->withHeader('Location', '/page-not-found');
      }
    }
    return $response;
  }
}
