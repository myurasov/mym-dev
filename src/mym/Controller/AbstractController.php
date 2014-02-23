<?php

namespace mym\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use LegalAdvice\Document\User;
use mym\Component\Auth\AbstractAuthService;
use mym\Exception\HttpForbiddenException;
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

  /**
   * @var AbstractAuthService
   */
  protected $authService;

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

    if (!in_array($this->request->getMethod(), $methods, true)) {
      throw (new HttpMethodNotAllowedException())->setAllowedMethods(implode(', ', $methods));
    }
  }

  /**
   * Ensure HTTP method is POST
   */
  protected function ensureMethodIsPost()
  {
    $this->ensureMethodIs('POST');
  }

  /**
   * Ensure user is logged in and has specific role
   *
   * @param $roles
   * @throws \mym\Exception\HttpForbiddenException
   * @return object
   */
  protected function ensureUserRoleIs($roles)
  {
    if (is_string($roles)) {
      $roles = [$roles];
    }

    // load user
    $userId = $this->authService->getUserIdFromRequest($this->request);

    if (false === $userId) {
      throw new HttpForbiddenException();
    }

    $user = User::load($userId, true /* required */);

    if (0 === count(array_intersect($user->getRoles(), $roles))) {
      throw new HttpForbiddenException();
    }

    return $user;
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

  public function setAuthService(AbstractAuthService $authService)
  {
    $this->authService = $authService;
  }

  public function getAuthService()
  {
    return $this->authService;
  }

  //</editor-fold>
}
