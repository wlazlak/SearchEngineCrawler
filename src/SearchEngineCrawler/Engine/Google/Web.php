<?php

namespace SearchEngineCrawler\Engine\Google;

use SearchEngineCrawler\Engine\AbstractEngine;
use SearchEngineCrawler\ResultSet\ResultSet;
use SearchEngineCrawler\ResultSet\Link\ResultSet as LinkSet;
use SearchEngineCrawler\ResultSet\Metadata\ResultSet as MetadataSet;
use SearchEngineCrawler\ResultSet\Page\Container as PageContainer;
use Zend\Stdlib\Exception;

class Web extends AbstractEngine
{
    public function crawl($keyword, array $options = array())
    {
        $set = new ResultSet();
        $crawler = $this->getCrawler();

        $page = 1;
        $source = $crawler->crawl('google', $keyword, $options);

        // create container
        $linkSet = new LinkSet();
        $metadataSet = new MetadataSet();
        $pageContainer = new PageContainer();

        // get links
        if(!isset($options['links'])) {
            throw new Exception\InvalidArgumentException(
                'Options must be defined list of links with the key "links"'
            );
        }
        foreach($options['links'] as $link) {
            $link = $this->getLink($link);
            $link->source($source)->detect($source);
            $result = $link->getResults();
            $linkSet->merge($result);
        }
        $linkSet->sort();
        $pageContainer->setLinks($linkSet);

        // get metadatas
        if(isset($options['metadatas'])) {
            foreach($options['metadatas'] as $metadata) {
                $metadata = $this->getMetadata($metadata);
                $metadata->find($source);
                $metadataSet->addMetadata($metadata);
            }
        }
        $pageContainer->setMetadatas($metadataSet);

        $set->setPage($page, $pageContainer);
        return $set;
    }
}
