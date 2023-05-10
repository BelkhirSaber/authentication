<?php 
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

$app->get('/logout', function(Request $request, Response $response){
  if(array_key_exists($this->get('config')->get('auth.remember'), $request->getCookieParams())){
    $this->get('auth')->removeRememberCredentials();
    $cookie_name = $this->get('config')->get('auth.remember');
    $cookie_expires = 'Thu, 01 Jan 1970 00:00:00 GMT';
    $cookie_path = '/';
    $response = $response->withAddedHeader('Set-Cookie', "$cookie_name=false; expires=$cookie_expires; path=$cookie_path");
  }
  session_unset();
  session_destroy();
  $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('login');
  return $response->withStatus(302)->withHeader('Location', $url);
})->setName('logout');