<?php
require './vendor/autoload.php';
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

//config
$configs = [
    'settings' => [
        'displayErrorDetails' => true,
        'debug' => true,
    ],
];

$container = new \Slim\Container($configs);

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        return $c['response']->withStatus($statusCode)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(["message" => $exception->getMessage()], $statusCode);
    };
};
$isDevMode = true;
/**
 *  entities and Metadata of Doctrine
 */
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src/Models/Entity"), $isDevMode);
/**
 * sqlite  Connection Config
 */
$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
);
/**
 * Entity Manager Instancy
 */
$entityManager = EntityManager::create($conn, $config);
/**
 * Entity manager in container (Entity Manager)
 */
$container['em'] = $entityManager;
$app = new \Slim\App($container);

