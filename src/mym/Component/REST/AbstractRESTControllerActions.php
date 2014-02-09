<?php

namespace mym\Component\REST;

use mym\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use mym\Exception\HttpNotImplementedException;

class AbstractRESTControllerActions extends AbstractController
{
  /**
   * GET /collection/id/sub-collection/sub-id
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function getSubResourceAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * PUT /collection/id/sub-collection/sub-id
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function updateOrCreateSubResourceAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * DELETE /collection/id/sub-collection/sub-id
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function deleteSubResourceAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * GET /collection/id/sub-collection
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function getSubCollectionAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * PUT /collection/id/sub-collection
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function replaceSubCollectionAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * POST /collection/id/sub-collection
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function createSubResourceAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * DELETE /collection/id/sub-collection
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function deleteSubCollectionAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * GET /collection/id
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function getResourceAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * PUT /collection/id
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function updateOrCreateResourceAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * DELETE /collection/id
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function deleteResourceAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * GET /collection
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function getCollectionAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * PUT /collection
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function replaceCollectionAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * POST /collection
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function createResourceAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }

  /**
   * DELETE /collection
   *
   * @param Request $request
   * @throws HttpNotImplementedException
   */
  public function deleteCollectionAction(Request $request)
  {
    throw new HttpNotImplementedException();
  }
}
