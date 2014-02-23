<?php

namespace mym\Component\REST;

use mym\Util\Arrays;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;

use mym\Exception\HttpForbiddenException;
use mym\Exception\HttpNotImplementedException;
use mym\ODM\AbstractRepository;

class AbstractRESTController extends AbstractRESTControllerActions
{
  /**
   * @var AbstractRepository
   */
  protected $repository;

  /**
   * @var $response SerializedResponse
   */
  protected $response;

  protected $documentName = '';

  protected $defaultLimit = 1;
  protected $maxLimit = 100;

  /**
   * @var PropertyAccessor
   */
  private $propertyAccessor;

  public function getResourceAction(Request $request)
  {
    $document = $this->loadResource($request->query->get('id'), true /* required */);
    $this->response->setData($document);

    // last-modified
    if (method_exists($document, 'getUpdatedAt')) {
      $this->response->setLastModified($document->getUpdatedAt());
    }

    return $this->response;
  }

  protected function loadResource($id, $required = true)
  {
    $m = [];

    if (preg_match('#^(id|md5):(.+)$#', $id, $m)) {
      return $this->getRepository()->findOneBy([$m[1] => $m[2]]);
    } else {
      return call_user_func([$this->documentName, 'loadUsingDocumentManager'], $this->getDm(), $id, $required);
    }
  }

  /**
   * Creates and configures repository from request
   */
  public function getRepository()
  {
    // get repository
    if (!$this->repository) {
      $this->repository = $this->getDm()->getRepository($this->documentName);
    }

    // set skip
    $this->repository->setSkip($this->request->query->get('skip'));

    // set limit (1..max)
    if ($this->request->query->has('limit')) {
      $this->repository->setLimit(max(min($this->request->query->get('limit'), $this->maxLimit), 1));
    } else {
      $this->repository->setLimit($this->defaultLimit);
    }

    return $this->repository;
  }

  public function getCollectionAction(Request $request)
  {
    $this->response->setData($this->search());
    return $this->response;
  }

  public function createResourceAction(Request $request)
  {
    // create new instance of the document
    $document = new $this->documentName();

    $input= $request->request->all();
    $this->updateDocument($document, $input);

    $this->dm->persist($document);
    $this->dm->flush($document);

    //

    $this->response->setData($document);
    return $this->response;
  }

  public function search()
  {
    $repository = $this->getRepository();

    if ($this->request->query->has('id') && is_array($this->request->query->get('id'))) { /* IDs */

      return $repository->searchByIds(
        $this->request->query->get('id')
      )->toArray(false);

    } else {
      throw new HttpNotImplementedException();
    }
  }

  //<editor-fold desc="updating">

  /**
   * Update field
   * Should be overriden for access check
   *
   * @param $document
   * @param $path
   * @param $value
   */
  protected function updateField(&$document, $path, $value)
  {
    // create property accessor
    if (!$this->propertyAccessor) {
      $this->propertyAccessor = new PropertyAccessor();
    }

    $this->propertyAccessor->setValue($document, $path, $value);
  }

  /**
   * Update document
   *
   * @param $document
   * @param $input
   */
  protected function updateDocument(&$document, $input)
  {
    if (is_array($input)) {
      Arrays::walkArray($input, function ($path, $value) use ($document) {
          // update property
          try {
            $this->updateField($document, $path, $value);
          } catch (FieldUpdateException $e) {
            throw new HttpForbiddenException('Failed to update ' . $path);
          }
        });
    }
  }

  //</editor-fold>

  // <editor-fold desc="accessors">

  public function getDefaultLimit()
  {
    return $this->defaultLimit;
  }

  public function setDefaultLimit($defaultLimit)
  {
    $this->defaultLimit = $defaultLimit;
  }

  public function getMaxLimit()
  {
    return $this->maxLimit;
  }

  public function setMaxLimit($maxLimit)
  {
    $this->maxLimit = $maxLimit;
  }

  // </editor-fold>
}