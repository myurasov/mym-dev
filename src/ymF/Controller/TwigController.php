<?php

namespace ymF\Controller;

use Symfony\Component\HttpFoundation\Response;
use ymF\Helper\TwigHelper;
use ymF\Config;

abstract class TwigController
{
  private $conf;

  public function __construct()
  {
    $this->conf = Config::get("TwigController");
  }

  /**
   * Render data
   *
   * @param array $data
   * @return string
   */
  public function render($data = array())
  {
    $className = get_called_class();
    $className = substr($className, strrpos($className, "\\") + 1);
    $templateName = substr($className, 0, strrpos($className, "Controller")) . ".twig";
    $content = TwigHelper::renderTemplate($templateName, $data);
    return $content;
  }

  public function createPublicResponse($data = array())
  {
    $response = new Response($this->render($data));
    $response->setPublic()->setSharedMaxAge($this->conf["responseSharedMaxAge"]);
    return $response;
  }

  public function createPrivateResponse($data = array())
  {
    $response = new Response($this->render($data));
    $response->setPrivate();
    return $response;
  }

  public function createResponse($data)
  {
    return $this->createPublicResponse($data);
  }
}