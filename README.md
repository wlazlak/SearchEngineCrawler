ZF2 SearchEngineCrawler module
===================

Version 0.0.1 Created by [Vincent Blanchon](http://developpeur-zend-framework.fr/)

Introduction
------------

SearchEngineCrawler is a SEO/SEA crawler.
Actually, just a draft.

Requirement
------------
libxml2 >= 2.7.8

Usage
------------

A simple search on Google Web :

```php
$googleWeb = $this->getServiceLocator('crawler_google_web');
$pageSet = $googleWeb->crawl('rooney', array(
    'links' => array('natural', 'image', 'video'),
    'localisation' => array('lang' => 'fr'),
));
$linkSet = $pageSet->getPage(1);

echo sprintf('There are %s natural links !', count($linkSet->getNaturalResults()));
echo sprintf('There are %s image links !', count($linkSet->getImageResults()));
echo sprintf('There are %s video links !', count($linkSet->getVideoResults()));

foreach($linkSet as $position => $result) {
    echo 'Position :' . ($position+1);
    echo 'Link     :' . $result->getLink();
    echo 'Ad       :' . $result->getAd();
}
```
Features
------------

You can crawl :
* Google Web (Natural, image, video, product, premium, map & news link)


Todo
------------

Crawl on :
* Google Images
* Google Video
* Bing Web

Other stuff:
* Crawler matcher
* Crawler with proxy
* Crawler with pagination
* Crawler with Zend\Client
* Get link datas (sitelinks, stars)
* Link builder
* Engine\Metadatas (number of result, word spelling, search suggest)
* Improve workflow with event manager