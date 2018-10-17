<?php
declare(strict_types=1);

namespace Northwoods\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ConditionalMiddleware implements MiddlewareInterface
{
    /** @var MiddlewareInterface */
    private $middleware;

    /** @var callable */
    private $condition;

    public function __construct(MiddlewareInterface $middleware, callable $condition)
    {
        $this->middleware = $middleware;
        $this->condition = $condition;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (($this->condition)($request) === true) {
            return $this->middleware->process($request, $handler);
        }

        return $handler->handle($request);
    }
}
