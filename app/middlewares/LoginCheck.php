<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class LoginCheck implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $_SESSION['user'] ?? null;

        if (!$session) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write('Unauthorized: Please log in.');
            return $response->withStatus(401);
        }

        return $handler->handle($request);
    }
}
