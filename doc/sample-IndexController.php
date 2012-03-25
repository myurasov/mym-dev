<?php

namespace ProjectName\Controller;

use ymF\Controller\TwigController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends TwigController
{
  public function defaultAction(Request $requset)
  {
    return $this->createPublicResponse();
  }
}