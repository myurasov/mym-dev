<?php

require __DIR__ . '/../../src/modules/AppBase/Application.php';

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
$d->setRepository($r);
$d->setProcessorPool($pp);

$d->run();