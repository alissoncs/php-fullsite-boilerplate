<?php

namespace App\Admin\Controller;

use App\Admin\Controller\BaseController;
use App\Model\Pagina;
use App\Model\PaginaCustomizada;

class PaginasCustomizadasController extends BaseController
{
  public function list($request, $response, $args)
  {
    $pages = PaginaCustomizada::orderBy('titulo', 'ASC')->get();

    if (count($pages)) {
      foreach ($pages as &$page) {
        if (!empty($page['conteudo_builder'])) {
          $page['conteudo_builder'] = json_decode($page['conteudo_builder']);
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
    $payload['conteudo_builder'] = $payload['conteudo_builder'] ? json_encode($payload['conteudo_builder']) : null;

    $payload['label'] = $payload['titulo'];

    $created = PaginaCustomizada::create($payload);

    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args)
  {
    $payload = $request->getParsedBody();

    $id = $args['id'];
    $payload['label'] = $payload['titulo'];
    $payload['conteudo_builder'] = $payload['conteudo_builder'] ? json_encode($payload['conteudo_builder']) : null;

    $created = PaginaCustomizada::findOrFail($id);
    $created->update($payload);

    return $response->withJson([]);
  }

  public function delete($request, $response, $args)
  {
    $id = $args['id'];

    $created = PaginaCustomizada::findOrFail($id);
    $created->delete();

    return $response->withJson([]);
  }

  public function find($request, $response, $args) {
    $id = $args['id'];

    $created = PaginaCustomizada::findOrFail($id);
    $builder = $created['conteudo_builder'];

    $created['conteudo_builder'] = !empty($builder) ? json_decode($builder) : null;

    return $response->withJson($created);
  }
}
