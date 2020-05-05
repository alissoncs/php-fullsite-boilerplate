<?php
namespace App\Website;

use App\Website\BaseWebsiteController;
use App\Model\MensagemContato;
use App\Util\Email;
use App\Model\Config;

class FaleConoscoController extends BaseWebsiteController
{
  public function home($request, $response, $args)
  {
    return $this->container['view']->render($response, 'fale-conosco.twig', [
    ]);
  }

  public function submit($request, $response) {
    $payload = $request->getParsedBody();

    if (empty($payload['nome'])) {
      return $response->withJson([
        'data' => $payload,
        'detail' => 'Nome obrigatório'
      ], 422);
    }

    if (empty($payload['email'])) {
      return $response->withJson([
        'data' => $payload,
        'detail' => 'E-mail obrigatório'
      ], 422);
    }

    if (empty($payload['telefone'])) {
      return $response->withJson([
        'data' => $payload,
        'detail' => 'Telefone obrigatório'
      ], 422);
    }

    $payload['status'] = 'novo';
    $payload['observacao_interna'] = '--';

    $configs = Config::getMapped();

    $sent = false;

    $email_destinatario = $configs['EMAIL_CONTATO'];

    switch ($payload['config_to']) {
      case 'EMAIL_FORM_DENUNCIAS': 
        $email_destinatario = $configs['EMAIL_FORM_DENUNCIAS']; 
        break;
      case 'EMAIL_FORM_DUVIDAS': 
        $email_destinatario = $configs['EMAIL_FORM_DUVIDAS']; 
        break;
      case 'EMAIL_FORM_ELOGIOS': 
        $email_destinatario = $configs['EMAIL_FORM_ELOGIOS']; 
        break;
      case 'EMAIL_FORM_MEDICINA_SEGURANCA_TRABALHO': 
        $email_destinatario = $configs['EMAIL_FORM_MEDICINA_SEGURANCA_TRABALHO']; 
        break;
      case 'EMAIL_FORM_QUERO_SER_CLIENTE': 
        $email_destinatario = $configs['EMAIL_FORM_QUERO_SER_CLIENTE']; 
        break;
      case 'EMAIL_FORM_RELACIONAMENTO_EMPRESARIAL': 
        $email_destinatario = $configs['EMAIL_FORM_RELACIONAMENTO_EMPRESARIAL']; 
        break;
      case 'EMAIL_FORM_RECLAMACOES': 
        $email_destinatario = $configs['EMAIL_FORM_RECLAMACOES']; 
        break;
      case 'EMAIL_FORM_SUGESTOES': 
        $email_destinatario = $configs['EMAIL_FORM_SUGESTOES']; 
        break;
      case 'EMAIL_FORM_SOLICITACOES': 
        $email_destinatario = $configs['EMAIL_FORM_SOLICITACOES']; 
        break;
    }

    if ( strlen($email_destinatario) > 0 ) {
      $email = new Email($configs);
      $to = $email_destinatario;
      $sent = $email->send($to, 'Contato Via Form Fale Conosco - ' . $payload['assunto'], $payload);
      if (!$sent) {
        $this->container->get('logger')->error('Falha ao enviar email!!!');
      }
    }

    $mensagem = MensagemContato::create($payload);

    return $response->withJson([
      'sent' => $sent,
      'data' => $mensagem
    ]);
  }

  public function ouvidoria($request, $response) {
    return $this->container['view']->render($response, 'fale-conosco-ouvidoria.twig', [
    ]);
  }

  public function submitOuvidoria($request, $response) {
    $payload = $request->getParsedBody();


    $payload['assunto'] = 'ouvidoria';
    $payload['status'] = 'novo';
    $payload['observacao_interna'] = '--';

    $endereco = @$payload['endereco'];
    $numero = @$payload['numero'];
    $complemento = @$payload['complemento'];
    $bairro = @$payload['bairro'];
    $cidade = @$payload['cidade'];
    $estado = @$payload['estado'];
    $numero_cartao = @$payload['numero_cartao'];
    $mensagem = @$payload['mensagem'];

    $configs = Config::getMapped();

    $sent = false;
    if (!empty($configs['EMAIL_FORM_OUVIDORIA'])) {
      $email = new Email($configs);
      $to = $configs['EMAIL_FORM_OUVIDORIA'];
      $sent = $email->send($to, 'Contato Via Form Ouvidoria', $payload);
      if (!$sent) {
        $this->container->get('logger')->error('Falha ao enviar email!!!');
      }
    }

    $mensagem = MensagemContato::create($payload);

    $this->container->get('logger')->info('Form ouvidoria: ' . json_encode($payload));

    return $response->withJson([
      'sent' => $sent,
      'data' => $mensagem,
    ]);
  }


  public function atendimentoParticular($request, $response, $args)
  {
    return $this->container['view']->render($response, 'fale-conosco-atendimento-particular.twig', [
    ]);
  }

  public function submitAtendimentoParticular($request, $response) {
    $payload = $request->getParsedBody();


    $payload['assunto'] = 'atendimento_particular';
    $payload['status'] = 'novo';
    $payload['observacao_interna'] = '--';
    $payload['mensagem'] = $payload['local'] . ' // ' . @$payload['procedimento_medico'];

    $configs = Config::getMapped();

    $sent = false;
    if (!empty($configs['EMAIL_FORM_ATENDIMENTO_PARTICULAR'])) {
      $email = new Email($configs);
      $to = $configs['EMAIL_FORM_ATENDIMENTO_PARTICULAR'];
      $sent = $email->send($to, 'Contato Via Form Atendimento Particular', $payload);
      if (!$sent) {
        $this->container->get('logger')->error('Falha ao enviar email!!!');
      }
    }

    $mensagem = MensagemContato::create($payload);

    $this->container->get('logger')->info('Form atendimento particular: ' . json_encode($payload));

    return $response->withJson([
      'sent' => $sent,
      'data' => $mensagem,
    ]);
  }

  public function medicoCooperado($request, $response, $args)
  {
    return $this->container['view']->render($response, 'fale-conosco-medico-cooperado.twig', [
    ]);
  }

  public function submitMedicoCooperado($request, $response) {
    $payload = $request->getParsedBody();


    $payload['assunto'] = 'medico_cooperado';
    $payload['status'] = 'novo';
    $payload['observacao_interna'] = '--';

    $configs = Config::getMapped();

    $sent = false;
    if (!empty($configs['EMAIL_FORM_MEDICO_COOPERADO'])) {
      $email = new Email($configs);
      $to = $configs['EMAIL_FORM_MEDICO_COOPERADO'];
      $sent = $email->send($to, 'Contato Via Form Medico Cooperado', $payload);
      if (!$sent) {
        $this->container->get('logger')->error('Falha ao enviar email!!!');
      }
    }

    $mensagem = MensagemContato::create($payload);

    $this->container->get('logger')->info('Form medico cooperado: ' . json_encode($payload));

    return $response->withJson([
      'sent' => $sent,
      'data' => $mensagem,
    ]);
  }

  public function orientacaoFarmaceutica($request, $response, $args)
  {
    return $this->container['view']->render($response, 'fale-conosco-orientacao-farmaceutica.twig', [
    ]);
  }

  public function submitOrientacaoFarmaceutica($request, $response) {
    $payload = $request->getParsedBody();
    $payload['assunto'] = 'orientacao_farmaceutica';
    $payload['status'] = 'novo';
    $payload['observacao_interna'] = '--';

    $configs = Config::getMapped();

    $sent = false;
    if (!empty($configs['EMAIL_FORM_ORIENTACAO_FARMACEUTICA'])) {
      $email = new Email($configs);
      $to = $configs['EMAIL_FORM_ORIENTACAO_FARMACEUTICA'];
      $sent = $email->send($to, 'Contato Via Form Oriencação Farmaceutica', $payload);
      if (!$sent) {
        $this->container->get('logger')->error('Falha ao enviar email!!!');
      }
    }

    $mensagem = MensagemContato::create($payload);

    $this->container->get('logger')->info('Form orientacao farmaceutica: ' . json_encode($payload));

    return $response->withJson([
      'sent' => $sent,
      'data' => $mensagem,
    ]);
  }

  public function visitaInstitucional($request, $response, $args)
  {
    return $this->container['view']->render($response, 'fale-conosco-visita-institucional.twig', [
    ]);
  }

  public function submitVisitaInstitucional($request, $response) {
    $payload = $request->getParsedBody();
    $payload['assunto'] = 'visita_institucional';
    $payload['status'] = 'novo';
    $payload['observacao_interna'] = '--';

    $configs = Config::getMapped();

    $sent = false;
    if (!empty($configs['EMAIL_FORM_VISITA_INSTITUCIONAL'])) {
      $email = new Email($configs);
      $to = $configs['EMAIL_FORM_VISITA_INSTITUCIONAL'];
      $sent = $email->send($to, 'Contato Via Form visita_institucional', $payload);
      if (!$sent) {
        $this->container->get('logger')->error('Falha ao enviar email!!!');
      }
    }

    $mensagem = MensagemContato::create($payload);

    $this->container->get('logger')->info('Form visita_institucional: ' . json_encode($payload));

    return $response->withJson([
      'sent' => $sent,
      'data' => $mensagem,
    ]);
  }
}
