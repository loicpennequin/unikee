<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Test\db;

// -----------------------------------------------------------------------------
// Views
// -----------------------------------------------------------------------------

$app->get('/', App\Action\HomeAction::class)
    ->setName('homepage');

$app->get('/login', App\Action\LoginAction::class)
    ->setName('loginpage');

$app->get('/admin', App\Action\AdminHomeAction::class)
    ->setName('admin-home');

// -----------------------------------------------------------------------------
// API
// -----------------------------------------------------------------------------

// Authentication
$app->post('/api/login', function(Request $request, Response $response, $args){
    $body = $request->getParsedBody();
    if ( $body['login'] === 'admin' && $body['password'] === 'admin' )
    {
        $this->session->set('authenticated', true);

        $url = $this->router->pathFor('admin-home');
        return $response->withRedirect($url);
    } else {
        $this->session->set('authenticated', false);
        $this->flash->addMessage('errorMessage', 'Identifiants incorrects.');

        $url = $this->router->pathFor('loginpage');
        return $response->withRedirect($url);
    }
})->setName('authentication');

$app->get('/logout', function(Request $request, Response $response, $args){
    $this->session->set('authenticated', false);
    $url = $this->router->pathFor('homepage');
    return $response->withRedirect($url);
})->setName('logout');

// Header image

$app->get('/api/header', function(Request $request, Response $response, $args){
    $sql = "SELECT header_image FROM config WHERE id = 1";
    $db = new db();
    $db = $db->connect();

    $stmt = $db->query($sql);

    $header_image = $stmt->fetch(PDO::FETCH_OBJ);
    $db = null;
    return $response->withJson($header_image, 200);
})->setName('get_header');

$app->post('/api/header', function(Request $request, Response $response, $args){
    $directory = $this->get('upload_directory');
    $fileArray = $request->getUploadedFiles();
    $uploadedFile = $fileArray['header-img'];

    $uploadedFile->moveTo($directory . 'header-picture.jpg');
    return $response->withStatus(200);
})->setName('set_header');

// Intro text

$app->get('/api/intro', function(Request $request, Response $response, $args){
    $sql = "SELECT intro_text FROM config WHERE id = 1";
    $db = new db();
    $db = $db->connect();

    $stmt = $db->query($sql);

    $intro = $stmt->fetch(PDO::FETCH_OBJ);
    $db = null;
    return $response->withJson($intro, 200);
})->setName('get_intro');

$app->post('/api/intro', function(Request $request, Response $response, $args){
    $body = $request->getParsedBody();

    $sql = "UPDATE config SET intro_text = :intro_text WHERE id = 1";
    $db = new db();
    $db = $db->connect();

    $stmt = $db->prepare($sql);
    $stmt->bindValue( ':intro_text', $body['intro_text']);
    $stmt->execute();
    $db = null;

    return $response->withStatus(200);
})->setName('set_intro');


// Gallery

$app->get('/api/gallery', function(Request $request, Response $response, $args){
    $sql = "SELECT * FROM images";
    $db = new db();
    $db = $db->connect();

    $stmt = $db->query($sql);

    $intro = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    return $response->withJson($intro, 200);
})->setName('get_gallery');

$app->post('/api/gallery', function(Request $request, Response $response, $args){
    $directory = $this->get('gallery_upload_directory');
    $fileArray = $request->getUploadedFiles();
    $uploadedFile = $fileArray['gallery-img'];
    $filename = $uploadedFile->getClientFilename();

    $sql = "INSERT INTO images (path) VALUES (:path)";

    $db = new db();
    $db = $db->connect();

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':path', $filename);

    $stmt->execute();
    $db = null;

    $uploadedFile->moveTo($directory . $filename);

    return $response->withStatus(200);
})->setName('add_gallery');

$app->delete('/api/gallery/{id}', function(Request $request, Response $response, $args){
    $id = $args['id'];
    $sql = "SELECT path FROM images where id = $id";
    $db = new db();
    $db = $db->connect();

    $stmt = $db->query($sql);

    $img = $stmt->fetch(PDO::FETCH_OBJ);
    $filename = $img->path;
    $directory = $this->get('gallery_upload_directory');
    $path = $directory . $filename;
    unlink($path);

    $sql = "DELETE FROM images where id = $id";

    $stmt = $db->prepare($sql);
    $stmt->execute();


    $db = null;
    return $response->withStatus(200);
})->setName('get_gallery');
