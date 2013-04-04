<?php

namespace mym\Component\CLI;

/**
 * Cli command controls cli application
 */
abstract class CLICommand
{
  /**
   * @var CLIApplication
   */
  protected $ca;

  private $total = 0;
  private $progress = 0;

  public function __construct()
  {
    $this->ca = new CLIApplication();
    $this->setup();
  }

  protected function setup()
  {
    $this->ca->options->set(array(
      'script_name'         => 'CLI Command ' . \get_called_class(),
      'script_version'      => '1.0',
      'script_description'  => 'Command description',
    ));
  }

  public function run()
  {
    if ($this->ca->getParameter('?'))
    {
      $this->ca->displayHelp();
    }
    else
    {
      $this->ca->onStart();
      $this->execute();
      $this->ca->onEnd();
    }
  }

  abstract protected function execute();

  public function getCa() {
    return $this->ca;
  }

  public function setCa($ca) {
    $this->ca = $ca;
  }

  public function getTotal() {
    return $this->total;
  }

  public function setTotal($total) {
    $this->total = $total;
    $this->ca->options->set("progress_items_total", $this->total);
  }

  public function incProgress() {
    $this->progress++;
    $this->ca->updateProgress($this->progress);
  }

  public function getProgress() {
    $this->ca->updateProgress($this->progress);
    return $this->progress;
  }

  public function setProgress($progress) {
    $this->progress = $progress;
  }
}