<?php

namespace App\Website;

use App\Website\BaseWebsiteController;
use App\Model\PostCategoria;
use App\Model\Post;
use App\Model\Tag;

class BlogController extends BaseWebsiteController
{
  private $pageSize = 8;

  private function tags() {
    return Tag::listWithCountPosts();
  }

  public function home($request, $response, $args)
  {
    $categorias = PostCategoria::menu();
    $isCategoria = isset($args['categoria']);

    $categoria = null;

    if ($isCategoria) {
      $categoria = PostCategoria::where('slug', $args['categoria'])->first();
      if (!$categoria) {
        return $response->withRedirect('/404');
      }
    }

    $tag = $request->getQueryParam('tag');
    if ($request->getQueryParam('tag')) {
      $tag = Tag::where(function($query) use($tag) {
        $query->where('id', $tag)
        ->orWhere('slug', $tag)
        ->orWhere('titulo', $tag);
      })->first();
    }

    $params = $request->getQueryParams();

    $page = empty($params['pagina']) ? 0 : intval($params['pagina']) - 1;

    $postCount = Post::with('categoria')->count();

    $totalPages = ceil($postCount / $this->pageSize);

    if ($page > $totalPages) {
      return $response->withRedirect('/blog?pagina=' . $totalPages);
    }

    $pageSize = $this->pageSize;

    if ($page === 0 && !$categoria) {
      $pageSize = $pageSize + 1;
    }

    $query = Post::with(['categoria'])
    ->orderBy('created_at', 'desc')
    ->offset($page * $pageSize)
    ->limit($pageSize);

    if ($tag) {
      $query->join('posts_tags', function($join) use ($tag) {
        $join
          ->on('posts_tags.post_id', '=', 'posts.id')
          ->where('posts_tags.tag_id', $tag->id);
      });
    }

    if ($categoria) {
      $query->where('post_categoria_id', $categoria->id);
    }

    $posts = $query->get();

    /// views  post_views
    $post_views = Post::with('categoria')
      ->whereRaw('MONTH(data) = ' . date('m') . ' AND YEAR(data) = ' . date('Y'))
      ->orderBy('views', 'desc')->limit(3)->get();
    if (count($post_views) == 0) {
      $post_views = Post::with('categoria')
        ->orderBy('views', 'desc')->limit(3)->get();
    }

    return $this->container['view']->render($response, 'blog.twig', [
      'categoria' => $categoria,
      'menu_categorias' => $categorias,
      'posts' => $posts,
      'posts_count' => $postCount,
      'current_page' => $page + 1,
      'total_pages' => $totalPages,
      'post_views' => $post_views,
      'tag' => $tag,
      'tags' => $this->tags(),
    ]);
  }

  public function categoria($request, $response, $args)
  {
    $categorias = PostCategoria::menu();
    $categoria = PostCategoria::where('slug', $args['categoria'])->first();
    if (!$categoria) {
      return $response->withRedirect('/404');
    }


    $query = Post::with('categoria')->orderBy('created_at', 'desc');
    $query = $query->where('post_categoria_id', $categoria->id);
    $posts = $query->get();
    $postCount = Post::with('categoria')->count();

    return $this->container['view']->render($response, 'blog-categoria.twig', [
      'menu_categorias' => $categorias,
      'categoria' => $categoria,
      'posts' => $posts,
      'posts_count' => $postCount,
      'tags' => $this->tags(),
    ]);
  }

  public function post($request, $response, $args)
  {
    $post = Post::where('slug', $args['post'])->first();
    $categoria = PostCategoria::where('slug', $args['categoria'])->first();
    $categorias = PostCategoria::menu();

    if (!$post || !$categoria) {
      return $response->withRedirect('/404');
    }
    $related_posts = Post::with('categoria')->whereNotIn('id', [$post->id])
      ->where('post_categoria_id', $categoria->id)
      ->limit(2)->orderBy('created_at', 'desc')
      ->get();

    if (count($related_posts) === 0) {
      $related_posts = Post::with('categoria')
      ->whereNotIn('id', [$post->id])
      ->limit(2)
      ->orderBy('created_at', 'desc')
      ->get();
    }

    $post->views = $post->views + 1;
    $post->save();

    return $this->container['view']->render($response, 'blog-post.twig', [
      'menu_categorias' => $categorias,
      'categoria' => $categoria,
      'post' => $post,
      'related_posts' => $related_posts,
      'tags' => $this->tags(),
    ]);
  }

  public function likes($request, $response, $args)
  {
    $post = Post::where('id', $args['post'])->first();
    if ($post) {
      $post->likes = $post->likes + 1;
      $post->save();
      return $response->withJson([
        'likes' => $post->likes,
      ]);
    } else {
      return $response->withJson([])->withStatus(500);
    }
  }
}
