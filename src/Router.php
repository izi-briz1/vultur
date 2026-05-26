<?php declare(strict_types=1);

namespace App;

use Closure;

class Router{
    /**
     * @var array
     */
    protected array $routes = [];

    public function addRoute(string $pattern, Closure $closure){
        $this->routes[$pattern] = $closure;
    }

    public function dispatch(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        foreach ($this->routes as $pattern => $closure) {
            $regex = '~^'. preg_replace('~\{(\w+)\}~', '(?P<$1>[^/]+)', $pattern). '$~';

            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $closure(...$params);
            }
        }

        http_response_code(404);
        return ($this->routes['notFound'] ?? fn() => '404 Not Found')([]);
    }
}