<?php
namespace App\Admin\Controller;

use Illuminate\Support\Facades\DB;
use App\Admin\Controller\BaseController;
use App\Model\Banner;

class BannersController extends BaseController
{
  public function posicoes($request, $response, $args) {
    return $response->withJson([
      'options' => [
        'slider_home' => 'Sliders da home',
        'servicos_home' => 'Outros Serviços (Home)',
      ]
    ]);
  }

  public function list($request, $response, $args)
  {
    $categorias = Banner::all();

    return $response->withJson([
      'data' => $categorias,
    ]);
  }

  public function find($request, $response, $args) {
    return $response->withJson(Banner::findOrFail($args['id']));
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();

    $created = Banner::create($payload);
    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();

    $id = $args['id'];
    $created = Banner::findOrFail($id);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function delete($request, $response, $args) {
    $id = $args['id'];

    $created = Banner::findOrFail($id);
    $created->delete();

    return $response->withJson([
    ]);
  }

}
