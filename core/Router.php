<?php

class Router
{
  protected static $routes = [];

  public static function get($uri, $callback)
  {
    self::$routes['GET'][$uri] = $callback;
  }

  public static function post($uri, $callback)
  {
    self::$routes['POST'][$uri] = $callback;
  }

  public static function dispatch($uri)
  {
    $uri = strtok($uri, '?'); // Hilangkan query string
    $method = $_SERVER['REQUEST_METHOD'];

    foreach (self::$routes[$method] as $route => $callback) {
      // Cek apakah rute memiliki parameter dinamis
      $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $route);
      $pattern = "#^" . $pattern . "$#";

      if (preg_match($pattern, $uri, $matches)) {
        array_shift($matches); // Hapus elemen pertama (path penuh)
        return call_user_func_array($callback, $matches);
      }
    }

    // Jika tidak ada rute yang cocok
    http_response_code(404);
    echo "404 Not Found";
  }
}
