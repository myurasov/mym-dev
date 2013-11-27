<?php

require __DIR__ . '/../../src/modules/AppBase/Application.php';

//

$logger = new Monolog\Logger('crawler');
$lh = new Monolog\Handler\StreamHandler('php://STDOUT', Monolog\Logger::DEBUG);
$logger->pushHandler($lh);

//

$r = new mym\Component\Crawler\Repository\MongoRepository();
$r->setMaxDepth(5);
//$r->clear();

$urls = [
  new \mym\Component\Crawler\Url('http://fleapop.com/')
];

$r->insert($urls[0]);

$pp = new mym\Component\Crawler\Processor\ProcessorPool();
$pp->addProcessor(new \AppBase\Crawler\TestProcessor());

$d = new \mym\Component\Crawler\NativeDispatcher();
$d->setLogger($logger);
$d->setRepository($r);
$d->setProcessorPool($pp);

$d->run();