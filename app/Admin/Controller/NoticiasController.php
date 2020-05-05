<?php
namespace App\Admin\Controller;

use App\Admin\Controller\BaseController;
use App\Model\Noticia;

class NoticiasController extends BaseController
{
  public function list($request, $response, $args)
  {
    $categorias = Noticia::all();

    return $response->withJson([
      'data' => $categorias,
    ]);
  }
  public function find($request, $response, $args)
  {
    return $response->withJson(Noticia::findOrFail($args['id']));    
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();
    if (empty($payload['data'])) {
      $payload['data'] = date('Y-m-d H:i:s');
    }
    $created = Noticia::create($payload);
    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();

    $id = $args['id'];

    if (empty($payload['data'])) {
      $payload['data'] = date('Y-m-d H:i:s');
    }

    $created = Noticia::findOrFail($id);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function delete($request, $response, $args) {
    $id = $args['id'];

    $created = Noticia::findOrFail($id);
    $created->delete();

    return $response->withJson([
    ]);
  }


}
