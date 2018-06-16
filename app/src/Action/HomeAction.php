<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Test\db;
use \PDO;
final class HomeAction
{
    private $view;
    private $logger;
    private $directory;

    public function __construct(Twig $view, LoggerInterface $logger, $directory)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->directory = $directory;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");
        $db = new db();
        $db = $db->connect();

        $sql = "SELECT intro_text FROM config";
        $stmt = $db->query($sql);

        $intro_text = $stmt->fetch(PDO::FETCH_OBJ)->intro_text;

        $sql = "SELECT path FROM images";
        $stmt = $db->query($sql);
        $images = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        var_dump($images);
        $this->view->render($response, 'home.twig', [
            'intro_text' => $intro_text,
            'images' => $images
        ]);
        return $response;
    }
}
