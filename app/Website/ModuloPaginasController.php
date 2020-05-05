<?php
namespace App\Website;

use App\Website\BaseWebsiteController;
use App\Model\ModuloPagina;
use App\Model\Post;

class ModuloPaginasController extends BaseWebsiteController
{
  public function find($request, $response, $args)
  {
    $split = explode('/', $args['levels']);


    $level1 = $split[0];
    $level2 = isset($split[1]) ? $split[1] : null;
    $level3 = isset($split[2]) ? $split[2] : null;
    $level4 = isset($split[3]) ? $split[3] : null;


    $pagina = ModuloPagina::where('status', 'ativo')->where('slug', $level1)->first();
    if (!$pagina) {
      return $this->notFound($response);
    }

    $level1 = $pagina;


    if ($level2) {
      $pagina = ModuloPagina::where('status', 'ativo')->where('slug', $level2)->where('modulo_pagina_id', $level1->id)->first();
      $level2 = $pagina;
    }
    if (!$pagina) {
      return $this->notFound($response);
    }


    if ($level3) {
      $pagina = ModuloPagina::where('status', 'ativo')->where('slug', $level3)->where('modulo_pagina_id', $level2->id)->first();
      $level3 = $pagina;
    }

    if ($level4) {
      $pagina = ModuloPagina::where('status', 'ativo')->where('slug', $level4)->where('modulo_pagina_id', $level3->id)->first();
      $level4 = $pagina;
    }


    if (!$pagina) {
      return $this->notFound($response);
    }

    return $this->container['view']->render($response, 'modulo-pagina.twig', [
      'params' => $pagina,
      'conteudo' => $pagina->conteudo ? json_decode($pagina->conteudo) : null,
      'pagina' => $pagina,
      'level1' => $level1,
      'level2' => $level2,
      'level3' => $level3,
      'level4' => $level4,
      'navigation' => '',
      'tree' => ModuloPagina::treeById($level1->id),
    ]);
  }

  private function notFound($response) {
    $response = $response->withStatus(404);
    return $this->container['view']->render($response, 'modulo-pagina-404.twig');
  }

  private function noContent($response, $childs) {
    $response = $response->withStatus(204);
    return $this->container['view']->render($response, 'modulo-pagina-empty.twig', [
      'childs' => $childs,
      'paginas' => $childs,
    ]);
  }
}
