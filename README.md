# Northwoods Conditional Middleware

[![Build Status](https://travis-ci.com/northwoods/conditional-middleware.svg?branch=master)](https://travis-ci.com/northwoods/conditional-middleware)
[![Code Quality](https://scrutinizer-ci.com/g/northwoods/conditional-middleware/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/northwoods/conditional-middleware/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/northwoods/conditional-middleware/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/northwoods/conditional-middleware/?branch=master)
[![Latest Stable Version](http://img.shields.io/packagist/v/northwoods/conditional-middleware.svg?style=flat)](https://packagist.org/packages/northwoods/conditional-middleware)
[![Total Downloads](https://img.shields.io/packagist/dt/northwoods/conditional-middleware.svg?style=flat)](https://packagist.org/packages/northwoods/conditional-middleware)
[![License](https://img.shields.io/packagist/l/northwoods/conditional-middleware.svg?style=flat)](https://packagist.org/packages/northwoods/conditional-middleware)

Middleware proxy that executes a middleware based on request conditions.

## Installation

The best way to install and use this package is with [composer](http://getcomposer.org/):

```shell
composer require northwoods/conditional-middleware
```

## Usage

```php
use Northwoods\Middleware\ConditionalMiddleware;

/** @var \Psr\Http\Server\MiddlewareInterface */
$actual = /* any existing middleware */

$middleware = new ConditionalMiddleware($actual, function (Request $request): bool {
    return $request->getHeaderLine('accept') === 'application/json';
});
```

In this example, the wrapped `$actual` middleware will only be executed if the
request accepts the `application/json` content type.

### Condition Callable

The condition callable should use the following signature:

```php
function (Request $request): bool;
```

The condition must return `true` (by strict `===` comparison) for the wrapped
middleware to be executed. If the condition check fails the handler will be
called immediately.
