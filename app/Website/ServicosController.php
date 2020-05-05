<?php
namespace App\Website;

use App\Website\BaseWebsiteController;

class ServicosController extends BaseWebsiteController
{
  public function home($request, $response, $args)
  {
    return $this->container['view']->render($response, 'servicos.twig');
  }

  public function categoria($request, $response, $args)
  {
    return $this->container['view']->render($response, 'planos-categoria.twig');
  }

  public function detalhe($request, $response, $args)
  {
    return $this->container['view']->render($response, 'servicos-detalhe.twig');
  }
}
