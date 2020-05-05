<?php
namespace App\Admin\Controller;

use App\Admin\Controller\BaseController;
use App\Model\Usuario;

class UsuariosController extends BaseController
{
  public function list($request, $response, $args)
  {
    $categorias = Usuario::all();

    return $response->withJson([
      'data' => $categorias,
    ]);
  }

  public function find($request, $response, $args) {
    return $response->withJson(Usuario::findOrFail($args['id']));
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();

    $payload['senha'] = password_hash($payload['senha'], PASSWORD_DEFAULT);

    $created = Usuario::create($payload);
    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();

    $id = $args['id'];

    $created = Usuario::findOrFail($id);

    if ($payload['senha'] === $created->senha) {
      unset($payload['senha']);
    }

    if (!empty($payload['senha'])) {
      $payload['senha'] = password_hash($payload['senha'], PASSWORD_DEFAULT);
    }

    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function delete($request, $response, $args) {
    $id = $args['id'];

    $count = Usuario::count();

    if ($count === 1) {
      return $response->withStatus(422)->withJson([
        'detail' => 'Precisa ter 1 usuário cadastrado'
      ]);
    }

    $created = Usuario::findOrFail($id);
    $created->delete();

    return $response->withJson([
    ]);
  }


}
