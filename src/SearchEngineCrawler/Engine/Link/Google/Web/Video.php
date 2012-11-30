<?php

namespace SearchEngineCrawler\Engine\Link\Google\Web;

use SearchEngineCrawler\Engine\Link\AbstractLink;
use SearchEngineCrawler\ResultSet\Link\Result\Video as VideoResult;
use SearchEngineCrawler\ResultSet\Link\ResultSet;

class Video extends AbstractLink
{
    public function detect(&$source)
    {
        $results = new ResultSet();

        $domQuery = $this->getDomQuery();
        $domQuery->setDocumentHtml($source);
        $nodes = $domQuery->queryXpath('//div[@id="ires"]//li[@class="g"]');
        foreach($nodes as $node) {
            // get image node
            $nodePath = $node->getNodePath();
            $nodePath .= '//img[starts-with(@id,"vidthumb")]';
            $link = $domQuery->queryXpath($nodePath)->current();
            if(null === $link) {
                continue; // not a video link
            }
            // get link node
            $nodePath = $node->getNodePath();
            $nodePath .= '/div[@class="vsc"]//h3[@class="r"]/a[@class="l"]';
            $link = $domQuery->queryXpath($nodePath)->current();
            // get image node
            $nodePath = $node->getNodePath();
            $nodePath .= '//img[starts-with(@id,"vidthumb")]';
            $image = $domQuery->queryXpath($nodePath)->current();
            // create datas
            $result = new VideoResult(array(
                'position' => $node->getLineNo(),
                'ad' => $node->ownerDocument->saveHtml($node),
                'link' => $link->getAttribute('href'),
                'anchor' => $link->textContent,
                'image' => $image->getAttribute('src'),
            ));
            $results->append($result);
        }
        return $results;
    }
}
