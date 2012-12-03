<?php

/*
 * This file is part of the SearchEngineCrawler package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace SearchEngineCrawlerTest\Engine\Google;

use PHPUnit_Framework_TestCase as TestCase;
use SearchEngineCrawler\Engine\Google\Web as GoogleWeb;
use SearchEngineCrawler\Engine\Link\Builder\Google\AbstractGoogle as GoogleLinkBuilder;
use SearchEngineCrawlerTest\Crawler\CachedCrawler;

class WebLinksTest extends TestCase
{
    protected $identifier = 'google.web';

    public function testCanCrawlNaturalLinks()
    {
        $crawler = new CachedCrawler();
        $crawler->setAutoFileCached(true);
        $crawler->setIdentifier($this->identifier);

        $google = new GoogleWeb();
        $google->setCrawler($crawler);
        $set = $google->crawl('zend framework', array(
            'links' => array('natural'),
            'builder' => array(
                'lang' => GoogleLinkBuilder::LANG_FR,
                'host' => GoogleLinkBuilder::HOST_FR,
            ),
        ));
        $linkSet = $set->getPage(1)->getLinks();
        $naturals = $linkSet->getNaturalResults();

        $this->assertEquals(10, count($naturals));
        $this->assertEquals(10, count($linkSet));
        $this->assertEquals(4, count($naturals->offsetGet(0)->getExtension()->getSitelinks()));

        // test index
        $keys = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $this->assertEquals($keys, array_keys($linkSet->getArrayCopy()));
    }

    public function testCanCrawlNaturalImageVideoLinks()
    {
        $crawler = new CachedCrawler();
        $crawler->setAutoFileCached(true);
        $crawler->setIdentifier($this->identifier);

        $google = new GoogleWeb();
        $google->setCrawler($crawler);
        $set = $google->crawl('rooney', array(
            'links' => array('natural', 'image', 'video'),
            'builder' => array(
                'lang' => GoogleLinkBuilder::LANG_FR,
                'host' => GoogleLinkBuilder::HOST_FR,
            ),
        ));
        $linkSet = $set->getPage(1)->getLinks();

        $this->assertEquals(7, count($linkSet->getNaturalResults()));
        $this->assertEquals(5, count($linkSet->getImageResults()));
        $this->assertEquals(3, count($linkSet->getVideoResults()));
        $this->assertEquals(15, count($linkSet));
    }

    public function testCanCrawlNaturalProductPremiumLinks()
    {
        $crawler = new CachedCrawler();
        $crawler->setAutoFileCached(true);
        $crawler->setIdentifier($this->identifier);

        $google = new GoogleWeb();
        $google->setCrawler($crawler);
        $set = $google->crawl('table a manger', array(
            'links' => array('natural', 'product', 'premium'),
            'builder' => array(
                'lang' => GoogleLinkBuilder::LANG_FR,
                'host' => GoogleLinkBuilder::HOST_FR,
            ),
        ));
        $linkSet = $set->getPage(1)->getLinks();
        $premiums = $linkSet->getPremiumResults();

        $this->assertEquals(10, count($linkSet->getNaturalResults()));
        $this->assertEquals(3, count($linkSet->getProductResults()));
        $this->assertEquals(3, count($premiums));
        $this->assertEquals(3, count($premiums->offsetGet(0)->getRichSnippet()->getProducts()));
        $this->assertEquals(16, count($linkSet));
    }

    public function testCanCrawlNaturalMapLinks()
    {
        $crawler = new CachedCrawler();
        $crawler->setAutoFileCached(true);
        $crawler->setIdentifier($this->identifier);

        $google = new GoogleWeb();
        $google->setCrawler($crawler);
        $set = $google->crawl('restaurant paris', array(
            'links' => array('natural', 'map'),
            'builder' => array(
                'lang' => GoogleLinkBuilder::LANG_FR,
                'host' => GoogleLinkBuilder::HOST_FR,
            ),
        ));
        $linkSet = $set->getPage(1)->getLinks();

        $this->assertEquals(10, count($linkSet->getNaturalResults()));
        $this->assertEquals(7, count($linkSet->getMapResults()));
        $this->assertEquals(17, count($linkSet));
    }

    public function testCanCrawlNaturalNewsPremiumLinks()
    {
        $crawler = new CachedCrawler();
        $crawler->setAutoFileCached(true);
        $crawler->setIdentifier($this->identifier);

        $google = new GoogleWeb();
        $google->setCrawler($crawler);
        $set = $google->crawl('bourse de paris', array(
            'links' => array('natural', 'news', 'premium'),
            'builder' => array(
                'lang' => GoogleLinkBuilder::LANG_FR,
                'host' => GoogleLinkBuilder::HOST_FR,
            ),
        ));
        $linkSet = $set->getPage(1)->getLinks();

        $this->assertEquals(10, count($linkSet->getNaturalResults()));
        $this->assertEquals(3, count($linkSet->getNewsResults()));
        $this->assertEquals(3, count($linkSet->getPremiumResults()));
        $this->assertEquals(16, count($linkSet));
    }
}
