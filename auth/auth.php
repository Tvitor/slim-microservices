
<?php

$app->get('/auth', function (Request $request, Response $response) use ($app) {
    return $response->withJson(["status" => "Autenticado!"], 200)
        ->withHeader('Content-type', 'application/json');   
});