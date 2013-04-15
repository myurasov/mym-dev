<?php

namespace mym\Component\REST;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerAbstract
{
  public function dispatchAction(Request $request) {
    switch ($request->getMethod()) {

      case "GET":
        return $this->getAction($request);
        break;

      case "POST":
        return $this->createAction($request);
        break;

      case "PUT":
        return $this->updateAction($request);
        break;

      case "DELETE":
        return $this->deleteAction($request);
        break;

      default:
        throw new \mym\Exception\HttpMethodNotAllowedException(
          "Method '{$request->getMethod()}' is not supported"
        );
        break;
    }
  }

  public function getAction(Request $request) {
    $response = new Response();
    return $response;
  }

  public function createAction(Request $request) {
    $response = new Response();
    return $response;
  }

  public function updateAction(Request $request) {
    $response = new Response();
    return $response;
  }

  public function deleteAction(Request $request) {
    $response = new Response();
    return $response;
  }
}