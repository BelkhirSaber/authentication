<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
// use Slim\Exception\HttpNotFoundException;

$app->get('/profile/u/{username}', function(Request $request, Response $response, $args) {

  if($this->get('auth')->username !== $args['username']){
    throw  new \Slim\Exception\HttpNotFoundException($request);
  }

  return $this->get('view')->render($response, 'user/profile.php', [
    'auth' => $this->get('auth'),
    'info' => $this->get('auth')->active ? false: "Please active your email account"
  ]);
})->setName('profile');