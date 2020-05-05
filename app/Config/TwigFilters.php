<?php
namespace App\Config;

use Twig\TwigFunction;
use Twig\TwigFilter;
use App\Model\Unidade;

class TwigFilters {

  public function setup($container) {
    $basePath = $container->get('request')->getUri()->getBasePath();

    $uplodaded = new TwigFunction('uploaded_image', function ($string) use($basePath) {
      return $basePath . '/upload/' . $string;
    });
    $container->get('view')->getEnvironment()->addFunction($uplodaded);

    $is_absolute_url = new TwigFunction('is_absolute_url', function ($string) use($basePath) {
      return !empty($string) && (strpos(trim($string), 'http') === 0);
    });
    $container->get('view')->getEnvironment()->addFunction($is_absolute_url);

    $string_date = new TwigFunction('string_date', function ($string) use($basePath) {
      return !empty($string) ? strftime("%d de %B de %Y", strtotime($string)) : null;
    });
    $container->get('view')->getEnvironment()->addFunction($string_date);

    $numbersOnly = new TwigFunction('numbers_only', function ($string) use($basePath) {
      // return $basePath . '/upload/' . $string;
      return preg_replace( '/[^0-9]/', '', $string );
    });
    $container->get('view')->getEnvironment()->addFunction($numbersOnly);

    $json_decode_custom = new TwigFunction('json_decode_custom', function ($string) use($basePath) {
      // return $basePath . '/upload/' . $string;
      if (!empty($string)) {
        return json_decode($string);
      }
      return [];
    });
    $container->get('view')->getEnvironment()->addFunction($json_decode_custom);

    $dynamicLink = new TwigFunction('mount_url', function ($string) use($basePath) {
      if (!empty($string)) {
        $string = trim($string);
        if (strpos($string, 'http') === false) {
          if (strpos($string, '/') === 0) {
            return $basePath . $string;
          } else {
            return $basePath . '/' . $string;
          }
        }
        return $string;
      }
      return null;
    });
    $container->get('view')->getEnvironment()->addFunction($dynamicLink);

    $humanDate = new TwigFunction('human_date', function($date) {
      if (!empty($date)) {
        $time = strtotime($date);
        return strftime("%d de %B de %Y", $time);
      } else {
        return null;
      }
    });
    $container->get('view')->getEnvironment()->addFunction($humanDate);

    $humanDate = new TwigFunction('resume_text', function($content, $limit = 60) {
      if (!empty($content)) {
        if(strlen($content)<=$limit)
        {
          return $content;
        }
        else
        {
          return substr($content,0,$limit) . '...';
        }
      } else {
        return null;
      }
    });
    $container->get('view')->getEnvironment()->addFunction($humanDate);

    $getUnidade = new TwigFunction('get_unidade', function($id) {
      return Unidade::find($id);
    });
    $container->get('view')->getEnvironment()->addFunction($getUnidade);

    $columnCarencias = new TwigFunction('carencias_column', function ($array, $plano) use($basePath) {
      $cars = $plano ?  $plano->decodeCarencias() : [];

      foreach($array as $key => $value) {
        // return json_encode($plano);//.[$key];
        if (!empty($cars[$key])) {
          // $possui1 = true;
          return 1;
        }
      }
      // return json_encode(gettype($plano));
      return 0;
    });
    $container->get('view')->getEnvironment()->addFunction($columnCarencias);

  }

}
