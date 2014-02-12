<?php

namespace mym\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use mym\Exception\HttpMethodNotAllowedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
  /**
   * @var Request
   */
  protected $request;

  /**
   * @var Response
   */
  protected $response;

  /**
   * @var DocumentManager
   */
  protected $dm;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  /**
   * Ensure HTTP method
   * @param string|array $methods
   * @throws \mym\Exception\HttpMethodNotAllowedException
   */
  protected function ensureMethodIs($methods)
  {
    if (is_string($methods)) {
      $methods = [$methods];
    }

    if (!in_array($this->request->getMethod(), $methods)) {
      throw (new HttpMethodNotAllowedException())->setAllowedMethods(implode(',', $methods));
    }
  }

  //<editor-fold desc="accessors">

  public function setRequest(Request $request)
  {
    $this->request = $request;
  }

  public function getRequest()
  {
    return $this->request;
  }

  public function setDm(DocumentManager $dm)
  {
    $this->dm = $dm;
  }

  public function getDm()
  {
    return $this->dm;
  }

  public function setResponse($response)
  {
    $this->response = $response;
  }

  public function getResponse()
  {
    return $this->response;
  }

  //</editor-fold>
}
