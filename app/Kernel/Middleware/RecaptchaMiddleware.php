<?php

namespace Kernel\Middleware;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;


class RecaptchaMiddleware implements MiddlewareInterface{
  public function  process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface{

    $response = $handler->handle($request);

    if(in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])){
      $captchaResponse = $request->getParsedBody()['recaptcha-response'];
      
      $secretKey = '6LeC2-klAAAAAHxnRthNt4MO7R5DIzpgnuVchbNk';
      $verifySite = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$captchaResponse);
      $responseData = json_decode($verifySite);
      
      if(!$responseData->success){
        // fail the verification captcha
        $response = $response->withStatus(302)->withHeader('Location', '/login');
      }   
    }

    return $response;
  }
}