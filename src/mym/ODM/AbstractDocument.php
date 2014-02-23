<?php

/**
 * Document abstarct
 * Also used for embedded documents
 *
 * @copyright 2012, Mikhail Yurasov
 */

namespace mym\ODM;

use Doctrine\ODM\MongoDB\DocumentManager;
use mym\Exception\HttpNotFoundException;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Exception\ValidatorException;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\MappedSuperclass
 */
abstract class AbstractDocument
{
  /**
   * Get document as array
   *
   * @return array
   */
  public function toArray($params = array())
  {
    if (!is_array($params))
    {
      $params = func_get_args();
    }

    if (count($params) == 0)
    {
      $res = get_object_vars($this);
    }
    else // arguments is a list of properties
    {
      $res = array();

      for ($i = 0; $i < count($params); $i++)
      {
        if (property_exists($this, $params[$i]))
        {
          $res[$params[$i]] = $this->$params[$i];
        }
      }
    }

    // remove non-scalar values
    foreach ($res as $k => $v) {
      if (!is_scalar($v) && !in_array($k, $params)){
        unset($res[$k]);
      }
    }


    return $res;
  }

  /**
   * Get object as JSON
   *
   * @return string
   */
  public function toJson($params = array())
  {
   if (!is_array($params))
   {
     $params = func_get_args();
   }

   return json_encode($this->toArray($params));
  }

  /**
   * Load document
   * @param \Doctrine\ODM\MongoDB\DocumentManager $dm
   * @param mixed $id
   * @param bool $required
   * @throws \Exception
   * @return static
   */
  public static function loadUsingDocumentManager(DocumentManager $dm, $id, $required = false)
  {
    $documentName = get_called_class();
    $document = null;

    if (!empty($id)) {
      $document = $dm->find($documentName, $id);
    }

    if ($required && is_null($document)) {
      throw new HttpNotFoundException('Document not found');
    }

    return $document;
  }

  /**
   * @ODM\PreFlush
   */
  public function validate() {

    $validator = Validation::createValidatorBuilder()
      ->enableAnnotationMapping()
      ->getValidator();

    $violations /* @var $violations \Symfony\Component\Validator\ConstraintViolationList */ = $validator->validate($this);
    $message = [];

    if (count($violations) > 0) {

      foreach ($violations as $violation /* @var $violation \Symfony\Component\Validator\ConstraintViolation */) {
        $message[] = $violation->getPropertyPath() . ": " . $violation->getMessage();
      }

      throw new ValidatorException("Validation failed. " . join(" ", $message));
    }

    return true;
  }
}