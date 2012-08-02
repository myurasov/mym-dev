<?php

/**
 * Upload receiver
 *
 * @version 1.0
 * @copyright 2012, Mikhail Yurasov
 */

namespace mym\Component\UploadReceiver;

class UploadReceiver
{
  private $uploadsDir = null;
  private $fileInputName = 'file';
  private $allowedExtensions = null;
  private $maxFileSize = 4194304; // 4M
  private $savedToPath;
  private $savedToFile;

  private function _ensureUploadsDir()
  {
    if (empty($this->uploadsDir))
    {
      $this->uploadsDir = sys_get_temp_dir();
    }
    else if (!is_dir($this->uploadsDir))
    {
      if (!mkdir($this->uploadsDir, 0755))
      {
        throw new \Exception('Failed to create uploads directory');
      }
    }
    else if (!is_writable($this->uploadsDir))
    {
      throw new \Exception('Upload directory is not writable');
    }
  }

  /**
   * Receive upload
   *
   * @throws \Exception
   */
  public function receive()
  {
    $this->savedToPath = null;
    $this->savedToFile = null;

    // ensure the uploads dir exists
    $this->_ensureUploadsDir();

    // save file
    if (isset($_GET[$this->fileInputName]))
    {
      $file = new XHRUpload($this->fileInputName);
    }
    else if (isset($_FILES[$this->fileInputName]))
    {
      $file = new HTTPUpload($this->fileInputName);
    }

    $name = $file->getName();
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $name = md5(uniqid('', true));
    $this->savedToFile = $name . (empty($ext) ? '' : '.') . $ext;
    $this->savedToPath = $this->uploadsDir . '/' . $this->savedToFile;

    // check extension
    if (is_array($this->allowedExtensions) && !in_array(strtolower($ext), $this->allowedExtensions))
      throw new ExtensionIsNotAllowedException();

    // check size
    if ($file->getSize() > $this->maxFileSize)
      throw new FileIsTooLargeException();

    // save file
    $file->save($this->savedToPath);

    return $this;
  }

  public function getUploadsDir()
  {
    return $this->uploadsDir;
  }

  public function setUploadsDir($uploadsDir)
  {
    $this->uploadsDir = $uploadsDir;
  }

  public function getFileInputName()
  {
    return $this->fileInputName;
  }

  public function setFileInputName($fileInputName)
  {
    $this->fileInputName = $fileInputName;
  }

  public function getAllowedExtensions()
  {
    return $this->allowedExtensions;
  }

  public function setAllowedExtensions($allowedExtensions)
  {
    $this->allowedExtensions = $allowedExtensions;
  }

  public function getMaxFileSize()
  {
    return $this->maxFileSize;
  }

  public function setMaxFileSize($maxFileSize)
  {
    $this->maxFileSize = $maxFileSize;
  }

  public function getSavedToPath()
  {
    return $this->savedToPath;
  }

  public function getSavedToFilename()
  {
    return $this->savedToFile;
  }
}