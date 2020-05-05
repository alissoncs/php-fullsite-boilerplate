<?php
namespace App\Website;

use App\Website\BaseWebsiteController;
use App\Model\PlanoCategoria;
use App\Model\Plano;
use App\Model\PlanoInteresse;
use App\Model\FormSimulacaoPlano;
use App\Model\PontoVenda;

class ServicosOnlineController extends BaseWebsiteController
{
  public function criacao_usuario($request, $response, $args)
  {
    return $this->container['view']->render($response, 'criacao-usuario-web.twig');
  }
  public function area_restrita($request, $response, $args)
  {
    return $this->container['view']->render($response, 'area-restrita.twig');
  }

}
