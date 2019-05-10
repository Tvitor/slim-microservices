<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Book;
require 'bootstrap.php';
/**
 * Lista de todos os livros
 * @request curl -X GET http://localhost:8000/book
 */

require 'routes.php';
$app->run();