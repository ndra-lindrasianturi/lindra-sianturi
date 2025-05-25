<?php

class App
{
  public function run()
  {
    $uri = $_SERVER['REQUEST_URI'];
    Router::dispatch($uri);
  }
}
