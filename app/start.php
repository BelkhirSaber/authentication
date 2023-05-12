<?php
session_cache_limiter(false);
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use Slim\Views\Twig;
use Noodlehaus\Config;
use RandomLib\Factory as RandomLib;
use Kernel\Models\User;
use Kernel\Mail\Mailer;
use Kernel\Helpers\Hash;
use Kernel\Helpers\Validator;
use Slim\Flash\Messages;
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;
use PHPMailer\PHPMailer\PHPMailer;
use Kernel\Middleware\CsrfMiddleware;
use Kernel\Middleware\AuthMiddleware;
use Kernel\Middleware\RecaptchaMiddleware;
use Kernel\Middleware\NotFoundMiddleware;



// Initialize error reporting
ini_set('display_errors', 'On');
// Define inc rout
define('INC_ROUT', dirname(__DIR__) );
// Autoload vendor class
require_once INC_ROUT . '/vendor/autoload.php';
// Create config file
$config_file = INC_ROUT . '/app/config/'. file_get_contents(INC_ROUT . '/mode.php') . '.php';
// $config = Config::load($config_file);
$storage = [];
// $container =  new Container([
//   'config' => Config::load($config_file),
//   'user' => new User,
//   'flash' => new Messages($storage),
//   'validator' => new Validator,
// ]);

$container = new Container();


AppFactory::setContainer($container);
// Create instance from slim 
$app = AppFactory::create();

$container->set('config', function() use($config_file){
  return Config::load($config_file);
});

$container->set('user', function() {
  return new User;
});

$container->set('flash', function() use($storage){
  return new Messages($storage);
});

$container->set('validator', function() use($config_file){
  return new Validator;
});

$container->set('hash', function() use($app){
  return new Hash($app->getContainer()->get('config'));
});

$container->set('mail', function() use ($app) {
  $mailer = new PHPMailer(true);
  $mailer->Host = $app->getContainer()->get('config')->get('mail.gmail.host');
  $mailer->SMTPAuth = $app->getContainer()->get('config')->get('mail.gmail.smtp_auth');
  $mailer->SMTPSecure = $app->getContainer()->get('config')->get('mail.gmail.smtp_secure');
  $mailer->Port = $app->getContainer()->get('config')->get('mail.gmail.port');
  $mailer->Username = $app->getContainer()->get('config')->get('mail.gmail.username');
  $mailer->Password = $app->getContainer()->get('config')->get('mail.gmail.password');
  $mailer->setFrom($app->getContainer()->get('config')->get('mail.gmail.username'));
  $mailer->isHTML($app->getContainer()->get('config')->get('mail.gmail.html'));
  $mailer->isSMTP();

  return new Mailer($app->getContainer()->get('view'), $mailer);
});

$container->set('randomLib', function(){
  $factory = new RandomLib();
  return $factory->getMediumStrengthGenerator();
});

$container->set('view', function(){
  return Twig::create([INC_ROUT . '/app/views', INC_ROUT . '/app/views/templates'], ['cache' => false]); 
});

// If user is authenticated set auth user data to container
$container->set('auth', function() use($app) {
  return isset($_SESSION['user_id']) ? $app->getContainer()->get('user')->find($_SESSION['user_id']): false;
});

$app->getContainer()->get('view')->offsetSet('baseUrl', $app->getContainer()->get('config')->get('app.url'));

$app->add(TwigMiddleware::createFromContainer($app));



// add flash message
$app->add(function($request, $next){
  // start php session
  if(session_status() !== PHP_SESSION_ACTIVE){ session_start();}
  $this->get('flash')->__construct($_SESSION);
  return $next->handle($request);
});

// Custom middleware
// $app->add(NotFoundMiddleware::class);

$app->add(RecaptchaMiddleware::class);

$authMiddleware = new AuthMiddleware($app);
$app->add($authMiddleware);

$csrfMiddleware = new CsrfMiddleware($app);
$app->add($csrfMiddleware);

// Parse json, form data and xml
$app->addBodyParsingMiddleware();
// Add the slim built-in routing middleware
$app->addRoutingMiddleware();
// Handle exceptions
$app->addErrorMiddleware(true, true, true);

// Database Connection
require 'database.php';
// App route
require 'route.php';