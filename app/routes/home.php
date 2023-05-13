<?php 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Home route
$app->get('/', function(Request $request, Response $response) use($app) {

  return $this->get('view')->render($response, 'home.php', [
    'auth' => $this->get('auth'),
    'flash' => [
      'global' => $this->get('flash')->getFirstMessage('global')?: false,
      'success' => $this->get('flash')->getFirstMessage('success')?: false,
      'error' => $this->get('flash')->getFirstMessage('error')?: false,
    ]
  ]);
})->setName('home');

