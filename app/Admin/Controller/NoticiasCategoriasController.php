<?php
namespace App\Admin\Controller;

use App\Admin\Controller\BaseController;
use App\Model\Noticia;
use App\Model\NoticiaCategoria;

class NoticiasCategoriasController extends BaseController
{
  public function list($request, $response, $args)
  {
    $categorias = NoticiaCategoria::all();

    return $response->withJson([
      'data' => $categorias,
    ]);
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();
    $created = NoticiaCategoria::create($payload);
    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();

    $id = $args['id'];

    $created = NoticiaCategoria::findOrFail($id);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function delete($request, $response, $args) {
    $id = $args['id'];

    $created = NoticiaCategoria::findOrFail($id);
    $created->delete();

    return $response->withJson([
    ]);
  }


}
