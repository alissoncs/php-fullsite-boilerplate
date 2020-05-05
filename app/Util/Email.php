<?php

namespace App\Util;

class Email
{
  private $from = 'no-reply@unimed.com.br';

  public function __construct($configs = array())
  {
    if ($configs && !empty($configs['EMAIL_ORIGEM_DISPAROS'])) {
      $this->from = $configs['EMAIL_ORIGEM_DISPAROS'];
    }
  }

  public function send($to, $subject, $arrayList = array())
  {
    $html = '';
    $html .= '<table>';

    foreach ($arrayList as $key => $item) {
      $html .= '<tr>';
      $html .= '<th>' .  $key . ': </th>';
      $html .= '<td>' . nl2br($item) . '</td>';
      $html .= '</tr>';
    }

    $html .= '</table>';
    $headers = $this->getHeaders();

    return (bool) mail($to, $subject, $html, $headers);
  }

  public function getHeaders()
  {
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Additional headers
    // $headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
    $headers .= 'From: Unimed VS <' . $this->from . '>' . "\r\n";

    return $headers;
  }
}
