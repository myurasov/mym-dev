<?php

namespace mym\Component\Crawler\Processor;

use mym\Component\Crawler\Processor\ProcessorInterface;
use mym\Component\Crawler\Url;
use Goutte\Client as GoutteClient;
use Symfony\Component\DomCrawler\Crawler;

class AbstractProcessor implements ProcessorInterface
{
  /**
   * @var Url[]
   */
  protected $extractedUrls = [];

  /**
   *
   * @var GoutteClient
   */
  private $client;

  /**
   * @return GoutteClient
   */
  protected function getWebClient()
  {
    if (!$this->client) {
      $this->client = new GoutteClient();
      $this->client->setServerParameter('HTTP_USER_AGENT', 'Crawler');
    }

    return $this->client;
  }

  /**
   * @param Url $url
   * @return Crawler
   */
  protected function crawlUrl(&$url)
  {
    $client = $this->getWebClient();
    $crawler = $client->request('GET', $url->getUrl());

    $url->setStatus(
      $client->getResponse()->getStatus() >= 400
        ? Url::STATUS_ERROR
        : Url::STATUS_OK
    );

    return $crawler;
  }

  public function process(Url &$url)
  {
  }

  public function getExtractedUrls()
  {
    return $this->extractedUrls;
  }

  public function setExtractedUrls($extractedUrls)
  {
    $this->extractedUrls = $extractedUrls;
  }
}