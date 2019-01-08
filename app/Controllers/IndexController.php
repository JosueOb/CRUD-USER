<?php
namespace App\Controllers;

class IndexController extends BaseController{

    public function getIndexAction($request){
        return $this->renderHTML('index.twig');
    }
}