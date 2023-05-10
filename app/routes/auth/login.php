<?php

use Slim\Routing\RouteContext;
use Kernel\Middleware\RedirectAuthMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;



$app->get('/login', function(Request $request, Response $response){
  
  $json = array_key_exists('error', $this->get('flash')->getMessages()) ? 
  json_decode($this->get('flash')->getFirstMessage('error'), true) : "";

  $errors = is_null($json) ? $this->get('flash')->getFirstMessage('error') : "";
  $errors = is_array($json) ? $json['errors'] : "";
  $formData = is_array($json) ? $json['request'] : "";

  $success = array_key_exists('success', $this->get('flash')->getMessages()) ? 
  $this->get('flash')->getFirstMessage('success') : false;


  return $this->get('view')->render($response, 'auth/login.php', [
    'errors' => $errors,
    'request' => $formData,
    'flash' =>[
      'success' => $success,
      'error' => $this->get('flash')->getFirstMessage('flash_error')
      ]
  ]);
})->setName('login')->add(RedirectAuthMiddleware::class);




$app->post('/login', function(Request $request, Response $response){
  $data = []; $redirect = 'login';

  if(array_key_exists('identifier', $request->getParsedBody()) && array_key_exists('password', $request->getParsedBody())){
    $rules= [ 'identifier' => 'required|identifier', 'password' => 'required' ];

    $messages = ['identifier.required' => 'identifier is required',
      'identifier.identifier' => 'identifier is invalid "please enter valid username or email"',
      'password.required' => 'password is required' ];
    $identifier = trim($request->getParsedBody()['identifier']);
    $password = trim($request->getParsedBody()['password']);

    $validator = $this->get('validator');
    $validator->make(['identifier' => $identifier, 'password' => $password], $rules, $messages);
    
    if($validator->fails()){
      $data = ['errors' => $validator->errors(), 'request' => $request->getParsedBody()];
      $this->get('flash')->addMessage('error', json_encode($data));
    }else{
      $u = $this->get('user')->where('active', true)->where(function($query) use($identifier) {
        return $query->where('email', $identifier)->orWhere('username', $identifier);
      })->first();

      if(!empty($u) && $this->get('hash')->passwordCheck($password, $u->password)){
        $_SESSION[$this->get('config')->get('auth.session')] = $u->id;
        if($request->getParsedBody()['remember_me'] == "on"){
          $remember_identifier = $this->get('randomLib')->generateString(128);
          $remember_token = $this->get('randomLib')->generateString(128);
        
          $u->updateRememberCredentials($remember_identifier, $this->get('hash')->hash($remember_token));

          $cookie_name = $this->get('config')->get('auth.remember');
          $cookie_value = base64_encode($remember_identifier . "___" . $remember_token);
          $cookie_expires = gmdate("M d Y H:i:s", time() + ((60*60*24) * 7));
          $cookie_options = "SameSite=Strict;HttpOnly=true;Secure=true";
          
          $cookie = $cookie_name ."=". $cookie_value . ";expires=" . $cookie_expires . ";path=/;" . $cookie_options;
          $response = $response->withAddedHeader('Set-Cookie', $cookie);
        }
        $data = ['username' => $u->username];
        $redirect = 'profile';
      }else{
        $data = ['errors' => ['fails' => 'identifier or password is invalid'], 'request' => $request->getParsedBody()];
        $this->get('flash')->addMessage('error', json_encode($data));
        $this->get('flash')->addMessage('flash_error', 'Please check your email and active your account');
      }
    }
  }else{
    $this->get('flash')->addMessage('error', 'please fill the input fields');
  }

  $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor($redirect, $data);
  return $response->withStatus(302)->withHeader("Location", $url);

})->setName('login.post');