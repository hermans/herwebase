<?php
use Slim\App;
use Psr\Http\Message\ServerRequestInterface;

return function (App $app): void {
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    $errorMiddleware->setDefaultErrorHandler(function (
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ) use ($app) {
        $status = $exception->getCode();
        $status = ($status >= 400 && $status < 600) ? $status : 500;

        $html = ($status === 404)
            ? '<h1>404 Not Found</h1><p>The page you requested could not be found.</p>'
            : '<h1>500 Internal Server Error</h1><p>Something went wrong. Please try again later.</p>';

        $response = $app->getResponseFactory()->createResponse($status);
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    });

    // Catch-all route for unmatched paths
    $app->map(['GET', 'POST', 'PUT', 'DELETE'], '/{routes:.+}', function ($request, $response) {
        $html = '<h1>404 Not Found</h1><p>The route you requested does not exist.</p>';
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html')->withStatus(404);
    });
};
