<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

final class AdminHomeAction
{
    private $view;
    private $logger;
    private $session;
    private $router;

    public function __construct(Twig $view, LoggerInterface $logger, $session, $router)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->session = $session;
        $this->router = $router;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Admin Home page action dispatched");
        if ( $this->session->authenticated !== true)
        {
            $url = $this->router->pathFor('loginpage');
            return $response->withRedirect($url);
        }
        $this->view->render($response, 'admin.twig');
        return $response;
    }
}
