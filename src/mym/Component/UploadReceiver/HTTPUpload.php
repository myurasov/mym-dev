<?php

/**
 * @copyright 2012, Mikhail Yurasov
 */

namespace mym\Component\UploadReceiver;

class HTTPUpload
{
  protected $fileInputName;

  public function __construct($fileInputName = 'file')
  {
    $this->fileInputName = $fileInputName;
  }

  public function save($path)
  {
    if (!isset($_FILES[$this->fileInputName]))
      throw new \Exception('Uploaded file not found');

    if (!move_uploaded_file($_FILES[$this->fileInputName]['tmp_name'], $path))
      throw new \Exception('Failed to move uploaded file');

    return $this;
  }

  public function getName()
  {
    return $_FILES[$this->fileInputName]['name'];
  }

  public function getSize()
  {
    return $_FILES[$this->fileInputName]['size'];
  }
}