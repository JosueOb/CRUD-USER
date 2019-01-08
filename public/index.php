<?php
//INICIALIZANDO ERRORES
ini_set('display_errors',1);
ini_set('display_starup_errors',1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use App\Models\User;


//eloquent
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'rescate_animal',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

//zend-diactoros PSR-7
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

//aura router
$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index','/',[
    'controller'=>'App\Controllers\IndexController',
    'action'=>'getIndexAction'
]);
$map->get('viewUser','/user/add',[
    'controller'=> 'App\Controllers\UserController',
    'action'=>'getAddJobAction'
]);
$map->post('saveUser','/user/add',[
    'controller'=> 'App\Controllers\UserController',
    'action'=>'getAddJobAction'
]);
$map->get('deleteUser','/user/delete/{userId}',[
    'controller'=> 'App\Controllers\UserController',
    'action'=>'getDeteleUserAction'
]);
$map->get('updateUser','/user/update/{userName}',[
    'controller'=> 'App\Controllers\UserController',
    'action'=>'getUpdateUserAction'
]);
$map->post('postUpdateUser','/user/update/{userName}',[
    'controller'=> 'App\Controllers\UserController',
    'action'=>'getUpdateUserAction'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
    echo 'Entrada no válida';
}else{
    // var_dump( $route->attributes);
    // require $route->handler;
    // var_dump($request->getAttribute('userId'));
    $attributeData = $route->attributes;
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $controller= new $controllerName;//crear un objeto del controlador indicado
    // $controller->$actionName($request);//se ejcuta un metodo en base a una cadena
    // if(empty($attributeData)){
    //     // echo 'No se tienen atributos';
    //     $response =  $controller->$actionName($request);
    // }else{
    //     $response =  $controller->$actionName($request,$attributeData);
    //     // echo 'Tienen atributos';
    // }
    $response =  $controller->$actionName($request,$attributeData);
    foreach ($response->getHeaders() as $name => $values) {
        foreach($values as $value){
            header(sprintf('%s: %s', $name,$value),false);
        }
    }
    http_response_code($response->getStatusCode());
    echo($response->getBody());
}