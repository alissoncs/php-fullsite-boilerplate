<?php
namespace App\Admin\Controller;

use App\Admin\Controller\BaseController;
use App\Model\Plano;
use App\Model\PlanoInteresse;
use App\Model\FormSimulacaoPlano;
use App\Model\PlanoCategoria;
use App\Util\Pagination;

class PlanosController extends BaseController
{
  public function list($request, $response, $args)
  {
    $categorias = PlanoCategoria::orderBy('ordem', 'ASC')->get();

    foreach( $categorias as &$cat) {
      $cat->arquivos = $cat->arquivos();
    }
    return $response->withJson([
      'data' => $categorias,
    ]);
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();
    $created = PlanoCategoria::create($payload);

    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();

    $id = $args['id'];
    $created = PlanoCategoria::findOrFail($id);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function find($request, $response, $args) {
    $id = $args['id'];

    $created = PlanoCategoria::findOrFail($id);
    $result = $created->toArray();


    $result['arquivos'] = $created->arquivos();

    return $response->withJson($result);
  }


  public function delete($request, $response, $args) {
    $id = $args['id'];

    $created = PlanoCategoria::findOrFail($id);

    $plano = Plano::where('plano_categoria_id', $id)->first();

    if ($plano) {
      return $response->withJson([
        'detail' => 'Categoria sendo usada pelo plano "' . $plano->titulo . '"',
      ], 422);
    }

    $created->delete();

    return $response->withJson([
    ]);
  }


  public function listPlanos($request, $response, $args)
  {
    $data = Plano::orderBy('ordem', 'ASC')->get();

    return $response->withJson([
      'data' => $data,
    ]);
  }

  public function createPlano($request, $response, $args) {
    $payload = $request->getParsedBody();


    if (!empty($payload['carencias'])) {
      $payload['carencias'] = json_encode($payload['carencias']);
    }

    $created = Plano::create($payload);

    return $response->withJson([
    ]);
  }

  public function findPlano($request, $response, $args) {

    $created = Plano::findOrFail($args['id']);
    $result = $created->toArray();
    if (!empty($result['carencias'])) {
      $result['carencias'] = json_decode($result['carencias']);
    } else {
      $result['carencias'] = [];
    }

    return $response->withJson($result);
  }

  public function updatePlano($request, $response, $args) {
    $payload = $request->getParsedBody();

    $id = $args['id'];


    if (!empty($payload['carencias'])) {
      $payload['carencias'] = json_encode($payload['carencias']);
    }

    $created = Plano::findOrFail($id);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function deletePlano($request, $response, $args) {
    $id = $args['id'];

    $created = Plano::findOrFail($id);

    $created->delete();

    return $response->withJson([
    ]);
  }

  public function listInteresses($request, $response) {
    $data = PlanoInteresse::orderBy('created_at', 'DESC')->get();

    return $response->withJson([
      'data' => $data,
    ]);
  }

  public function deleteInteresses($request, $response, $args) {
    $id = $args['id'];

    $created = PlanoInteresse::findOrFail($id);

    $created->delete();

    return $response->withJson([
    ]);
  }

  public function listPlanosForms($request, $response) {
    $query = FormSimulacaoPlano::orderBy('created_at', 'DESC');
    // $data = $query->get();

    if ($request->getQueryParam('filter')) {
      $query->where('nome', 'LIKE', '%' . $request->getQueryParam('filter') . '%');
    }

    return $response->withJson([
      'data' => $query->get(),
      'pages ' => Pagination::totalPages($query->count()),
    ]);
  }

  public function deletePlanosForms($request, $response, $args) {
    $id = $args['id'];

    $created = FormSimulacaoPlano::findOrFail($id);

    $created->delete();

    return $response->withJson([
    ]);
  }

}
