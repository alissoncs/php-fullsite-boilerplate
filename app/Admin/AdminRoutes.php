<?php

namespace App\Admin;

use App\Middleware\AdminSharedMiddleware;
use App\Model\Usuario;
use App\Admin\Controller\ConfigController;
use App\Admin\Controller\PlanosController;
use App\Admin\Controller\PostsController;
use App\Admin\Controller\PaginasCustomizadasController;
use App\Admin\Controller\UploadsController;
use App\Admin\Controller\BannersController;
use App\Admin\Controller\PontosVendaController;
use App\Admin\Controller\UnidadesController;
use App\Admin\Controller\ModuloPaginasController;
use App\Admin\Controller\HomeDiferenciaisController;
use App\Admin\Controller\AcessoRapidoController;
use App\Admin\Controller\NoticiasController;
use App\Admin\Controller\NoticiasCategoriasController;
use App\Admin\Controller\UsuariosController;
use App\Admin\Controller\NewslettersController;
use App\Admin\Controller\TagsController;
use App\Admin\Controller\MapeamentoEspecialidadeController;
use Psr7Middlewares\Middleware\TrailingSlash;

class AdminRoutes
{
  private $jwt;
  public function __construct($jwt) {
    $this->jwt = $jwt;
  }

  public function apply($app)
  {
    // $app->add(new TrailingSlash(true));

    $jwt = $this->jwt;

    $app->map(['GET', 'POST', 'DELETE', 'PATCH', 'PUT'], '/', function ($request, $response, $args) {
      return $response->withJson([
        'status' => 'ok',
        'version' => 1,
      ]);
    });

    $app->post('/auth', function($request, $response) use ($jwt) {
      $data = $request->getParsedBody();

      if (empty($data['senha']) || empty($data['email'])) {
        return $response->withStatus(422)->withJson([
          'error' => 'Email e senha obrigatórios'
        ]);
      }

      $usuario = Usuario::where('email', '=', $data['email'])->first();

      if (!$usuario) {
        return $response->withStatus(401)->withJson([
          'error' => 'Usuário não encontrado'
        ]);
      }

      if (!password_verify($data['senha'], $usuario->senha)) {
        return $response->withStatus(401)->withJson([
          'error' => 'Senha incorreta',
          'current' => $usuario->senha,
          'data' => $data['senha'],
        ]);
      }

      $token = $jwt->encode([
        'user_id' => $usuario->id,
        'user_name' => $usuario->login,
        'user_senha' => $usuario->senha,
      ]);

      return $response->withJson([
        'token' => $token,
        'user_id' => $usuario->id,
        'user_name' => $usuario->login,
        'user_email' => $usuario->email,
      ]);
    });

    $app->get('/planos-categorias', PlanosController::class . ':list');
    $app->get('/planos-categorias/{id}', PlanosController::class . ':find');
    $app->post('/planos-categorias', PlanosController::class . ':create');
    $app->put('/planos-categorias/{id}', PlanosController::class . ':update');
    $app->delete('/planos-categorias/{id}', PlanosController::class . ':delete');

    $app->get('/planos', PlanosController::class . ':listPlanos');
    $app->get('/planos/{id}', PlanosController::class . ':findPlano');
    $app->post('/planos', PlanosController::class . ':createPlano');
    $app->put('/planos/{id}', PlanosController::class . ':updatePlano');
    $app->delete('/planos/{id}', PlanosController::class . ':deletePlano');

    $app->get('/post-categorias', PostsController::class . ':list');
    $app->get('/post-categorias/{id}', PostsController::class . ':find');
    $app->post('/post-categorias', PostsController::class . ':create');
    $app->put('/post-categorias/{id}', PostsController::class . ':update');
    $app->delete('/post-categorias/{id}', PostsController::class . ':delete');

    $app->get('/posts', PostsController::class . ':listPosts');
    $app->post('/posts', PostsController::class . ':createPost');
    $app->put('/posts/{id}', PostsController::class . ':updatePost');
    $app->get('/posts/{id}', PostsController::class . ':findPost');
    $app->delete('/posts/{id}', PostsController::class . ':deletePost');

    // noticias
    $app->get('/noticias-categorias', NoticiasCategoriasController::class . ':list');
    $app->get('/noticias-categorias/{id}', NoticiasCategoriasController::class . ':find');
    $app->post('/noticias-categorias', NoticiasCategoriasController::class . ':create');
    $app->put('/noticias-categorias/{id}', NoticiasCategoriasController::class . ':update');
    $app->delete('/noticias-categorias/{id}', NoticiasCategoriasController::class . ':delete');

    $app->get('/mapeamento-especialidades/mapa', MapeamentoEspecialidadeController::class . ':mapa');
    $app->get('/mapeamento-especialidades', MapeamentoEspecialidadeController::class . ':list');
    $app->get('/mapeamento-especialidades/{id}', MapeamentoEspecialidadeController::class . ':find');
    $app->post('/mapeamento-especialidades', MapeamentoEspecialidadeController::class . ':create');
    $app->put('/mapeamento-especialidades/{id}', MapeamentoEspecialidadeController::class . ':update');
    $app->delete('/mapeamento-especialidades/{id}', MapeamentoEspecialidadeController::class . ':delete');

    $app->get('/noticias', NoticiasController::class . ':list');
    $app->get('/noticias/{id}', NoticiasController::class . ':find');
    $app->post('/noticias', NoticiasController::class . ':create');
    $app->put('/noticias/{id}', NoticiasController::class . ':update');
    $app->delete('/noticias/{id}', NoticiasController::class . ':delete');

    $app->get('/bandeiras/posicoes', BannersController::class . ':posicoes');
    $app->get('/bandeiras', BannersController::class . ':list');
    $app->get('/bandeiras/{id}', BannersController::class . ':find');
    $app->post('/bandeiras', BannersController::class . ':create');
    $app->put('/bandeiras/{id}', BannersController::class . ':update');
    $app->delete('/bandeiras/{id}', BannersController::class . ':delete');

    $app->get('/pontos-venda', PontosVendaController::class . ':list');
    $app->get('/pontos-venda/{id}', PontosVendaController::class . ':find');
    $app->post('/pontos-venda', PontosVendaController::class . ':create');
    $app->put('/pontos-venda/{id}', PontosVendaController::class . ':update');
    $app->delete('/pontos-venda/{id}', PontosVendaController::class . ':delete');

    $app->get('/paginas-customizadas', PaginasCustomizadasController::class . ':list');
    $app->get('/paginas-customizadas/{id}', PaginasCustomizadasController::class . ':find');
    $app->post('/paginas-customizadas', PaginasCustomizadasController::class . ':create');
    $app->put('/paginas-customizadas/{id}', PaginasCustomizadasController::class . ':update');
    $app->delete('/paginas-customizadas/{id}', PaginasCustomizadasController::class . ':delete');
    // $app->get('/paginas-customizadas', PaginasCustomizadasController::class . ':list');

    // unidades
    $app->get('/unidades', UnidadesController::class . ':list');
    $app->get('/unidades/{id}', UnidadesController::class . ':find');
    $app->post('/unidades', UnidadesController::class . ':create');
    $app->put('/unidades/{id}', UnidadesController::class . ':update');
    $app->delete('/unidades/{id}', UnidadesController::class . ':delete');

    // usuarios
    $app->get('/usuarios', UsuariosController::class . ':list');
    $app->get('/usuarios/{id}', UsuariosController::class . ':find');
    $app->post('/usuarios', UsuariosController::class . ':create');
    $app->put('/usuarios/{id}', UsuariosController::class . ':update');
    $app->delete('/usuarios/{id}', UsuariosController::class . ':delete');

    $app->get('/tags', TagsController::class . ':list');
    $app->get('/tags/{id}', TagsController::class . ':find');
    $app->post('/tags', TagsController::class . ':create');
    $app->put('/tags/{id}', TagsController::class . ':update');
    $app->delete('/tags/{id}', TagsController::class . ':delete');

    // home_diferenciais
    $app->get('/home-diferenciais', HomeDiferenciaisController::class . ':list');
    $app->get('/home-diferenciais/{id}', HomeDiferenciaisController::class . ':find');
    $app->post('/home-diferenciais', HomeDiferenciaisController::class . ':create');
    $app->put('/home-diferenciais/{id}', HomeDiferenciaisController::class . ':update');
    $app->delete('/home-diferenciais/{id}', HomeDiferenciaisController::class . ':delete');

    // acesso_rapido
    $app->get('/acesso-rapido', AcessoRapidoController::class . ':list');
    $app->get('/acesso-rapido/{id}', AcessoRapidoController::class . ':find');
    $app->post('/acesso-rapido', AcessoRapidoController::class . ':create');
    $app->put('/acesso-rapido/{id}', AcessoRapidoController::class . ':update');
    $app->delete('/acesso-rapido/{id}', AcessoRapidoController::class . ':delete');

    //
    $app->get('/modulo-paginas', ModuloPaginasController::class . ':list');
    $app->get('/modulo-paginas/{id}', ModuloPaginasController::class . ':find');
    $app->post('/modulo-paginas/{id}/duplicate', ModuloPaginasController::class . ':duplicate');
    $app->post('/modulo-paginas', ModuloPaginasController::class . ':create');
    $app->put('/modulo-paginas/{id}', ModuloPaginasController::class . ':update');
    $app->delete('/modulo-paginas/{id}', ModuloPaginasController::class . ':delete');

    $app->get('/newsletters', NewslettersController::class . ':list');
    $app->delete('/newsletters/{id}', NewslettersController::class . ':delete');
    $app->get('/plano-interesses', PlanosController::class . ':listInteresses');
    $app->delete('/plano-interesses/{id}', PlanosController::class . ':deleteInteresses');
    $app->get('/planos-forms', PlanosController::class . ':listPlanosForms');
    $app->delete('/planos-forms/{id}', PlanosController::class . ':deletePlanosForms');

    $app->get('/uploads', UploadsController::class . ':list');
    $app->post('/uploads', UploadsController::class . ':create');
    $app->post('/uploads-downloadable', UploadsController::class . ':createdDownloadable');

    $app->get('/configs', ConfigController::class . ':list');
    $app->put('/configs', ConfigController::class . ':update');

  }
}
