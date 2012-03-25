<?php

namespace ymF\Router;

use Symfony\Component\HttpFoundation\Request;

interface RouterInterface
{
  /**
   * @return RouterInterface
   */
  public function route(Request $request);

  /**
   * Get controller funciton
   *
   * @return string
   */
  public function getAction();

  /**
   * Get controller class name
   *
   * @return string
   */
  public function getController();
}