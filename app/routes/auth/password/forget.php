<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Kernel\Middleware\RedirectAuthMiddleware;


$app->get('/forget-password', function(Request $request, Response $response){


  return $this->get('view')->render($response, 'auth/password/forget.php', [
    'errors'=> [
      'user_not_found' => $this->get('flash')->getFirstMessage('user_not_found')?: false
    ],
    'request' => json_decode($this->get('flash')->getFirstMessage('request'), true)?: false
  ]);
})->setName('password.forget')->add(RedirectAuthMiddleware::class);


$app->post('/forget-password', function(Request $request, Response $response){

  if(isset($request->getParsedBody()['user_email'])){
    $email = filter_var(trim($request->getParsedBody()['user_email']), FILTER_VALIDATE_EMAIL);
    $user = $this->get('user')->where('email', $email)->first();
    
    if(!$user){
      $this->get('flash')->addMessage('user_not_found', 'your email not found! please check you enter email');
      $this->get('flash')->addMessage('request', json_encode(['user_email' => $email]));
      $response = $response->withStatus(302)->withHeader('Location', '/forget-password');
    }else{
      $code = $this->get('randomLib')->generateInt(1000_0000, 9999_9999);
      $recover_hash = $this->get('randomLib')->generateString(128);
      $_SESSION['forget'] = ['email' => $email, 'recover_hash' => $recover_hash];
      
      $user->update([
        'recover_hash' => $this->get('hash')->hash($recover_hash),
        'confirm_code' => $this->get('hash')->hash($code),
      ]);

      // Send a email with confirm code
      $this->get('mail')->send($response, 'email/auth/password/forget_password.php', ['confirm_code' => $code], function($message) use($user){
        $message->to($user->email);
        $message->subject('Confirm code for reset password');
      } );
      $response = $response->withStatus(302)->withHeader('Location', '/confirm');
    }
  }
  return $response;
})->setName('password.forget.post');
