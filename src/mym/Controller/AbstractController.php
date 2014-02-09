<?php

namespace mym\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
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
}
