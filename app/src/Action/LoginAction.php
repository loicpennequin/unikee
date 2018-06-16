<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

final class LoginAction
{
    private $view;
    private $logger;
    private $flash;

    public function __construct(Twig $view, LoggerInterface $logger, $flash)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Login page action dispatched");
        $this->view->render($response, 'login.twig', [
            'errorMessage' => $this->flash->getMessage('errorMessage')[0]
        ]);
        return $response;
    }
}
