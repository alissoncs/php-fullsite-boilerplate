<?php

namespace App\Admin;

use App\Middleware\AdminSharedMiddleware;
use App\Model\Usuario;
use App\Admin\Controller\ConfigController;
use App\Admin\Controller\UploadsController;
use App\Admin\Controller\BannersController;
use App\Admin\Controller\UsuariosController;
use Psr7Middlewares\Middleware\TrailingSlash;

class AdminRoutes
{
  private $jwt;
  public function __construct($jwt) {
    $this->jwt = $jwt;
  }

  public function apply($app)
  {
    // $app->add(new TrailingSlash(true));

    $jwt = $this->jwt;

    $app->map(['GET', 'POST', 'DELETE', 'PATCH', 'PUT'], '/', function ($request, $response, $args) {
      return $response->withJson([
        'status' => 'ok',
        'version' => 1,
      ]);
    });

    $app->post('/auth', function($request, $response) use ($jwt) {
      $data = $request->getParsedBody();

      if (empty($data['senha']) || empty($data['email'])) {
        return $response->withStatus(422)->withJson([
          'error' => 'Email e senha obrigatórios'
        ]);
      }

      $usuario = Usuario::where('email', '=', $data['email'])->first();

      if (!$usuario) {
        return $response->withStatus(401)->withJson([
          'error' => 'Usuário não encontrado'
        ]);
      }

      if (!password_verify($data['senha'], $usuario->senha)) {
        return $response->withStatus(401)->withJson([
          'error' => 'Senha incorreta',
          'current' => $usuario->senha,
          'data' => $data['senha'],
        ]);
      }

      $token = $jwt->encode([
        'user_id' => $usuario->id,
        'user_name' => $usuario->login,
        'user_senha' => $usuario->senha,
      ]);

      return $response->withJson([
        'token' => $token,
        'user_id' => $usuario->id,
        'user_name' => $usuario->login,
        'user_email' => $usuario->email,
      ]);
    });

    $app->get('/banners/posicoes', BannersController::class . ':posicoes');
    $app->get('/banners', BannersController::class . ':list');
    $app->get('/banners/{id}', BannersController::class . ':find');
    $app->post('/banners', BannersController::class . ':create');
    $app->put('/banners/{id}', BannersController::class . ':update');
    $app->delete('/banners/{id}', BannersController::class . ':delete');

    // usuarios
    $app->get('/usuarios', UsuariosController::class . ':list');
    $app->get('/usuarios/{id}', UsuariosController::class . ':find');
    $app->post('/usuarios', UsuariosController::class . ':create');
    $app->put('/usuarios/{id}', UsuariosController::class . ':update');
    $app->delete('/usuarios/{id}', UsuariosController::class . ':delete');


    $app->get('/uploads', UploadsController::class . ':list');
    $app->post('/uploads', UploadsController::class . ':create');
    $app->post('/uploads-downloadable', UploadsController::class . ':createdDownloadable');

    $app->get('/configs', ConfigController::class . ':list');
    $app->put('/configs', ConfigController::class . ':update');

  }
}
