<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post('/data', function () use ($router) {
    return response()->json(["data"=>"gesang"]);
});
$router->get('/data', function () use ($router) {
   return response()->json(["data"=>"gesang"]);
});
$router->get('/key', function() {
   return \Illuminate\Support\Str::random(32);
});

$router->group(['prefix' => 'whatsapp'], function () use ($router) {
    $router->group(['namespace' => 'Whatsapp\Heandler_stage'], function() use ($router){
       return $router->post("/", "Index@index");
    });
    $router->group(['namespace' => 'Whatsapp\Heandler_stage'], function() use ($router){
       return $router->post("/connect",'Index@onOpen');
    });
    });
