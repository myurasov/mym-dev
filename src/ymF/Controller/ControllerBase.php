<?php

namespace ymF\Controller;

use Symfony\Component\HttpFoundation\Response;
use ymF\Helper\TwigHelper;
use ymF\Config;

abstract class ControllerBase
{
  /**
   * Render data
   *
   * @param array $data
   * @return string
   */
  public function renderWithTwig($data = array())
  {
    $className = get_called_class();
    $className = substr($className, strrpos($className, "\\") + 1);
    $templateName = substr($className, 0, strrpos($className, "Controller")) . ".twig";
    $content = TwigHelper::renderTemplate($templateName, $data);
    return $content;
  }
}