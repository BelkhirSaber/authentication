<?php

namespace Kernel\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Slim\Psr7\Headers;
use Slim\App;

class AuthMiddleware implements MiddlewareInterface
{
    private $app;

    public function __construct(App $app){
        $this->app = $app->getContainer();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // Check if exist remember me cookie
        if(!isset($_SESSION['remember_me']) && !in_array($request->getRequestTarget(), ['/logout'])){
            $this->checkRememberMe($request, $response);
        }
        
        $target = $request->getRequestTarget();
        if(str_contains($target, '?')){
            $target = substr($request->getRequestTarget(), 0, strpos($target, '?'));
        }

        // Check if the user is not authenticated
        if (!isset($_SESSION['user_id']) && !in_array($target, ['/login', '/register', '/check', '/logout', '/forget-password', '/page-not-found', '/confirm', '/reset-password', '/'])) {
            // Redirect the user to the login page
            $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('login');
            $response = new \Slim\Psr7\Response(302, new Headers(['Location' => $url]));
        }
        // Redirect user when exist remember me cookie
        if(isset($_SESSION['remember_me']) && $_SESSION['remember_me'] == true){
            $_SESSION['remember_me'] = false;
            $response = new \Slim\Psr7\Response(302, new Headers(['Location' => $request->getRequestTarget()]));
        }
        return $response;
    }

    public function checkRememberMe($request, $response){
        
        $remember_key = $this->app->get('config')->get('auth.remember');

        if(array_key_exists($remember_key, $request->getCookieParams())){
            
            $credentials = explode('___', base64_decode($request->getCookieParams()[$remember_key]));

            if(empty(trim($request->getCookieParams()[$remember_key])) || count($credentials) !== 2){
                return $response->withStatus(302)->withHeader('Location', 'home');
            }else{
                $identifier = $credentials[0];
                $token = $this->app->get('hash')->hash($credentials[1]);
                $u = $this->app->get('user')->where('remember_identifier', $identifier)->first();

                if(!empty($u)){
                    if($this->app->get('hash')->checkHash($u->remember_token, $token)){
                        $_SESSION[$this->app->get('config')->get('auth.session')] = $u->id;
                        $_SESSION['remember_me'] = true;
                    }else{
                        $u->removeRememberCredentials();
                    }
                }

            }
            
        }

    }
}
