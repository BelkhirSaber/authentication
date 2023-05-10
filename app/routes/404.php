<?php 

use Psr\Http\Message\ServerRequestInterface as Request ;
use Psr\Http\Message\ResponseInterface as Response ;


$app->get('/page-not-found', function(Request $request, Response $response){

  if(empty($request->getAttribute('Referer')))
    return $response->withStatus(302)->withHeader('Location', '/');

  return $this->get('view')->render($response, '404.php', [
    'auth' => $this->get('auth'),
    'referer' => $request->getAttribute('referer') ?: false
  ]);
})->setName('page.not.found');