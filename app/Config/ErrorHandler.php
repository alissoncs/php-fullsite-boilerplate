<?php

namespace App\Config;

class ErrorHandler
{

  public function setup($container)
  {
    $container['errorHandler'] = function ($c) {
      return function ($request, $response, $exception) use ($c) {
        $status = 500;
        $json = [
          'type' => 'InternalError',
          'detail' => $exception->getMessage(),
          'exception' => get_class($exception),
        ];

        if ($exception instanceof \Illuminate\Database\QueryException) {
          $json['type'] = 'DatabaseError';
        }

        return $response->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
          ->withJson($json, $status);
      };
    };

    $container['phpErrorHandler'] = function ($c) {
      return function ($request, $response, $exception) use ($c) {
        $status = 500;
        $json = [
          'type' => 'InternalError',
          'detail' => $exception->getMessage(),
          'exception' => get_class($exception),
        ];

        if ($exception instanceof \Illuminate\Database\QueryException) {
          $json['type'] = 'DatabaseError';
        }

        return $response->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
          ->withJson($json, $status);
      };
    };

  }
}
