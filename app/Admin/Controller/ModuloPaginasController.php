<?php
namespace App\Admin\Controller;

use Illuminate\Support\Facades\DB;
use App\Admin\Controller\BaseController;
use App\Model\ModuloPagina;
use App\Model\PaginaCustomizada;

class ModuloPaginasController extends BaseController
{
  public function list($request, $response, $args)
  {
    $list = ModuloPagina::levels()->get();

    $layouts = PaginaCustomizada::all();

    return $response->withJson([
      'data' => $list,
      'layouts' => $layouts,
    ]);
  }

  public function find($request, $response, $args) {
    return $response->withJson(ModuloPagina::findOrFail($args['id']));
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();

    $created = ModuloPagina::create($payload);
    return $response->withJson([
      'data' => $created,
    ]);
  }
  
  public function duplicate($request, $response, $args) {
    $existent = ModuloPagina::findOrFail($args['id']);
    $copy = $existent->replicate();
    
    $copy->titulo = $existent->titulo . ' (cópia)';
    $copy->save();

    return $response->withJson([
      'data' => $copy,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();
    $created = ModuloPagina::findOrFail($args['id']);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function delete($request, $response, $args) {
    $created = ModuloPagina::findOrFail($args['id']);
    $created->delete();

    return $response->withJson([
    ]);
  }

}
