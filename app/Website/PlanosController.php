<?php
namespace App\Website;

use App\Website\BaseWebsiteController;
use App\Model\PlanoCategoria;
use App\Model\Plano;
use App\Model\PlanoInteresse;
use App\Model\FormSimulacaoPlano;
use App\Model\PontoVenda;
use App\Util\Email;
use App\Model\Config;

class PlanosController extends BaseWebsiteController
{
  public function planos($request, $response, $args)
  {
    $categorias = PlanoCategoria::where('status', 'ativo')
      ->orderBy('ordem', 'asc')
      ->get();

    return $this->container['view']->render($response, 'planos.twig', [ 'categorias' => $categorias ]);
  }

  public function categoria($request, $response, $args)
  {

    $slug = $args['categoria'];

    $categoria = PlanoCategoria::where('slug', $slug)->where('status', 'ativo')->first();

    if (!$categoria) {
      return $response->withRedirect('/404');
    }

    $planos = Plano::where('plano_categoria_id', $categoria->id)->where('status', 'ativo')->orderBy('ordem', 'asc')->get();

    return $this->container['view']->render($response, 'planos-categoria.twig', [
      'categoria' => $categoria,
      'planos' => $planos,
      ]);
    }

  public function detalhe($request, $response, $args)
  {
    $slug = $args['plano'];

    $plano = Plano::with('categoria')->where(function($q) use($slug) {
      $q->where('slug', $slug)->orWhere('id', $slug);
    })->where('status', 'ativo')->first();

    $pontos = PontoVenda::where('status', 'ativo')->orderBy('ordem', 'asc')->get();

    if (!$plano) {
      return $response->withRedirect('/404');
    }

    return $this->container['view']->render($response, 'planos-detalhe.twig', [
      'plano' => $plano,
      'pontos_venda' => $pontos,
      'categoria' => $plano->categoria,
    ]);
  }

  public function encontre($request, $response, $args)
  {
    $slug = $args['categoria'];

    $view = [
      'categoria_slug' => $slug,
    ];

    if (!empty($_SESSION['form_interesse'])) {
      // TODO ??
      $view['form_data'] = json_decode($_SESSION['form_interesse']);
    }

    $categoria = PlanoCategoria::where('slug', $slug)->where('status', 'ativo')->first();

    if (!$categoria) {
      return $response->withRedirect('/404');
    }
    $view['categoria'] = $categoria;

    $query = Plano::where('encontre_exibir', '1')->where('plano_categoria_id', $categoria->id)->orderBy('ordem', 'ASC');
    $view['primarios'] = $query->where('atencao_primaria', '1')->get();

    $query = Plano::where('encontre_exibir', '1')->where('plano_categoria_id', $categoria->id)->orderBy('ordem', 'ASC');
    $view['planos'] = $query->where('atencao_primaria', '!=', '1')->get();

    if ($request->getMethod() === 'POST') {
      $data = $request->getParsedBody();

      $interesse = PlanoInteresse::exists($data, $categoria->titulo);
      if (!$interesse) {
        $interesse = new PlanoInteresse($data);
        $interesse->plano_titulo = $categoria->titulo;
        $interesse->save();
      }

      $_SESSION['enable_interesse'] = 'true';
      // $_SESSION['form_interesse'] = json_encode($data);

      $view['form_data'] = $data;
      $view['enabled'] = true;
      $view['interesse'] = $interesse;
      $view['interesse_id'] = $interesse->id;

      return $this->container['view']->render($response, 'encontre-seu-plano.twig', $view);
    }

    return $this->container['view']->render($response, 'encontre-seu-plano.twig', $view);
  }

  public function whatsapp($request, $response, $args) {
    // $id = $args['interesse_id'];
    $redirect = $request->getQueryParam('redirect_to', null);
    $id = $request->getQueryParam('interesse_id', null);
    $planoid = $request->getQueryParam('plano_id', null);

    if ($id && $planoid) {
      $interesse = PlanoInteresse::where('id', $id)->first();
      if ($interesse) {
        $plano = Plano::where('id', $planoid)->first();
        if ($plano) {
          $interesse->plano_id = $plano->id;
          $interesse->plano_whatsapp_click = $plano->titulo . ' | id: 1';
          $interesse->save();
        }
      }
    }

    if ($redirect) {
      return $response->withRedirect($redirect);
    } else {
      return $response->withRedirect('/');
    }
  }

  public function simulacao($request, $response, $args) {

    $data = $request->getParsedBody();

    if (!empty($data['plano_id'])) {
      $plano = Plano::find($data['plano_id']);
      if (!$plano) return $response->withRedirect('/404');
    } else {
      return $response->withRedirect('/404');
    }

    $data['status'] = 'novo';
    $register = FormSimulacaoPlano::create($data);

    $configs = Config::getMapped();

    $sent = false;
    if (!empty($configs['EMAIL_FORM_PLANOS'])) {

      $email = new Email($configs);
      $to = $configs['EMAIL_FORM_PLANOS'];
      $sent = $email->send($to, 'Interesse Simulação Plano', $data);
      if (!$sent) {
        $this->container->get('logger')->error('Falha ao enviar email simulacao plano!!!');
      }
    }

    $this->container->get('logger')->info('Form simulacao plano: ' . json_encode($data));

    return $this->container['view']->render($response, 'planos-sucesso-simulacao.twig', [
      'data' => $register,
      'plano' => $plano,
      'email_sent' => $sent,
    ]);
  }
}
