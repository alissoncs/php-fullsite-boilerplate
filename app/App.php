<?php

namespace App;

use Slim\App as Application;
use Slim\Container;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Slim\Http\Uri;
use Slim\Http\Environment;
use Slim\Http\Request;
use Ahc\Jwt\JWT;

use App\Service\UploadService;
use App\Service\ApiMedicosService;
use App\Middleware\AdminSharedMiddleware;

use App\Config\TwigFilters;
use App\Config\ErrorHandler;
use App\Config\DatabaseConfig;
use App\Admin\AdminRoutes;
use App\Website\HomeController;
use App\Website\BlogController;
use App\Website\MedicosController;
use App\Website\NoticiasController;
use App\Website\PlanosController;
use App\Website\FaleConoscoController;
use App\Website\ModuloPaginasController;
use App\Website\UnidadesController;

use App\Model\Config;
use App\Model\AcessoRapido;
use App\Model\ModuloPagina;
use App\Model\PlanoCategoria;
use App\Model\PostCategoria;
use App\Website\ServicosOnlineController;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class App extends Application
{
  public function __construct($container = [])
  {
    $request = Request::createFromEnvironment(new Environment($_SERVER));

    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

    $innerPaths = ['UnimedVS', 'unimed-site'];
    foreach ($innerPaths as $innerPath) {
      if (FALSE !== strpos($_SERVER['REQUEST_URI'], $innerPath)) {
        $innerPath = '/' . $innerPath;
        $uri = $request->getUri()->withBasePath($innerPath);
        $uri = $uri->withPath(str_replace($innerPath, '', $uri->getPath()));
        $uri = $uri->withBasePath($innerPath);
        $request = $request->withUri($uri);
        break;
      }
    }

    $log = new Logger('site');
    $log->pushHandler(new StreamHandler(ROOT . '/logs/site-info.log', Logger::INFO));
    $log->pushHandler(new StreamHandler(ROOT . '/logs/site-error.log', Logger::ERROR));

    $adminLogger = new Logger('admin');
    $adminLogger->pushHandler(new StreamHandler(ROOT . '/logs/admin-info.log', Logger::INFO));
    $adminLogger->pushHandler(new StreamHandler(ROOT . '/logs/admin-error.log', Logger::ERROR));

    $configuration = [
      'settings' => [
        'displayErrorDetails' => true,
        "determineRouteBeforeAppMiddleware" => true,
      ],
      'request' => $request,
      'site_logger' => $log,
      'logger' => $log,
      'admin_logger' => $adminLogger,
    ];
    $c = new Container($configuration);
    parent::__construct($c);
  }

  public function run($silent = false)
  {
    session_start();

    $jwt = new JWT(',Hc9_KR=btnA&!&', 'HS256', 3600 * 24, 10);

    $container = $this->getContainer();

    // database
    $dbConfig = new DatabaseConfig();
    $dbConfig->setup($container);
    $container['config'] = Config::getMapped();


    $container['service.upload'] = new UploadService();
    $container['service.api_medicos'] = new ApiMedicosService($container);

    $errorHandler = new ErrorHandler();
    $errorHandler->setup($container);

    $container['view'] = function ($c) {
      $view = new Twig(__DIR__ . '/views', [
        'auto_reload' => true,
        'cache' => false,
      ]);
      $uri = $c->get('request')->getUri();
      $view->getEnvironment()->addGlobal('config', $c->get('config'));
      $view->getEnvironment()->addGlobal('estados_lista', [
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins',
      ]);
      $view->getEnvironment()->addGlobal('acesso_rapido', AcessoRapido::menu());
      $view->getEnvironment()->addGlobal('menu_planos', PlanoCategoria::menu());
      $view->getEnvironment()->addGlobal('menu_blog_categorias', PostCategoria::menuLimit5());
      $view->getEnvironment()->addGlobal('menu_header', ModuloPagina::menuHeader());
      $view->getEnvironment()->addGlobal('menu_footer', ModuloPagina::menuFooter());
      $view->addExtension(new TwigExtension($c->get('router'), $uri));
      return $view;
    };
    $filters = new TwigFilters();
    $filters->setup($container);

    $this->options('/{routes:.+}', function ($request, $response, $args) {
      return $response;
    });

    $this->add(function ($req, $res, $next) {
      $response = $next($req, $res);
      return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });


    $this->group('/admin', function (Application $app) use ($jwt) {
      $adminRoutes = new AdminRoutes($jwt);
      $adminRoutes->apply($app);
    })->add(new AdminSharedMiddleware($jwt));

    $this->get('/', HomeController::class . ':home')->setName('home');
    $this->get('/busca', HomeController::class . ':home')->setName('busca');
    $this->post('/newsletters', HomeController::class . ':submitNewsletter')->setName('newsletters');

    $this->get('/encontre-{any}-medico', MedicosController::class . ':encontre')->setName('encontre_um_medico');

    $this->get('/medicos', MedicosController::class . ':search')->setName('medicos');
    $this->get('/medicos/busca', MedicosController::class . ':search')->setName('medicos_busca');
    $this->get('/medicos/detalhe/{id}', MedicosController::class . ':detalhe')->setName('medicos_detalhe');

    $this->get('/medicos/metadata', MedicosController::class . ':metadata')->setName('medicos_metadata');
    $this->get('/medicos/metadata/estados', MedicosController::class . ':estados');
    $this->get('/medicos/metadata/planos', MedicosController::class . ':planos');
    $this->get('/medicos/metadata/cidades', MedicosController::class . ':cidades');
    $this->get('/medicos/metadata/tipoprestador', MedicosController::class . ':tipoprestador');

    $this->get('/encontre-seu-plano/{categoria}', PlanosController::class . ':encontre');
    $this->post('/encontre-seu-plano/{categoria}', PlanosController::class . ':encontre');
    $this->get('/encontre-seu-plano/{categoria}/whatsapp', PlanosController::class . ':whatsapp');

    $this->get('/planos/{categoria}/{plano}', PlanosController::class . ':detalhe');
    $this->get('/planos/{categoria}', PlanosController::class . ':categoria');
    $this->get('/planos', PlanosController::class . ':planos')->setName('planos');
    $this->post('/planos/simulacao', PlanosController::class . ':simulacao');

    $this->get('/servicos-online/area-restrita', ServicosOnlineController::class . ':area_restrita')->setName('criacao_usuario_web');
    $this->get('/servicos-online/criacao-usuario-web', ServicosOnlineController::class . ':criacao_usuario')->setName('criacao_usuario_web');
    $this->get('/unidades', UnidadesController::class . ':unidades')->setName('unidades');

    $this->get('/blog', BlogController::class . ':home')->setName('blog');
    $this->get('/blog/{categoria}', BlogController::class . ':categoria')->setName('blog_categoria');
    $this->get('/blog/{categoria}/{post}', BlogController::class . ':post')->setName('post');
    $this->post('/blog/likes/{post}', BlogController::class . ':likes');

    $this->get('/noticias', NoticiasController::class . ':home')->setName('blog');
    $this->get('/noticias/{categoria}', NoticiasController::class . ':home')->setName('blog_categoria');
    $this->get('/noticias/{categoria}/{post}', NoticiasController::class . ':post')->setName('post');

    $this->get('/fale-conosco', FaleConoscoController::class . ':home')->setName('fale_conosco');
    $this->post('/fale-conosco/submit', FaleConoscoController::class . ':submit');
    $this->get('/fale-conosco/ouvidoria', FaleConoscoController::class . ':ouvidoria');
    $this->post('/fale-conosco/ouvidoria/submit', FaleConoscoController::class . ':submitOuvidoria');
    $this->get('/fale-conosco/atendimento-particular', FaleConoscoController::class . ':atendimentoParticular');
    $this->post('/fale-conosco/atendimento-particular/submit', FaleConoscoController::class . ':submitAtendimentoParticular');
    $this->get('/fale-conosco/medico-cooperado', FaleConoscoController::class . ':medicoCooperado');
    $this->post('/fale-conosco/medico-cooperado/submit', FaleConoscoController::class . ':submitMedicoCooperado');
    $this->get('/fale-conosco/orientacao-farmaceutica', FaleConoscoController::class . ':orientacaoFarmaceutica');
    $this->post('/fale-conosco/orientacao-farmaceutica/submit', FaleConoscoController::class . ':submitOrientacaoFarmaceutica');
    $this->get('/fale-conosco/visita-institucional', FaleConoscoController::class . ':visitaInstitucional');
    $this->post('/fale-conosco/visita-institucional/submit', FaleConoscoController::class . ':submitVisitaInstitucional');

    $this->get('/{levels:.+}', ModuloPaginasController::class . ':find');

    $this->map(['POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($req, $res) {
      $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
      return $handler($req, $res);
    });

    return parent::run($silent);
  }
}
