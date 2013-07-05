<?php

namespace mym\Routing;

use Symfony\Component\HttpFoundation\Request;

interface RouterInterface
{
  public function route(Request & $request);

  /**
   * @return callable
   */
  public function getController();
}