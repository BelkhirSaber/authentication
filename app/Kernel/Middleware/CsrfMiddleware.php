<?php 
namespace Kernel\Middleware;

use Slim\App;
use Exception;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class CsrfMiddleware implements MiddlewareInterface{
  private  $app;
  private $csrf_key;

  public function __construct(App $app){
    $this->app = $app;
    $this->csrf_key = $this->app->getContainer() ->get('config')->get('csrf.key');
  }

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface{  

    if(!isset($_SESSION[$this->csrf_key])){
      $_SESSION[$this->csrf_key] = $this->app->getContainer()->get('hash')->hash(
        $this->app->getContainer()->get('randomLib')->generateString(128)
      );
    }
    
    $token = $_SESSION[$this->csrf_key];

    if(in_array($request->getMethod(), array('POST', 'PUT', 'DELETE'))){
      $submitToken = $request->getParsedBody()[$this->csrf_key] ?: '';
      if(!$this->app->getContainer()->get('hash')->checkHash($token, $submitToken)){
        throw new Exception('CSRF token mismatch');
      }
    }

    $this->app->getContainer()->get('view')->offsetSet('csrf', ['key' => $this->csrf_key, 'token' => $token]);
    $response = $handler->handle($request);
    return $response;
  }

}