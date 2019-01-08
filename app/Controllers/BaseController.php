<?php
namespace App\Controllers;
use Zend\Diactoros\Response\HtmlResponse;//para entregar una respuesta HTML
use Zend\Diactoros\Response\RedirectResponse;
// use \Twig_Loader_Filesystem;
// use \Twig_Environment;

class BaseController{
    protected $templateEngine;

    public function __construct(){
        $loader = new \Twig_Loader_Filesystem('../views');
        $this->templateEngine = new \Twig_Environment($loader, array(
            'cache' => false,
            'debug' => true
        ));;
    }

    public function renderHTML($fileName, $data = []){
        return new HtmlResponse ($this-> templateEngine->render($fileName, $data));
    }
    public function redirectResponse($fileName){
        return new RedirectResponse($fileName);
    }
}