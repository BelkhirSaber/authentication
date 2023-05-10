<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Kernel\Middleware\RedirectAuthMiddleware;
use Kernel\Models\UserPermission;


$app->get('/register', function(Request $request, Response $response){
  return $this->get('view')->render($response, 'auth/register.php', []);
})->setName('register')->add(RedirectAuthMiddleware::class);

$app->post('/register', function(Request $request, Response $response){
  // Add user
  $email = filter_var($request->getParsedBody()['email'], FILTER_SANITIZE_EMAIL);
  $username = htmlspecialchars($request->getParsedBody()['username']);
  $hash_password = $this->get('hash')->password($request->getParsedBody()['password']);
  $active_identifier = $this->get('randomLib')->generateString(128);

  $user = $this->get('user')->create([
    'email' => $email,
    'username' => $username,
    'password' => $hash_password,
    'active' => false,
    'active_hash' => $this->get('hash')->hash($active_identifier)]);

  $user->permission()->create(UserPermission::$default);
  
  $this->get('mail')->send($response, 'email/auth/register.php', ['base_url' => $this->get('config')->get('app.url'), 'user' => $user, 'active_identifier' => $active_identifier], function($message) use ($user){
    $message->to($user->email);
    $message->subject('Confirm Your Email Registration');
  });
  
  $this->get('flash')->addMessage('success', "congratulation you have registered! Please check your email and confirm registration");
  $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('login');
  return $response ->withStatus(302)->withHeader('Location', $url);
})->setName('register.post');

$app->post('/check', function(Request $request, Response $response) {
  // check if the fields are what are you waiting for
  $fields = ['username', 'email', 'password', 'password_confirm', 'csrf_token']; $check = true;
  if(count($fields) === count($request->getParsedBody())){
    foreach($fields as $field){
      if(!array_key_exists($field, $request->getParsedBody())){ $check = false; break; }
    }

    // Validate user input
    if($check){
      $json = [];
      $rules=  ['email' => 'required|email|unique:user,email',
        'username' => 'required|username|unique:user,username',
        'password' => 'required|strong|min:8',
        'password_confirm' => 'required|matches(password)'];
      $messages = [ 'email.required' => 'email is required',
        'email.email' => 'email is invalid',
        'email.unique' => 'email is already exists',
        'username.required' => 'username is required',
        'username.username' => 'username is invalid "nospace only accept \'_\' or \'-\' "',
        'username.unique' => 'username is already exists',
        'password.required' => 'password is required',
        'password.strong' => 'password is not strong',
        'password.min' => 'password min length 8 character',
        'password_confirm.required' => 'password_confirm is required',
        'password_confirm.matches' => 'password_confirm is not match to password',];
      $data = array_reduce(array_keys($request->getParsedBody()), function($carry, $key) use($request){
        if($key !== "csrf_token"){ $carry[$key] = $request->getParsedBody()[$key];}
        return $carry;
      });
      $validator = $this->get('validator');
      $validator->make($data, $rules, $messages);
      $json = $validator->fails() ? $validator->errors() : '';
    }
  }else{
    $json = ['invalid_params_number' => "invalid params number"];
  }
  $response->getBody()->write(!empty($json) ? json_encode($json) : "");
  return $response;

})->setName('check');