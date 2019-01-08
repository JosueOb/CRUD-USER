<?php
namespace App\Controllers;
// use Zend\Diactoros\Response\RedirectResponse;
use App\Models\User;
use Respect\Validation\Validator as v;

class UserController extends BaseController{
    public function getAddJobAction($request){
        // var_dump((string) $request->getbody());//entrega una cadena de texto
        // echo '<br>-----------<br>';
        // var_dump($request->getParsedBody());entrega un arreglo asociativo
        
        $responseMessage=null;

        if($request->getMethod()=='POST'){
            $postData= $request->getParsedBody();

            //attribute es de un objeto y key es para un arreglo asociativo
            $userValidator = v::key('userName', v::stringType()->notEmpty())
                              ->key('userLastName', v::stringType()->notEmpty())
                              ->key('userEmail', v::stringType()->notEmpty())
                              ->key('userCedula', v::stringType()->notEmpty())
                              ->key('userPhoto', v::stringType()->notEmpty())
                              ->key('userPassword', v::stringType()->notEmpty())
                              ->key('userStatus', v::stringType()->notEmpty());

            // var_dump($userValidator->validate($postData));
            try {
                $userValidator->assert($postData);

                $user = new User();
                $user->userName = $postData['userName'];
                $user->userLastName = $postData['userLastName'];
                $user->userEmail = $postData['userEmail'];
                $user->userCedula = $postData['userCedula'];
                $user->userPhoto = $postData['userPhoto'];
                $user->userPassword = $postData['userPassword'];
                $user->userStatus = $postData['userStatus'];
                $user->save();

                $responseMessage = 'Saved';
            } catch (\Exception $e) {//exception generica, se puede capturar difenrentes excepciones
                // var_dump($e->getMessage());//se atrapa la excepcion
                $responseMessage=$e->getMessage();
            }

            
        }
        $users= User::all();
        return $this->renderHTML('addUser.twig',[
            'users'=>$users,
            'responseMessage'=> $responseMessage
            ]);
    }
    public function getDeteleUserAction ($request,$attribute=[]){
        // var_dump($request->getAttribute());
        // var_dump($request);
        // var_dump($attribute);
        $userId = $attribute['userId'];
        $user = User::find($userId);
        $user->delete();
        // return new RedirectResponse('/user/add');
        return $this->redirectResponse('/user/add');
    }
    public function getUpdateUserAction ($request,$attribute=[]){
        $responseMessage=null;
        $userId = $attribute['userId'];
        //  var_dump($userId);
        $user = User::find($userId);

        if($request->getMethod()=='POST'){
            
            try {
                $postData= $request->getParsedBody();
            //attribute es de un objeto y key es para un arreglo asociativo
            $userValidator = v::key('userName', v::stringType()->notEmpty())
                                ->key('userLastName', v::stringType()->notEmpty())
                                ->key('userEmail', v::stringType()->notEmpty())
                                ->key('userCedula', v::stringType()->notEmpty())
                                ->key('userPhoto', v::stringType()->notEmpty())
                                ->key('userPassword', v::stringType()->notEmpty())
                                ->key('userStatus', v::stringType()->notEmpty());
                $userValidator->assert($postData);
                $user->userName = $postData['userName'];
                $user->userLastName = $postData['userLastName'];
                $user->userEmail = $postData['userEmail'];
                $user->userCedula = $postData['userCedula'];
                $user->userPhoto = $postData['userPhoto'];
                $user->userPassword = $postData['userPassword'];
                $user->userStatus = $postData['userStatus'];
                $user->save();
                $responseMessage = 'Saved';
                return $this->redirectResponse('/user/add');

            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }
        }
        $users= User::all();
        return $this->renderHTML('updateUser.twig',[
            'user'=>$user,
            'users'=>$users,
            'responseMessage'=>$responseMessage
            ]);
    }
}