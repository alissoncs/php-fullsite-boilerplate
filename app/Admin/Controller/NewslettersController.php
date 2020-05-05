<?php
namespace App\Admin\Controller;

use App\Admin\Controller\BaseController;
use App\Model\FormNewsletter;

class NewslettersController extends BaseController
{
  public function list($request, $response, $args)
  {
    $query = FormNewsletter::orderBy('created_at', 'DESC');

    $term = $request->getQueryParam('term', null);
    $offset = $request->getQueryParam('offset', 0);
    $limit = $request->getQueryParam('limit', 50);

    if ($term) {
      $query->where('nome', 'LIKE', '%'.$term.'%')->orWhere('email', 'LIKE', '%'.$term.'%');
    }
    $count = $query->count();
    $query->offset($offset)->limit($limit);
    $list = $query->get();

    return $response->withJson([
      'data' => $list,
      'total' => $count,
    ]);
  }

  public function delete($request, $response, $args) {
    $id = $args['id'];

    $created = FormNewsletter::findOrFail($id);
    $created->delete();

    return $response->withJson([
    ]);
  }


}
