<?php
namespace App\Controllers;
// use Zend\Diactoros\Response\RedirectResponse;
use App\Models\User;

class UserController extends BaseController{
    public function getAddJobAction($request){
        // var_dump((string) $request->getbody());//entrega una cadena de texto
        // echo '<br>-----------<br>';
        // var_dump($request->getParsedBody());entrega un arreglo asociativo
        
        if($request->getMethod()=='POST'){
            $postData= $request->getParsedBody();
            $user = new User();
            $user->userName = $postData['userName'];
            $user->userLastName = $postData['userLastName'];
            $user->userEmail = $postData['userEmail'];
            $user->userCedula = $postData['userCedula'];
            $user->userPhoto = $postData['userPhoto'];
            $user->userPassword = $postData['userPassword'];
            $user->userStatus = $postData['userStatus'];
            $user->save();
        }
        $users= User::all();
        return $this->renderHTML('addUser.twig',[
            'users'=>$users
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
        $userId = $attribute['userName'];
        var_dump($userId);
        $user = User::find($userId);
        if($request->getMethod()=='POST'){
            $postData= $request->getParsedBody();
            $user->userName = $postData['userName'];
            $user->userLastName = $postData['userLastName'];
            $user->userEmail = $postData['userEmail'];
            $user->userCedula = $postData['userCedula'];
            $user->userPhoto = $postData['userPhoto'];
            $user->userPassword = $postData['userPassword'];
            $user->userStatus = $postData['userStatus'];
            $user->save();
            return $this->redirectResponse('/user/add');
        }
        $users= User::all();
        return $this->renderHTML('updateUser.twig',[
            'user'=>$user,
            'users'=>$users
            ]);
    }
    // public function postUpdateUserAction ($request,$attribute=[]){
    //     $userId = $attribute['userId'];
    //     var_dump($userId);
    //     $user = User::find($userId);
    //     $postData= $request->getParsedBody();
    //     $user->userName = $postData['userName'];
    //     $user->userLastName = $postData['userLastName'];
    //     $user->userEmail = $postData['userEmail'];
    //     $user->userCedula = $postData['userCedula'];
    //     $user->userPhoto = $postData['userPhoto'];
    //     $user->userPassword = $postData['userPassword'];
    //     $user->userStatus = $postData['userStatus'];
    //     $user->save();
    //     return $this->redirectResponse('/user/add');
    // }
}