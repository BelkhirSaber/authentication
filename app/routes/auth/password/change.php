<?php 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


$app->get('/change-password', function(Request $request, Response $response){
  $json = (array)json_decode($this->get('flash')->getFirstMessage('errors'))?: false;
  $newPasswordErrors = false;
  $confirmNewPasswordErrors = false;

  if(is_array($json)){
    $newPasswordErrors = array_reduce(array_keys($json), function($errors, $key)use($json){
      if(substr($key, 0, strpos($key, '.')) === 'new_password') $errors[$key] = $json[$key];
      return $errors;
    }, []);

    $confirmNewPasswordErrors = array_reduce(array_keys($json), function($errors, $key)use($json){
      if(substr($key, 0, strpos($key, '.')) === 'confirm_new_password') $errors[$key] = $json[$key];
      return $errors;
    }, []);
  }

  return $this->get('view')->render($response, 'auth/password/change_password.php', [
    'auth' => $this->get('auth'),
    'errors' => [
      'invalid_params' => $this->get('flash')->getFirstMessage('invalid_params')?: false,
      'param_not_found' => $this->get('flash')->getFirstMessage('param_not_found')?: false,
      'refresh' => $this->get('flash')->getFirstMessage('refresh')?: false,
      'old_password' => $this->get('flash')->getFirstMessage('old_password')?: false,
      'new_password' => !empty($newPasswordErrors)? $newPasswordErrors : false,
      'confirm_new_password' => !empty($confirmNewPasswordErrors)? $confirmNewPasswordErrors : false,
    ],
    'request' => ''
  ]);
})->setName('password.change');


$app->post('/change-password', function(Request $request, Response $response){
  if(count($request->getParsedBody()) === 5){
    $data = [];
    $rules = [
      'old_password' => 'required',
      'new_password' => 'required|strong|min:8',
      'confirm_new_password' => 'required|matches(new_password)'
    ];
    $messages = [
      'old_password.required' => 'old password is required',
      'new_password.required' => 'new password is required',
      'new_password.strong' => 'write a strong password',
      'new_password.min' => 'new password must be min length 8 character',
      'confirm_new_password.required' => 'confirm new password is required',
      'confirm_new_password.matches' => 'confirm new password must be match to new password'
    ];

    foreach($request->getParsedBody() as $key => $value){
      if(in_array($key, ['old_password', 'new_password', 'confirm_new_password', 'csrf_token', 'recaptcha-response'])){
        if($key !== 'csrf_token' && $key !== 'recaptcha-response') $data[$key] = $value;
      }else{
        $this->get('flash')->addMessage('param_not_found', 'invalid input key');
      }
    }//end foreach

    if(count($data) === 3){
      // Valid input form
      $validator = $this->get('validator');
      $validator->make($data, $rules, $messages);

      if(!$validator->fails()){

        // Check if old password is correct
        if($this->get('hash')->passwordCheck($data['old_password'], $this->get('auth')->password)){
          // Update recover hash
          $active_code = $this->get('randomLib')->generateInt(10000000, 99999999);
          $recover_hash = $this->get('randomLib')->generateString(128);
          $this->get('auth')->updateRecoverHash($this->get('hash')->hash($recover_hash));
          $user = $this->get('auth');

          // Send confirmation email with active code
          $this->get('mail')->send($response, 'email/auth/password/change_password.php', ['code' => $active_code], function ($message) use($user) {
            $message->to($user->email);
            $message->subject('Confirm change password');
          });
          
          $data['code'] = $active_code;
          $data['hash'] = $recover_hash;
          
          // redirect to confirm
          $_SESSION['request'] = $data;
          return $response->withStatus(302)->withHeader('Location', '/confirm');

        } else{
          $this->get('flash')->addMessage('old_password', 'invalid old password');
        }//end innerIf

      }else{
        $this->get('flash')->addMessage('errors', json_encode($validator->errors()));
      }//end if validator->fails()

    }else{
      $this->get('flash')->addMessage('refresh', "Please refresh website");
    }//end if count data
  }else{
    $this->get('flash')->addMessage('invalid_params', "Please enter correctly input form ");
  }//end if count request

  $this->get('flash')->addMessage('request', json_encode($request->getParsedBody()));
  return $response->withStatus(302)->withHeader('Location', '/change-password');
})->setName('password.change.post');