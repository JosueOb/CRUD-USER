<?php
namespace App\Controllers;
use App\Models\User;
use Respect\Validation\Validator as v;
use Zend\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController{

    public function getLogin(){
        return $this->renderHTML('login.twig');
    }
    public function postLogin($request){
        $postData = $request->getParsedBody();
        $responseMessage=null;
        //Se valida los campos
        //se especifica el campo y la variable a comparar//se obtiene el primer resultado
        $user = User::where('userEmail', $postData['userEmail'])->first();
        if($user){
            //verifica el dato que se obtiene con el password del objeto/hash
            if(password_verify($postData['userPassword'],$user->userPassword)){
                $_SESSION['userId'] = $user->userId;//se envia a la session el id del usuario
                return new RedirectResponse('/admin');
            }else{
                $responseMessage='Bad Credentials';
            }
        }else{
            $responseMessage='Bad Credentials';
        }
        return $this->renderHTML('login.twig',[
            'responseMessage'=>$responseMessage
        ]);
    }
    public function getLogout(){
        //quitar el user id y redireccionar a login
        unset($_SESSION['userId']);//se elimina un elemento de un arreglo asociativo
        return new RedirectResponse('/login');
    }
}