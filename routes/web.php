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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/menu', 'MenuController@index');

//$router->get('/email', 'MailerController@index');
$router->post('/email', 'MailerController@index');
$router->get('/email/dados', 'MailerController@data');

$router->get('/noticias', 'NoticiaController@index');
$router->get('/noticias/{id}', 'NoticiaController@show');
$router->get('/noticia/{id}', 'NoticiaController@show');

$router->get('/paginas/{menu}[/{submenu}]', 'PaginaController@show');
$router->get('/pagina/{menu}[/{submenu}]', 'PaginaController@show');

$router->get('/estatisticas/types', 'EstatisticaController@types');
$router->get('/estatisticas', 'EstatisticaController@index');

$router->get('/anuncios/{tipo}', 'AnuncioController@index');

$router->get('/biblioteca', 'BibliotecaController@index');

$router->get('/fotos', 'FotoController@index');

$router->get('/animais', 'AnimalController@index');
$router->get('/animais/types', 'AnimalController@types');
$router->get('/animais/{id}', 'AnimalController@show');
$router->get('/animal/{id}', 'AnimalController@show');
$router->get('/animais/{id}/print', 'AnimalController@print');
$router->get('/animal/{id}/print', 'AnimalController@print');
$router->get('/animais/{sire}/{dam}/print', 'AnimalController@crossingPrint');
$router->get('/animal/{sire}/{dam}/print', 'AnimalController@crossingPrint');

$router->get('/comunicado/{tipo}', 'ComunicadoController@index');

$router->get('/inspetores', 'InspetorController@index');
$router->get('/criadores', 'CriadorController@index');

$router->get('/pesquisa/{search}', 'PesquisaController@index');

$router->get('/matriz/{type}', 'MatrizController@index');
