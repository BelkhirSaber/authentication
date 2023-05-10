<?php 
namespace Kernel\Mail;
use Slim\Psr7\Response;

class Mailer{


  protected $mailer;
  protected $view;

  public function __construct($view, $mailer){
    $this->view = $view;
    $this->mailer = $mailer;
  }

  public function send($response, $template, $data, $callback){
    $message = new Message($this->mailer);
    $message->body($this->view->render($response, $template, $data)->getBody()->__toString());
    call_user_func($callback, $message);
    $this->mailer->send();
  }
}