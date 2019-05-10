<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Book;

$app->get('/auth', function (Request $request, Response $response) use ($app) {
    return $response->withJson(["status" => "Autenticado!"], 200)
        ->withHeader('Content-type', 'application/json');   
});


$app->get('/book', function (Request $request, Response $response) use ($app) {
    $entityManager = $this->get('em');
    $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $books = $booksRepository->findAll();
    $return = $response->withJson($books, 200)
        ->withHeader('Content-type', 'application/json');
    return $return;
});
/**
 * Retornando mais informações do livro informado pelo id
 * @request curl -X GET http://localhost:8000/book/1
 */
$app->get('/book/{id}', function (Request $request, Response $response) use ($app) {
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');
    $entityManager = $this->get('em');
    $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $book = $booksRepository->find($id); 
    /**
     * Verifica se existe um livro com a ID informada
     */
    if (!$book) {
        $logger = $this->get('logger');
        $logger->warning("Book {$id} Not Found");
        throw new \Exception("Book not Found", 404);
    }       
    $return = $response->withJson($book, 200)
        ->withHeader('Content-type', 'application/json');
    return $return;
});
/**
 * Cadastra um novo <Livro></Livro>
 * @request curl -X POST http://localhost:8000/book -H "Content-type: application/json" -d '{"name":"O Oceano no Fim do Caminho", "author":"Neil Gaiman"}'
 */
$app->post('/book', function (Request $request, Response $response) use ($app) {
    $params = (object) $request->getParams();
    /**
     * Pega o Entity Manager do nosso Container
     */
    $entityManager = $this->get('em');
    /**
     * Instância da nossa Entidade preenchida com nossos parametros do post
     */
    $book = (new Book())->setName($params->name)
        ->setAuthor($params->author);
    
    /**
     * Persiste a entidade no banco de dados
     */
    $entityManager->persist($book);
    $entityManager->flush();
    $logger = $this->get('logger');
    $logger->info('Book Created!', $book->getValues());
    $return = $response->withJson($book, 201)
        ->withHeader('Content-type', 'application/json');
    return $return;
});
/**
 * Atualiza os dados de um livro
 * @request curl -X PUT http://localhost:8000/book/14 -H "Content-type: application/json" -d '{"name":"Deuses Americanos", "author":"Neil Gaiman"}'
 */
$app->put('/book/{id}', function (Request $request, Response $response) use ($app) {
    /**
     * Pega o ID do livro informado na URL
     */
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');
    /**
     * Encontra o Livro no Banco
     */ 
    $entityManager = $this->get('em');
    $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $book = $booksRepository->find($id);   
    /**
     * Monolog Logger
     */
    $logger = $this->get('logger');
    /**
     * Verifica se existe um livro com a ID informada
     */
    if (!$book) {
        $logger->warning("Book {$id} Not Found - Impossible to Update");        
        throw new \Exception("Book not Found", 404);
    }   
    /**
     * Atualiza e Persiste o Livro com os parâmetros recebidos no request
     */
    $book->setName($request->getParam('name'))
        ->setAuthor($request->getParam('author'));
    /**
     * Persiste a entidade no banco de dados
     */
    $entityManager->persist($book);
    $entityManager->flush();        
    $logger->info("Book {$id} updated!", $book->getValues());
    $return = $response->withJson($book, 200)
        ->withHeader('Content-type', 'application/json');
    return $return;
});
/**
 * Deleta o livro informado pelo ID
 * @request curl -X DELETE http://localhost:8000/book/3
 */
$app->delete('/book/{id}', function (Request $request, Response $response) use ($app) {
    /**
     * Pega o ID do livro informado na URL
     */
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');
    /**
     * Monolog Logger
     */
    $logger = $this->get('logger');
    /**
     * Encontra o Livro no Banco
     */ 
    $entityManager = $this->get('em');
    $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $book = $booksRepository->find($id);   
    /**
     * Verifica se existe um livro com a ID informada
     */
    if (!$book) {
        $logger->info("Book {$id} not Found");
        throw new \Exception("Book not Found", 404);
    }     
    /**
     * Remove a entidade
     */
    $entityManager->remove($book);
    $entityManager->flush(); 
    $logger->info("Book {$id} deleted", $book->getValues());
    $return = $response->withJson(['msg' => "Deletando o livro {$id}"], 204)
        ->withHeader('Content-type', 'application/json');
    return $return;
});