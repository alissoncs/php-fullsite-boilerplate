<?php
namespace App\Website;

use App\Model\Unidade;
use App\Website\BaseWebsiteController;

class UnidadesController extends BaseWebsiteController
{
  public function unidades($request, $response, $args) {
    $unidades = Unidade::where('status', 'ativo')->orderBy('ordem', 'asc')->limit(100)->get();
    $u = $unidades->toArray();

    foreach($u as &$unidade) {
      if (!empty($unidade['horarios_exames'])) {
        $unidade['horarios_exames'] = json_decode($unidade['horarios_exames']);
      }
    }

    return $this->container['view']->render($response, 'unidades.twig', [
      'unidades' => $u,
    ]);
  }
}
