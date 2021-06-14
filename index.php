<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;
date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once './app/vendor/autoload.php';
require_once './app/models/AccesoDatos.php';
require_once './app/controllers/usuarioController.php';
require_once './app/controllers/hortalizaController.php';
require_once './app/controllers/ventaController.php';
require_once './app/controllers/estadisticas.php';
require './app/mw/MWparaAutentificar.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

$container=$app->getContainer();

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'practicasegundoparcial',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$app->group('/login',function() {

    $this->post('/', \MWparaAutentificar::class . ':VerificarLogin');
  
});

$app->group('/usuario', function () {
 
    $this->get('/', \usuarioController::class . ':TraerTodos');
   
    $this->get('/{id}', \usuarioController::class . ':TraerUno');
  
    $this->post('/', \usuarioController::class . ':CargarUno');
  
    $this->delete('/{id}', \usuarioController::class . ':BorrarUno');
  
    $this->put('/', \usuarioController::class . ':ModificarUno');
       
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/hortaliza', function () {
 
    $this->get('/', \hortalizaController::class . ':TraerTodos');
   
    $this->get('/{id}', \hortalizaController::class . ':TraerUno');
    
    $this->get('/tipo/{tipo}', \hortalizaController::class . ':TraerTodosTipo');
  
    $this->post('/', \hortalizaController::class . ':CargarUno');
  
    $this->delete('/{id}', \hortalizaController::class . ':BorrarUno');
  
    $this->put('/', \hortalizaController::class . ':ModificarUno');
       
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/venta', function () {
 
    $this->get('/', \ventaController::class . ':TraerTodos');
   
    $this->get('/{id}', \ventaController::class . ':TraerUno');
  
    $this->post('/', \ventaController::class . ':CargarUno');
  
    $this->delete('/{id}', \ventaController::class . ':BorrarUno');
  
    $this->put('/', \ventaController::class . ':ModificarUno');
       
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/estadisticas', function () {
 
    $this->get('/{id}', \estadisticas::class . ':TraerVentasEmpleado');
   
    $this->get('/', \estadisticas::class . ':TraerHortalizaMasVendida');
  
       
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->run();
?>