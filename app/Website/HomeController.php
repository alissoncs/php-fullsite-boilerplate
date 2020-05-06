<?php

namespace App\Website;

use App\Website\BaseWebsiteController;
use App\Model\Banner;

class HomeController extends BaseWebsiteController
{
  public function home($request, $response, $args)
  {
    return $this->container['view']->render($response, 'home.twig', [
    ]);
  }
}
