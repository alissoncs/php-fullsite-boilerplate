<?php
namespace App\Admin\Controller;

use Illuminate\Support\Facades\DB;
use App\Admin\Controller\BaseController;
use App\Model\Post;
use App\Model\PostCategoria;
use App\Model\Tag;
use App\Model\PostTag;

class PostsController extends BaseController
{
  public function list($request, $response, $args)
  {
    $categorias = PostCategoria::all();

    return $response->withJson([
      'data' => $categorias,
    ]);
  }

  public function find($request, $response, $args) {
    return $response->withJson(PostCategoria::findOrFail($args['id']));
  }

  public function create($request, $response, $args) {
    $payload = $request->getParsedBody();

    $created = PostCategoria::create($payload);
    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function update($request, $response, $args) {
    $payload = $request->getParsedBody();

    $id = $args['id'];
    $created = PostCategoria::findOrFail($id);
    $created->update($payload);

    return $response->withJson([
    ]);
  }

  public function delete($request, $response, $args) {
    $id = $args['id'];

    $created = PostCategoria::findOrFail($id);
    $created->delete();

    return $response->withJson([
    ]);
  }

  public function findPost($request, $response, $args) {
    $post = Post::with(['tags'])->where('id', $args['id'])->first();
    if ($post) {
      return $response->withJson($post);
    }
    return $response->withStatus(404);
  }

  public function listPosts($request, $response, $args)
  {
    $data = Post::with(['tags'])->orderBy('created_at', 'DESC')->get();

    return $response->withJson([
      'data' => $data,
    ]);
  }

  public function createPost($request, $response, $args) {
    $payload = $request->getParsedBody();

    if (empty($payload['data'])) {
      $payload['data'] = date('Y-m-d H:i:s');
    }

    $created = Post::create($payload);

    if (isset($payload['tags'])) {
      foreach($payload['tags'] as $tagId) {
        $tag = Tag::findFirst($tagId);
        if ($tag) {
          $tag = new PostTag([
            'post_id' => $created->id,
            'tag_id' => $tagId,
          ]);
          $tag->save();
        }
      }
    }

    return $response->withJson([
      'data' => $created,
    ]);
  }

  public function updatePost($request, $response, $args) {
    $payload = $request->getParsedBody();

    $id = $args['id'];

    if (empty($payload['data'])) {
      $payload['data'] = date('Y-m-d H:i:s');
    }

    $created = Post::findOrFail($id);
    $result = $created->update($payload);
    $created->tags()->detach();

    if (isset($payload['tags'])) {
      foreach($payload['tags'] as $tagId) {
        $tag = Tag::find($tagId);
        if ($tag) {
          $tag = new PostTag([
            'post_id' => $created->id,
            'tag_id' => $tagId,
          ]);
          $tag->save();
        }
      }
    }

    return $response->withJson([
      'data' => $result,
    ]);
  }

  public function deletePost($request, $response, $args) {
    $id = $args['id'];

    $created = Post::findOrFail($id);
    $created->delete();

    return $response->withJson([
    ]);
  }

}
