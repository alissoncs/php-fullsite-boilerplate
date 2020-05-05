<?php
namespace App\Admin\Controller;

use Illuminate\Support\Facades\DB;
use App\Admin\Controller\BaseController;
use App\Model\AcessoRapido;

class AcessoRapidoController extends BaseController
{
  public function list($request, $response, $args)
  {
    $list = AcessoRapido::all();

    return $response->withJson([
      'data' => $list,
    ]);
  }

  public function find($request, $response, $args) {
    return $response->withJson(AcessoRapido::findOrFail($args['id']));
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();

    $created = AcessoRapido::create($payload);
    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();

    $id = $args['id'];
    $created = AcessoRapido::findOrFail($id);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function delete($request, $response, $args) {
    $id = $args['id'];

    $created = AcessoRapido::findOrFail($id);
    $created->delete();

    return $response->withJson([
    ]);
  }

}
