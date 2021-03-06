<?php

/*
 * This file is part of the SearchEngineCrawler package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 * This work is licensed under a [Creative Commons Attribution-NonCommercial 3.0 Unported License](http://creativecommons.org/licenses/by-nc/3.0/).
 */

namespace SearchEngineCrawler\Engine\Link\Google\Web;

use SearchEngineCrawler\Engine\Link\AbstractLink;
use SearchEngineCrawler\Engine\Link\Features;

class Image extends AbstractLink implements Features\NodeImageSourceProviderInterface
{
    /**
     * Result class container
     * @var string
     */
    protected $resultClass = 'SearchEngineCrawler\ResultSet\Link\Result\Image';

    /**
     * Get the node list, each node contains
     * the ad & line number
     * @return Zend\Dom\NodeList
     */
    public function getNodeList()
    {
        return $this->xpath('//div[@id="ires"]//div[@id="iur"]//ul[@class="rg_ul"]/li');
    }

    /**
     * Check if a node is valid, if the node match with the type required
     * If node is valid, return the node
     * @param \DOMElement $node node to validate
     * @return null|\DOMElement
     */
    public function validateNode(\DOMElement $node)
    {
        $nodePath = $node->getNodePath();
        $nodePath .= '//a/img[starts-with(@name,"imgthumb")]';
        return $this->xpath($nodePath)->current();
    }

    /**
     * Get the link
     * @param \DOMElement $node
     * @return integer the line number
     */
    public function getNodeLink(\DOMElement $node)
    {
        $link = $this->validateNode($node);
        $href = $link->parentNode->getAttribute('href');
        if(preg_match('#&imgrefurl=(?P<domain>.*)&h=\d+&w=\d+&#', $href, $regs)) {
            $href = $regs['domain'];
        }
        return $href;
    }

    /**
     * Get the link
     * @param \DOMElement $node
     * @return integer the line number
     */
    public function getNodeImageSource(\DOMElement $node)
    {
        $node = $this->validateNode($node);
        return $node->getAttribute('src');
    }
}
