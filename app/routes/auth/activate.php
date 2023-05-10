<?php 
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

$app->get('/activate', function(Request $request, Response $response){
  if(!empty($request->getQueryParams()) ){
    $email = $request->getQueryParams()['email'];
    $identifier = $this->get('hash')->hash($request->getQueryParams()['identifier']);
    $user = $this->get('user')->where('email', $email)->first();
    if(!empty($user)){
      if(!is_null($user->active_hash)){
        if($this->get('hash')->checkHash($user->active_hash, $identifier)){
          $user->update(['active' => true, 'active_hash' => null]);
          $this->get('flash')->addMessage('success', 'Your account has been activated');
          $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('login');
        }
      }else{
        print('<h1>Oops Error! link can be used one time.</h1>');
        exit();
      }
    }else{
      $this->get('flash')->addMessage('error', 'Something wrong your account inactive');
      $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('home');
    }
    return $response->withStatus(302)->withHeader('Location', $url);
  }
  print('<h1>Oops Error! link can not be used.</h1>');
  exit();
})->setName('activate');