<?php

namespace App\Middleware;

class AdminSharedMiddleware
{

  private $jwt;

  public function __construct($jwt) {
    $this->jwt = $jwt;
  }

  public function __invoke($request, $response, $next)
  {

    $path = $request->getUri()->getPath();

    // by pass
    if (strpos($path, 'admin') === false || $path === '/admin/auth') {
      $response = $next($request, $response);
      return $response;
    }

    // pega o header
    $token = $request->getHeader('Authorization');

    if (empty($token)) {
      return $response->withStatus(401)->withJson([
        'error' => 'Token is required',
        'route' => $request->getUri()->getPath(),
      ]);
    } else {
      $token = $token[0];
    }

    try {
      $decode = $this->jwt->decode($token);
    } catch(\Exception $e) {
      return $response->withStatus(401)->withJson([
        'error' => 'Faça login novamente',
        'detail' => $e->getMessage(),
      ]);
    }

    if (!$decode) {
      return $response->withStatus(401)->withJson([
        'error' => 'Faça login novamente',
      ]);
    }

    $response = $next($request, $response);
    return $response;
  }
}
