<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Kernel\Middleware\AdminMiddleware;

$app->get('/all', function(Request $request, Response $response){
  
  return $this->get('view')->render($response, 'user/allUser.php', [
    'auth' => $this->get('auth'),
  ]);
})->setName('all_user')->add(AdminMiddleware::class);
