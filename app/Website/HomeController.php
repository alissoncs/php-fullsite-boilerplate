<?php

namespace App\Website;

use App\Website\BaseWebsiteController;
use App\Model\PlanoCategoria;
use App\Model\Plano;
use App\Model\Banner;
use App\Model\Post;
use App\Model\Noticia;
use App\Model\FormNewsletter;
use App\Model\ModuloPagina;
use App\Model\HomeDiferencial;

class HomeController extends BaseWebsiteController
{
  public function home($request, $response, $args)
  {

    if ($request->isXhr() && $request->getQueryParam('s')) {
      return $this->search($request, $response, $args);
    }

    $planos = PlanoCategoria::encontreSeuPlano();

    $sliders = Banner::where('status', 'ativo')->where('posicao', 'slider_home')->orderBy('ordem', 'asc')->limit(4)->get();
    $bannersServicos = Banner::where('status', 'ativo')->where('posicao', 'servicos_home')->orderBy('ordem', 'asc')->limit(3)->get();

    $blog = Post::with('categoria')
      ->where('status', 'ativo')
      ->orderBy('created_at', 'desc')
      ->limit(2)
      ->get();

    $noticias = Noticia::where('status', 'ativo')
      ->limit(4)
      ->orderBy('created_at', 'desc')
      ->get();

    $diferenciais = HomeDiferencial::where('status', 'ativo')->orderBy('ordem', 'asc')->get();

    return $this->container['view']->render($response, 'home.twig', [
      'planos_categorias' => $planos,
      'sliders' => $sliders,
      'banners_servicos' => $bannersServicos,
      //'blog' => $blog,
      'blog' => false,
      //'noticias' => $noticias,
      'noticias' => false,
      'diferenciais' => $diferenciais,
      // 'blog_count' => $blogCount
    ]);
  }

  public function submitNewsletter($request, $response, $args)
  {

    $payload = $request->getParsedBody();
    if ($request->isXhr() && !empty($payload['nome']) && !empty($payload['email'])) {
      $e = FormNewsletter::where('email', $payload['email'])->first();
      if ($e) {
        return $response->withStatus(204);
      }

      $created = FormNewsletter::create([
        'nome' => trim($payload['nome']),
        'email' => trim(strtolower($payload['email'])),
      ]);
      return $response->withStatus(201);
    }

    return $response->withStatus(500);
  }

  public function search($request, $response, $args)
  {
    $service = $this->container->get('service.api_medicos');

    $term = $request->getQueryParam('s');

    try {
      $medicos = $service->fetchRedePrestadores(array(
        'nome' => $term,
      ));
    } catch (\Exception $e) {
      $medicos = null;
    }

    $planosCategorias = PlanoCategoria::where('status', 'ativo')->where('titulo', 'LIKE', '%' . $term . '%')->limit(2)->get();
    $planos = Plano::with(['categoria'])
      ->where('status', 'ativo')
      ->where('titulo', 'LIKE', '%' . $term . '%')
      ->limit(7)
      ->get();
    $blogPosts = Post::with('categoria')->where('status', 'ativo')->where('titulo', 'LIKE', '%' . $term . '%')->limit(2)->get();

    $paginas = ModuloPagina::where('status', 'ativo')
    ->where(function($qb) use($term) {
      $qb->where('titulo', 'LIKE', '%' . $term . '%');
      $qb->orWhere('descricao', 'LIKE', '%' . $term . '%');
      $qb->orWhere('conteudo', 'LIKE', '%' . $term . '%');
    })->limit(5)->get();

    $options = [];

    if ($planosCategorias) {
      foreach ($planosCategorias as $plano) {
        $options[] = [
          'titulo' => 'Categoria de plano: ' . $plano->titulo,
          'link' => $request->getUri()->getBasePath() . '/planos/' . $plano->slug,
        ];
      }
    }

    if ($planos) {
      foreach ($planos as $plano) {
        $options[] = [
          'titulo' => 'Plano: ' . $plano->titulo,
          'link' => $request->getUri()->getBasePath() . '/planos/' . $plano->categoria->slug . '/' . $plano->slug,
        ];
      }
    }

    if ($blogPosts) {
      foreach ($blogPosts as $post) {
        $options[] = [
          'titulo' => 'Blog Viver Bem: ' . $post->titulo,
          'link' => $request->getUri()->getBasePath() . "/blog/" . $post->categoria->slug  . "/$post->slug",
        ];
      }
    }

    if ($paginas) {
      foreach ($paginas as $p) {
        $options[] = [
          'titulo' => 'Página: ' . $p->titulo,
          'link' => $request->getUri()->getBasePath() . '/' . ltrim($p->getUrlTreeBackward(), '/'),
        ];
      }
    }

    $noticias = Noticia::with('categoria')
      ->where('status', 'ativo')
      ->where('titulo', 'LIKE', '%' . $term . '%')
      ->limit(2)->get();

    if ($noticias) {
      foreach ($noticias as $post) {
        $options[] = [
          'titulo' => 'Notícia: ' . $post->titulo,
          'link' => $request->getUri()->getBasePath() . "/noticias/" . $post->categoria->slug  . "/$post->slug",
        ];
      }
    }

    $totalMedicos = 0;
    if (isset($medicos) && !empty($medicos['rede'])) {
      $totalMedicos = count($medicos['rede']);
      $medicos = array_slice($medicos['rede'], 0, 3);
    } else {
      $medicos = [];
    }

    $data = [
      'has' => !empty($medicos) && !empty($options) && count($medicos) && count($options),
      'options' => $options,
      'medicos' => $medicos,
      'total_medicos' => $totalMedicos,
      'term' => $term,
    ];

    return $this->container['view']->render($response, 'busca-resultados.twig', $data);
  }
}
