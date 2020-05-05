<?php
namespace App\Admin\Controller;

use Illuminate\Support\Facades\DB;
use App\Admin\Controller\BaseController;
use App\Model\Tag;
// use App\Model\Tag;

class TagsController extends BaseController
{
  public function list($request, $response, $args)
  {
    $list = Tag::all();

    return $response->withJson([
      'data' => $list,
    ]);
  }

  public function find($request, $response, $args) {
    return $response->withJson(Tag::findOrFail($args['id']));
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();

    $created = Tag::create($payload);
    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();
    $created = Tag::findOrFail($args['id']);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function delete($request, $response, $args) {
    $created = Tag::findOrFail($args['id']);
    $created->delete();

    return $response->withJson([
    ]);
  }

}
