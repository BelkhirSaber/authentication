<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/confirm', function (Request $request, Response $response){
  $referer = $request->getHeaderLine('Referer');

  // Check referer route
  if(!strpos($referer, '/change-password') !== false && !strpos($referer, '/confirm') !== false && !strpos($referer, '/forget-password') !== false){
    return $response->withStatus(302)->withHeader('Location', '/');
  }

  return $this->get('view')->render($response, 'auth/password/confirm_password.php',[
    'auth' => $this->get('auth'),
    'errors' => [
      'invalid_confirm_code' => $this->get('flash')->getFirstMessage('invalid_confirm_code') ?: false,
      'error_check' => $this->get('flash')->getFirstMessage('error_check') ?: false,
      'expire_confirm_code' => $this->get('flash')->getFirstMessage('expire_confirm_code') ?: false
    ]
  ]);
})->setName('password.confirm');


$app->post('/confirm', function (Request $request, Response $response){

  // check if user is authenticated
  if($this->get('auth')){
    // Check if confirm code is valid
    if((string)$request->getParsedBody()['confirm_code'] === (string)$_SESSION['request']['code']){

      $user = $this->get('auth');
      $recover_hash = $this->get('hash') ->hash($_SESSION['request']['hash']); 
      if($this->get('hash')->checkHash($user->recover_hash, $recover_hash)){
        // Update user password
        $user->update([
          'password' => $this->get('hash')->password($_SESSION['request']['new_password']),
          'recover_hash' => null
        ]);

        $user->removeRememberCredentials();

        // Send email notification
        $this->get('mail')->send($response, 'email/auth/password/confirm.php', ['auth' => $user], function($messages) use($user) {
          $messages->to($user->email);
          $messages->subject('Notification: your account password changed');
        });

        // Logout user and redirect to login page
        return $response->withStatus(302)->withHeader('Location', '/logout');
      }else{
        $this->get('flash')->addMessage('error_check', 'invalid check params');
      }
    }else{
      $this->get('flash')->addMessage('invalid_confirm_code', 'Confirm code is invalid');
    }

  }else{

    // Get user Data if exist
    $email = $_SESSION['forget']['email'];
    $recover_hash = $_SESSION['forget']['recover_hash'];
    $user = $this->get('user')->where('email', $email)->first();
    $confirm_code = $this->get('hash')->hash($request->getParsedBody()['confirm_code']);

    // Check if confirm code is valid
    if(!$user){
      $this->get('flash')->addMessage('error_check', 'user not found');
    }else{
      if(is_null($user->confirm_code)){
        $this->get('flash')->addMessage('expire confirm code', 'your confirm code is expire please refill all instruction');
        return $response->withStatus(302)->withHeader('Location', '/confirm');
      }else{

        if($this ->get('hash')->checkHash($user->confirm_code, $confirm_code)){
          // Send mail with reset password instruction
          $this->get('mail')->send($response, 'email/auth/password/reset_password.php', ['email' => $email, 'hash' => $recover_hash], function($message) use($user){
            $message->to($user->email);
            $message->subject('Reset password');
          });
  
          // Remove confirm code
          $user->update([
            'confirm_code' => null,
          ]);
          $this->get('flash')->addMessage('success', 'check your email account to continue reset password');
          return $response->withStatus(302)->withHeader('Location', '/');
  
        }else{
          $this->get('flash')->addMessage('invalid_confirm_code', 'Please enter correctly confirm code');
        }

      }
      
    }
  }

  return $response->withStatus(302)->withHeader('Location', '/confirm');
})->setName('password.confirm.post');