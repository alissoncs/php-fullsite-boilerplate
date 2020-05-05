<?php

namespace App\Admin\Controller;

use App\Admin\Controller\BaseController;
use App\Model\Unidade;

class UnidadesController extends BaseController
{
  public function list($request, $response, $args)
  {
    $pages = Unidade::orderBy('nome', 'ASC')->get();

    if (count($pages)) {
      foreach ($pages as &$page) {
        if (!empty($page['horarios_exames'])) {
          $page['horarios_exames'] = json_decode($page['horarios_exames']);
        }
      }
    }

    return $response->withJson([
      'data' => $pages,
    ]);
  }

  public function create($request, $response, $args)
  {
    $payload = $request->getParsedBody();
    $payload['horarios_exames'] = $payload['horarios_exames'] ? json_encode($payload['horarios_exames']) : null;

    $created = Unidade::create($payload);

    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args)
  {
    $payload = $request->getParsedBody();

    $id = $args['id'];
    $payload['horarios_exames'] = $payload['horarios_exames'] ? json_encode($payload['horarios_exames']) : null;

    $created = Unidade::findOrFail($id);
    $created->update($payload);

    return $response->withJson([]);
  }

  public function delete($request, $response, $args)
  {
    $id = $args['id'];

    $created = Unidade::findOrFail($id);
    $created->delete();

    return $response->withJson([]);
  }

  public function find($request, $response, $args) {
    $id = $args['id'];

    $created = Unidade::findOrFail($id);
    $builder = $created['horarios_exames'];

    $created['horarios_exames'] = !empty($builder) ? json_decode($builder) : null;

    return $response->withJson($created);
  }
}
