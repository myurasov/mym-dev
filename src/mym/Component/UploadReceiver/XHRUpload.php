<?php

/**
 * @copyright 2012, Mikhail Yurasov
 */

namespace mym\Component\UploadReceiver;

class XHRUpload
{
  protected $fileInputName;

  public function __construct($fileInputName = 'file')
  {
    $this->fileInputName = $fileInputName;
  }

  public function save($path)
  {
    $input = fopen("php://input", 'r');
    $temp = tmpfile();
    $realSize = stream_copy_to_stream($input, $temp);
    fclose($input);

    if ($realSize != $this->getSize())
      throw new \Exception('Failed to get input stream');

    // copy temporary file to destination
    $target = fopen($path, 'w');
    fseek($temp, 0, SEEK_SET);
    stream_copy_to_stream($temp, $target);
    fclose($target);

    return $this;
  }

  public function getName()
  {
    return $_GET[$this->fileInputName];
  }

  public function getSize()
  {
    if (isset($_SERVER["CONTENT_LENGTH"]))
    {
      return (int) $_SERVER["CONTENT_LENGTH"];
    }
    else
    {
      throw new Exception('Content length is unknown');
    }
  }
}