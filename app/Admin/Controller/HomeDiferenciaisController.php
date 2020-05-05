<?php
namespace App\Admin\Controller;

use Illuminate\Support\Facades\DB;
use App\Admin\Controller\BaseController;
use App\Model\HomeDiferencial;
use App\Model\PaginaCustomizada;

class HomeDiferenciaisController extends BaseController
{
  public function list($request, $response, $args)
  {
    $list = HomeDiferencial::all();

    return $response->withJson([
      'data' => $list,
    ]);
  }

  public function find($request, $response, $args) {
    return $response->withJson(HomeDiferencial::findOrFail($args['id']));
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();

    $created = HomeDiferencial::create($payload);
    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();
    $created = HomeDiferencial::findOrFail($args['id']);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function delete($request, $response, $args) {
    $created = HomeDiferencial::findOrFail($args['id']);
    $created->delete();

    return $response->withJson([
    ]);
  }

}
