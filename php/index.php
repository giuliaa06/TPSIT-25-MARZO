<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/controllers/AlunniController.php';

$app = AppFactory::create();

$app->get('/alunni', "AlunniController:index");
$app->get('/alunni/{id}', "AlunniController:view");
$app->post('/alunni/create', "AlunniController:create");
$app->put('/alunni/update/{id}', "AlunniController:update");
$app->delete('/alunni/destroy/{id}', "AlunniController:destroy");

$app->run();
