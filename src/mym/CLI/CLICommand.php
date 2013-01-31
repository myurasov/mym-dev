<?php

namespace mym\CLI;

/**
 * Cli command controls cli application
 */
abstract class CLICommand
{
  /**
   * @var CLIApplication
   */
  protected $cliApplication;

  public function __construct()
  {
    $this->cliApplication = new CLIApplication();
    $this->setup();
  }

  protected function setup()
  {
    $this->cliApplication->options->set(array(
      'script_name'         => 'CLI Command ' . \get_called_class(),
      'script_version'      => '0.0',
      'script_description'  => 'Command description',
    ));
  }

  public function run()
  {
    if ($this->cliApplication->getParameter('?'))
    {
      $this->cliApplication->displayHelp();
    }
    else
    {
      $this->cliApplication->onStart();
      $this->execute();
      $this->cliApplication->onEnd();
    }
  }

  abstract protected function execute();

  /**
   * @return CLIApplication
   */
  public function getCliApplication()
  {
    return $this->cliApplication;
  }

  public function setCliApplication(CLIApplication $cliApplication)
  {
    $this->cliApplication = $cliApplication;
  }
}