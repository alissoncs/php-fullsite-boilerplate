<?php

namespace App\Model;

use App\Model\BaseModel;

class Config extends BaseModel {
  protected $table = 'configs';

  public static $DYNAMIC_KEYS = [
    'NOME_SITE',
    'URL_SITE',
    'HTTPS',
    'EMAIL',
    'EMAIL_CONTATO',
    'URL_API_MEDICOS',
    'FACEBOOK_URL',
    'TWITTER_URL',
    'INSTAGRAM_URL',
    'YOUTUBE_URL',

    'ERP_LOGIN_URL',

    'WHATSAPP_VENDAS_PLANOS',
    'WHATSAPP_VENDAS_PLANOS_HORARIOS',
    'WHATSAPP_INFOS',
    'WHATSAPP_INFOS_HORARIOS',

    'TELEFONE_0800',
    'TELEFONE_SOS_0800',
    'TELEFONE_ATENDIMENTO_24H',
    'TELEFONE_OUVIDORIA',

    'ENDERECO',
    'ENDERECO_BAIRRO',
    'ENDERECO_CIDADE',
    'ENDERECO_ESTADO',
    'ENDERECO_CEP',

    'APP_ANDROID_URL',
    'APP_IOS_URL',

    'ID_GOOGLE_ANALYTICS',
    'SCRIPT_FACEBOOK_PIXEL',
    'SCRIPT_EXTRA',

    // criacao-usuario-web.twig
    'LINKS_BENEFICIARIO_TITULAR_PLANO_SAUDE',
    'LINKS_BENEFICIARIO_DEPENDENTE_PLANO_SAUDE',
    'LINKS_BENEFICIARIO_CONTRATANTE_PLANO_SAUDE_FAMILIAR',
    'LINKS_EMPRESA_DEMITIDO_APOSENTADO',

    // area-restrita.twig
    'LINK_PORTAL_COOPERADO',
    'LINK_PORTAL_CLIENTE',
    'LINK_PORTAL_RM_COLABORADO',
    'LINK_PORTAL_AUTORIZACOES',
    'LINK_AREA_RESTRITA_UNIMED_BRASIL',

    /// EMAILS
    'EMAIL_ORIGEM_DISPAROS',
    'EMAIL_FORM_OUVIDORIA',
    'EMAIL_FORM_ATENDIMENTO_PARTICULAR',
    'EMAIL_FORM_MEDICO_COOPERADO',
    'EMAIL_FORM_ORIENTACAO_FARMACEUTICA',
    'EMAIL_FORM_VISITA_INSTITUCIONAL',
    'EMAIL_FORM_PLANOS',

    'EMAIL_FORM_DENUNCIAS',
    'EMAIL_FORM_DUVIDAS',
    'EMAIL_FORM_ELOGIOS',
    'EMAIL_FORM_MEDICINA_SEGURANCA_TRABALHO',
    'EMAIL_FORM_QUERO_SER_CLIENTE',
    'EMAIL_FORM_RELACIONAMENTO_EMPRESARIAL',
    'EMAIL_FORM_RECLAMACOES',
    'EMAIL_FORM_SUGESTOES',
    'EMAIL_FORM_SOLICITACOES',

    'GUIA_MEDICO_EXTERNO_LINK',
    'NOTICIAS_ANTERIORES_EXTERNO_LINK',

    'CACHE_ID',
  ];

  protected $fillable = [
    'key',
    'value',
    'type',
  ];

  public static function getMapped() {
    $keys = static::$DYNAMIC_KEYS;

    $dbData = static::all()->toArray();

    $result = [];

    foreach($keys as $key) {
      $found = null;

      foreach($dbData as $data) {
        if ($data['key'] === $key) {
          $found = $data['value'];
          break;
        }
      }

      $result[$key] = $found;
    }

    return $result;
  }

}
