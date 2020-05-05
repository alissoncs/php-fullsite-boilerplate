<?php

namespace App\Website;

use App\Website\BaseWebsiteController;
use Exception;

class MedicosController extends BaseWebsiteController
{

  private $limitPerPage = 10;
  private $cpfCount = 11;

  public function encontre($request, $response, $args)
  {
    return $this->container['view']->render($response, 'encontre-um-medico.twig', []);
  }

  public function search($request, $response, $args)
  {
    $service = $this->container->get('service.api_medicos');

    $termo = $request->getQueryParam('termo');
    $cidade = $request->getQueryParam('cidade');
    $tipo_prestador = $request->getQueryParam('tipo_prestador');
    $especialidade = $request->getQueryParam('especialidade');
    $urgencia = $request->getQueryParam('urgencia');
    $documento = $request->getQueryParam('documento');

    if ($documento && !empty($documento)) {
      $documento = preg_replace('/[^0-9]/', '', $documento);
    }

    $sourceQuery = $request->getQueryParams();

    $cliente = null;
    $plano = null;

    try {
      if ($documento && !empty($documento)) {

        if (strlen($documento) === $this->cpfCount) {
          $cliente = $service->getCarteiraUnimedByCpf($documento);

          if (!$cliente || empty($cliente['carteiraUnimed'])) {
            return $this->container['view']->render($response, 'medicos-busca.twig', [
              'error' => 'Não encontramos nenhum cliente com o CPF/Nº Carteirinha informado: ' . $documento . '',
              'source_query' => $sourceQuery,
            ]);
          }
          $plano = $service->getPlanoByNumeroCarteira($cliente['carteiraUnimed'] . '1');

          if (!$plano || empty($plano['plano'])) {
            return $this->container['view']->render($response, 'medicos-busca.twig', [
              'error' => 'O cliente informado não possui plano na Unimed Vale dos Sinos. CPF/Nº Carteirinha: ' . $documento,
              'source_query' => $sourceQuery,
            ]);
          }
        } else {
          $plano = $service->getPlanoByNumeroCarteira($documento);
          if (!$plano) {
            return $this->container['view']->render($response, 'medicos-busca.twig', [
              'error' => 'O cliente informado não possui plano. CPF/Nº Carteirinha: ' . $documento,
              'source_query' => $sourceQuery,
              ]);
            }
          }
          $cliente = $plano['cliente'];
      }

      $query = [];
      $query['termo'] = $termo;
      $query['cidade'] = $cidade;
      $query['urgencia'] = $urgencia;
      $query['tipo_prestador'] = $tipo_prestador;
      $query['especialidade'] = $especialidade;

      if ($plano) {
        $codigoPlano = $service->getCodigoPlanoByNome($plano['plano']);
        $query['plano'] = $codigoPlano;
      }///

      // carrega plano pelo nome

      $result = $service->performSearch($query);

      $cidades = $service->fetchCidades();

      $prestadores = $especialidades = null;

      if (!empty($cidade)) {
        $prestadores = $service->fetchTipoPrestador($cidade);
        $especialidades = $service->fetchEspecialidades($cidade);
      }

      if (empty($result) && $plano) {
        return $this->container['view']->render($response, 'medicos-busca.twig', [
          'error' => 'Não encontramos nenhum prestador para seu plano: ' . $plano['plano'] . '. Cliente: ' . $cliente['nome'],
          'source_query' => $sourceQuery,
          ]);
      }

      $pagina = (int) $request->getQueryParam('pagina', 0);

      $outputView = [
        'result' => $this->mapResult($result, $pagina),
        'total_count' => count($result),
        'page_count' => $this->limitPerPage,
        'total_pages' => ceil(count($result) / $this->limitPerPage),
        'current_page' => $pagina,
        'source_query' => $request->getQueryParams(),
        'query' => $query,
        'is_cpf' => $documento && strlen($documento) === $this->cpfCount,
        'basica' => !empty($termo),
        'cidades' => $cidades,
        'prestadores' => $prestadores,
        'especialidades' => $especialidades,
        'cliente' => $cliente,
        'plano' => $plano,
        'error' => null,
      ];

      if ($request->isXhr()) {
        return $this->container['view']->render($response, 'medicos-busca-ajax.twig', $outputView);
      }

      return $this->container['view']->render($response, 'medicos-busca.twig', $outputView);
    } catch (Exception $e) {
      return $this->container['view']->render($response, 'medicos-busca.twig', [
        'error' => 'Estamos melhorando nosso serviço de busca de médicos. Tente mais tarde!',
        'internal_error' => $e->getMessage(),
        'source_query' => $sourceQuery,
      ]);
    }
  }

  private function mapResult($data, $page = 0) {
    return array_slice($data, $page * $this->limitPerPage, $this->limitPerPage);
  }

  public function metadata($request, $response, $args)
  {
    $query = $request->getQueryParams();

    $service = $this->container->get('service.api_medicos');

    try {
      $result = $service->fetchMetadata($query);
    } catch (\Exception $e) {
      return $response->withJson([
        'detail' => $e->getMessage(),
      ], 500);
    }

    return $response->withJson([
      'data' => $result,
      'parameters' => $query,
    ]);
  }

  public function planos($request, $response, $args)
  {
    $service = $this->container->get('service.api_medicos');

    try {
      $result = $service->fetchPlanos();
      if ($result && !empty($result['planos'])) {
        $result = $result['planos'];
      }
    } catch (\Exception $e) {
      return $response->withJson([
        'detail' => $e->getMessage(),
      ], 500);
    }

    return $response->withJson([
      'data' => $result,
      'parameters' => [],
    ]);
  }

  public function estados($request, $response, $args)
  {
    $query = $request->getQueryParams();

    $service = $this->container->get('service.api_medicos');

    try {
      $result = $service->fetchEstados($query);
      if ($result && !empty($result['estados'])) {
        $result = $result['estados'];
      }
    } catch (\Exception $e) {
      return $response->withJson([
        'detail' => $e->getMessage(),
      ], 500);
    }

    return $response->withJson([
      'data' => $result,
      'parameters' => $query,
    ]);
  }

  public function cidades($request, $response, $args)
  {
    $query = $request->getQueryParams();

    $service = $this->container->get('service.api_medicos');

    try {
      $result = $service->fetchCidades($query);
    } catch (\Exception $e) {
      return $response->withJson([
        'detail' => $e->getMessage(),
      ], 500);
    }

    return $response->withJson([
      'data' => $result,
      'parameters' => $query,
    ]);
  }

  public function tipoprestador($request, $response, $args)
  {
    $query = $request->getQueryParams();

    $service = $this->container->get('service.api_medicos');

    try {
      $result = $service->fetchTipoPrestador($query['cidade']);
      $especialidades = $service->fetchEspecialidades($query['cidade']);
    } catch (\Exception $e) {
      return $response->withJson([
        'detail' => $e->getMessage(),
      ], 500);
    }

    return $response->withJson([
      'tipo_prestador' => $result,
      'especialidade' => $especialidades,
      'parameters' => $query,
    ]);
  }

  public function detalhe($request, $response, $args)
  {
    $id = $args['id'];

    $data = [
      'id' => $id,
    ];

    $service = $this->container->get('service.api_medicos');
    $prestador = $service->fetchDetalhePrestador($id);
    return $this->container['view']->render($response, 'components/medico-planos-atendidos.twig', $data);
  }
}
