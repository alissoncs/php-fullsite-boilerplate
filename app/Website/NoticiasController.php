<?php
namespace App\Website;

use App\Website\BaseWebsiteController;
use App\Model\NoticiaCategoria;
use App\Model\Noticia;

class NoticiasController extends BaseWebsiteController
{
  private $pageSize = 8;

  public function home($request, $response, $args)
  {
    $isCategoria = isset($args['categoria']);
    $tag = $request->getQueryParam('tag');

    $categoria = null;

    if ($isCategoria) {
      $categoria = NoticiaCategoria::where('slug', $args['categoria'])->first();
      if (!$categoria) {
        return $response->withRedirect('/404');
      }
    }

    $params = $request->getQueryParams();
    $page = empty($params['pagina']) ? 0 : intval($params['pagina']) - 1;

    $postCount = Noticia::where('status', 'ativo')->count();

    if ($categoria) {
      $postCount = Noticia::where('status', 'ativo')->where('noticia_categoria_id', $categoria->id)->count();
    }

    $totalPages = ceil($postCount / $this->pageSize);

    if ($page > $totalPages) {
      return $response->withRedirect('/noticia?pagina=' . $totalPages);
    }

    $pageSize = $this->pageSize;

    if ($page === 0 && !$categoria) {
      $pageSize = $pageSize+1;
    }

    $query = Noticia::where('status', 'ativo')->orderBy('created_at', 'desc')->offset($page * $pageSize)->limit($pageSize);

    if ($categoria) {
      $query->where('noticia_categoria_id', $categoria->id);
    }

    $posts = $query->get();

    return $this->container['view']->render($response, 'noticias.twig', [
      'categoria' => $categoria,
      'posts' => $posts,
      'posts_count' => $postCount,
      'current_page' => $page + 1,
      'total_pages' => $totalPages,
      'tag' => $tag,
    ]);
  }

  public function post($request, $response, $args)
  {
    $post = Noticia::where('slug', $args['post'])->first();
    $categoria = NoticiaCategoria::where('slug', $args['categoria'])->first();

    if (!$post || !$categoria) {
      return $response->withRedirect('/404');
    }
    $related_posts = Noticia::with('categoria')->limit(2)->orderBy('created_at', 'desc')->where('noticia_categoria_id', $categoria->id)
    ->where('id', '!=', $post->id)->get();

    if (count($related_posts) == 0) {
      $related_posts = Noticia::with('categoria')->limit(2)->orderBy('created_at', 'desc')
      ->where('id', '!=', $post->id)
      ->get();
    }

    $post->views = $post->views + 1;
    $post->save();

    return $this->container['view']->render($response, 'noticia-post.twig', [
      'categoria' => $categoria,
      'post' => $post,
      'related_posts' => $related_posts,
    ]);
  }
}
