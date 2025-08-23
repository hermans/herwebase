<?php
namespace App\Middlewares;

use Slim\Csrf\Guard;
use Slim\Csrf\Storage\ArrayStorage;
use Psr\Http\Message\ResponseFactoryInterface;

class CsrfGuard extends Guard
{
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        parent::__construct($responseFactory);

        $this->setFailureHandler(function ($request, $handler) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write('CSRF validation failed');
            return $response->withStatus(403);
        });
    }
}