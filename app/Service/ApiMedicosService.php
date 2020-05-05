<?php

namespace App\Service;

use Psr\Container\ContainerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Exception;
use SoapClient;

use Illuminate\Support\Facades\DB;
use App\Admin\Controller\BaseController;
use App\Model\MapeamentoEspecialidade;

class ApiMedicosService
{

  private $client;

  private $apiMedicosUrl = 'https://ms3.tapcore.com.br/mssuite/plataforma_beneficiario/rede_credenciada/servicos/versao4/';

  private $portalUrlByCpf = 'http://portal.vs.unimed.com.br:9003/U_WSBOT005.APW?cpf={cpf}';
  private $soapBuscaPlanoCliente = 'http://portal.vs.unimed.com.br:9003/ws/WSBUSCAPLANO.apw?WSDL';

  private $plano = 'RB17474389150'; // TIAGO, ALTERAR ISSO SE NECESSARIO
  // private $plano = 'RB23478829170'; // TIAGO, ALTERAR ISSO SE NECESSARIO
  // plan=

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;

    if (!empty($container->get('config')['BUSCA_MEDICOS_PLANO_PADRAO'])) {
    }

    if (!empty($container->get('config')['BUSCA_MEDICOS_BASE_URL'])) {
    }

    $this->logger = $this->container->get('logger');

    $this->client = new Client([
      'headers' => [
        'Authorization' => 'Basic NmU3YjQ3YjcxZmUwMGM0ZjNmOTczNzU2ZjYwYzlmYjUzMGNlMWNjOA==',
      ],
    ]);
  }

  public function performSearch(array $data = array())
  {
    $logger = $this->logger;
    $logger->info('Buscando médicos, termos: ' . json_encode($data));

    $result = $this->fetchRedePrestadores($data);

    if (is_array($result) && isset($result['rede']) && is_array($result['rede'])) {
      $result = $result['rede'];
      return $result;
    } else {
      return [];
    }
  }

  public function fetchMetadata($url, array $options = array())
  {
    try {
      $this->logger->info('MEDICOS API -> ' . $url . ' -> ' . json_encode($options));

      $response = $this->client->request('GET', $url, [
        // 'query' => $options,
      ]);
      $result = $response->getBody()->getContents();
      return (array) json_decode($result);
    } catch (ClientException $e) {
      $response = $e->getResponse();
      $body = $response ? $response->getBody()->getContents() : null;
      $this->logger->error('MEDICOS API -> Falha ao carregar ' . $url . ' -> ' . $body . ' -> Message: ' . $e->getMessage());
      throw new Exception('MEDICOS API -> Falha ao carregar ' . $url);
    }
  }

  public function fetchCidades($options = array())
  {
    $url = $this->apiMedicosUrl . 'cidades.php?id_operadora=94&id_estados=RS';

    $result = $this->fetchMetadata($url, $options);

    if ($result && !empty($result['cidades'])) {
      $result = $result['cidades'];
      return $result;
    }

    return [];
  }


  public function fetchPlanos() {
    $url = $this->apiMedicosUrl . 'planos.php?id_operadoras=94';

    $result = $this->fetchMetadata($url, array());

    if ($result && !empty($result['planos'])) {
      $result = $result['planos'];
      return $result;
    }

    return [];
  }


  public function fetchTipoPrestador($cidade)
  {
    $url = $this->apiMedicosUrl . 'tipo_prestador.php?bairro=&dist=&id_cidade=' . $cidade . '&id_estado=RS&id_operadoras=94&id_plano=' . $this->plano . '&lat=&lon=';

    $result = $this->fetchMetadata($url);
    if ($result && !empty($result['tipo_prestador'])) {
      $result = $result['tipo_prestador'];
      return $result;
    }

    return [];
  }
  public function fetchEspecialidades($cidade)
  {
    $url =  $this->apiMedicosUrl . 'especialidades.php?bairro=&dist=&id_cidade=' . $cidade . '&id_estado=RS&id_operadoras=94&lat=&lon=&plan=' . $this->plano . '&tpprest=';

    $especialidades = $this->fetchMetadata($url);

    if ($especialidades && !empty($especialidades['especialidade'])) {
      $especialidades = $especialidades['especialidade'];
      return $especialidades;
    }

    return [];
  }
  public function fetchRedePrestadores($dados)
  {
    $dados['cidade'] = !empty($dados['cidade']) ? $dados['cidade'] : '';
    $dados['tipo_prestador'] = !empty($dados['tipo_prestador']) ? $dados['tipo_prestador'] : '';
    $dados['especialidade'] = !empty($dados['especialidade']) ? $dados['especialidade'] : '';
    $dados['nome'] = !empty($dados['nome']) ? $dados['nome'] : '';
    if (empty($dados['nome'])) {
      $dados['nome'] = !empty($dados['termo']) ? $dados['termo'] : '';
    }

    $cidade = $dados['cidade'];
    $tipo_prestador = $dados['tipo_prestador'];
    $especialidade = $dados['especialidade'];
    $nome = $dados['nome'];
    $plano = !empty($dados['plano']) ? $dados['plano'] : $this->plano;

    $url = $this->apiMedicosUrl . 'busca_rede.php?bair=&cid=' . $cidade . '&dist=&espagr=S&espec=' . $especialidade . '&est=RS&isQA=S&isweb=1&lat=&nome=' . $nome . '&ope=94&plan=' . $plano . '&secoes=N&subespec=&tip=' . $tipo_prestador;
    $resultadoBusca = $this->fetchMetadata($url)['rede'];

    $especialidadesBuscar = $this->getMapeamentoEspecialidadeByTag($nome);
    //print_r($especialidadesBuscar);

    foreach ($especialidadesBuscar as $especialidade) {

      //echo $especialidade['codigo_cbo'];
      $urlEspec = $this->apiMedicosUrl . 'busca_rede.php?bair=&cid=' . $cidade . '&dist=&espagr=S&espec=' . $especialidade['codigo_cbo'] . '&est=RS&isQA=S&isweb=1&lat=&nome=&ope=94&plan=' . $plano . '&secoes=N&subespec=&tip=' . $tipo_prestador;
      $resultadoBuscaEspec = $this->fetchMetadata($urlEspec);

      $temp = $resultadoBusca;
      $resultadoBusca = array_merge($temp, $resultadoBuscaEspec["rede"]);
    }
    //print_r($resultadoBusca);
    $retorno["rede"] = $resultadoBusca;
    return $retorno;
    //return $this->fetchMetadata($url);
  }

  public function fetchDetalhePrestador($idPrestador)
  {
    $url = $this->apiMedicosUrl . 'prestador.php?espagr=S&ope=94&id_prest=' . $idPrestador;

    return $this->fetchMetadata($url);
  }

  public function getCodigoPlanoByNome($nome) {
    $planos = $this->fetchPlanos();

    if ($planos && !empty($planos)) {
      // foreach($)
      foreach($planos as $p) {
        if ($p->descricao === $nome) {
          return $p->codigo_legado;
        }
      }
    }
    return null;
  }

  public function getCarteiraUnimedByCpf($cpf) {
    $url = str_replace('{cpf}', $cpf, $this->portalUrlByCpf);

    $result = $this->fetchMetadata($url);

    if (is_array($result) && count($result) > 0) {
      $result = (array) $result[0];
      $obj = [
        'carteiraUnimed' => $result['carteiraUnimed'],
        'nome' => $result['nome'],
      ];
      return $obj;
    }

    return null;
  }

  public function getPlanoByNumeroCarteira($carteiraUnimed) {

    try {
      $client = new SoapClient($this->soapBuscaPlanoCliente);
      $cliente = $client->BUSCAR(array('CARTEI' => $carteiraUnimed));
    } catch(Exception $e) {
      $this->logger->error('MEDICOS API -> getPlanoByNumeroCarteira(), carteiraUnimed: ' . $carteiraUnimed . ' (' . $e->getMessage(). ')');
      throw $e;
    }

    if ($cliente && isset($cliente)) {
      $plano = $cliente->BUSCARRESULT->STLISTA->PLANO;
      if (empty($plano)) {
        $plano = null;
      }

      if (empty($cliente->BUSCARRESULT->STLISTA->NOME)) {
        return null;
      }

      $obj = [
        'nome' => $cliente->BUSCARRESULT->STLISTA->NOME,
        'plano' => $plano,
        'cliente' => [
          'nome' => $cliente->BUSCARRESULT->STLISTA->NOME,
        ],
      ];
      return $obj;
    }

    return null;
  }

  public function getMapeamentoEspecialidadeByTag($tag) {
    return MapeamentoEspecialidade::where('tags_busca', 'LIKE', '%' . $tag . '%')->orWhere('descricao', 'LIKE', '%' . $tag . '%')->get();
  }

  public function listAllEspecialidades() {
    $result = [];
    $esp = $this->fetchEspecialidades('');
    foreach($esp as &$e) {
      $result[] = [
        // 'cidade' => $c,
        'especialidades' => $e,
      ];
    }
    return $result;
  }

}
