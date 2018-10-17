<?php
declare(strict_types=1);

namespace Northwoods\Middleware;

use InvalidArgumentException;
use Northwoods\Middleware\Fixture\Handler;
use Northwoods\Middleware\Fixture\Middleware;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class ConditionalMiddlewareTest extends TestCase
{
    public function testCallsActualMiddlewareWhenConditionMatches(): void
    {
        $middleware = new ConditionalMiddleware(new Middleware(), function ($request) {
            return $request->getHeaderLine('accept') === 'application/json';
        });

        $handler = new Handler();
        $request = new ServerRequest('GET', 'https://example.com');
        $request = $request->withHeader('accept', 'application/json');

        $response = $middleware->process($request, $handler);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(Middleware::class, (string) $response->getBody());
    }

    public function testDoesNotCallActualMiddlewareWhenConditionFails(): void
    {
        $middleware = new ConditionalMiddleware(new Middleware(), function ($request) {
            return $request->getHeaderLine('accept') === 'application/json';
        });

        $handler = new Handler();
        $request = new ServerRequest('GET', 'https://example.com');

        $response = $middleware->process($request, $handler);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(Handler::class, (string) $response->getBody());
    }

    public function testDoesNotCallActualMiddlewareUnlessConditionReturnsTrue(): void
    {
        $middleware = new ConditionalMiddleware(new Middleware(), function ($request) {
            return 'okay';
        });

        $handler = new Handler();
        $request = new ServerRequest('GET', 'https://example.com');

        $response = $middleware->process($request, $handler);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(Handler::class, (string) $response->getBody());
    }
}
