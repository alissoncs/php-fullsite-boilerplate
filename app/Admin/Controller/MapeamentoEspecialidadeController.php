<?php
namespace App\Admin\Controller;

use Illuminate\Support\Facades\DB;
use App\Admin\Controller\BaseController;
use App\Model\MapeamentoEspecialidade;
// use App\Model\MapeamentoEspecialidade;

class MapeamentoEspecialidadeController extends BaseController
{

  public function mapa($request, $response, $args) {
    $service = $this->container['service.api_medicos'];
    $especialidades = $service->listAllEspecialidades();

    return $response->withJson([
      'data' => $especialidades,
    ]);
  }

  public function list($request, $response, $args)
  {
    $list = MapeamentoEspecialidade::all();

    return $response->withJson([
      'data' => $list,
    ]);
  }

  public function find($request, $response, $args) {
    return $response->withJson(MapeamentoEspecialidade::findOrFail($args['id']));
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();

    $created = MapeamentoEspecialidade::create($payload);
    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();
    $created = MapeamentoEspecialidade::findOrFail($args['id']);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function delete($request, $response, $args) {
    $created = MapeamentoEspecialidade::findOrFail($args['id']);
    $created->delete();

    return $response->withJson([
    ]);
  }

}
