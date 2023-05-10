<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;

$app->get('/reset-password', function(Request $request, Response $response){

  if($request->getAttribute('Referer') === ""){
    return $response->withStatus(302)->withHeader('Location', '/');
  }

  $email = filter_var(trim($request->getQueryParams()['email']), FILTER_SANITIZE_EMAIL);
  $user = $this->get('user')->where('email', $email)->first();


  if(is_null($user->recover_hash)){
    return $response->withStatus(302)->withHeader('Location', '/');
  }

  return $this->get('view')->render($response, 'auth/password/reset_password.php', [
    'errors' => json_decode($this->get('flash')->getFirstMessage('fails'), true)?:false,
    'query' => "?email=".$email."&token=". urlencode($request->getQueryParams()['token'])
  ]);
})->setName('password.reset');


$app->post('/reset-password', function(Request $request, Response $response){

  $email = filter_var(trim($request->getQueryParams()['email']), FILTER_SANITIZE_EMAIL);
  $recover_hash = $this->get('hash')->hash($request->getQueryParams()['token']);
  $user = $this->get('user')->where('email', $email)->first();

  if(!$user){
    $this->get('flash')->addMessage('error', 'User not found');
    return $response->withStatus(302)->withHeader('Location', '/');
  }else{
    if(!$this->get('hash')->checkHash(($user->recover_hash?:''), $recover_hash)){
      $this->get('flash')->addMessage('error', 'Violation error');
      return $response->withStatus(302)->withHeader('Location', '/');
    }else{
      $data = [
        'password' => $request->getParsedBody()['new_password'],
        'confirm_password' => $request->getParsedBody()['confirm_new_password'],
      ];
      $rules = [
        'password' => 'required|strong|min:8',
        'confirm_password' => 'required|matches(password)',
      ];

      $messages = [
        'password.required' => 'password is required',
        'password.strong' => 'enter a strong password',
        'password.min' => 'password min length 8 character',
        'confirm_password.required' => 'required|matches(password)',
        'confirm_password.matches' => 'confirm password has be match to password',
      ];


      $validator = $this->get('validator');
      $validator->make($data, $rules, $messages);
      if($validator->fails()){  
        $this->get('flash')->addMessage('fails', json_encode($validator->errors()));
        $url =  RouteContext::fromRequest($request)->getRouteParser()->urlFor('password.reset', [], ['email' => $email, 'token' => $request->getQueryParams()['token']]);
        return $response->withStatus(302)->withHeader('Location', $url);
      }else{

        // Reset user password
        $user->update([
          'password' => $this->get('hash')->password($request->getParsedBody()['new_password']),
          'recover_hash' => null,
        ]);

        // Send mail notification for user
        $this->get('mail')->send($response, 'email/auth/password/confirm.php', [], function($message) use($user) {
          $message->to($user->email);
          $message->subject('Your password reset');
        });

        $this->get('flash')->addMessage('success', 'password changed successfully');
        $response = $response->withStatus(302)->withHeader('Location', '/');
      }
    }
  }

  return $response;

})->setName('password.reset.post');