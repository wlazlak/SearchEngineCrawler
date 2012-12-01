<?php

namespace SearchEngineCrawler\Engine\Link;

use Zend\Dom\Query as DomQuery;
use SearchEngineCrawler\ResultSet\Link\ResultSet;
use SearchEngineCrawler\Engine\Link\Features;

abstract class AbstractLink implements LinkInterface, Features\NodeAdProviderInterface,
    Features\NodeLineNumberProviderInterface, Features\NodeListProviderInterface
{
    /**
     * @var ResultSet
     */
    protected $set;

    /**
     * @var string
     */
    protected $resultClass;

    /**
     * @var DomQuery
     */
    protected $domQuery;

    /**
     *
     * @param string $html
     */
    public function source($html)
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->strictErrorChecking = false;
        @$dom->loadHTML($html);
        $dom->formatOutput = true;
        $html = $dom->saveHTML();

        $domQuery = $this->getDomQuery();
        $domQuery->setDocumentHtml($html);
        return $this;
    }

    /**
     * Set of result
     * @return ResultSet
     */
    public function getResults()
    {
        if(null === $this->set) {
            $this->set = new ResultSet();
        }
        return $this->set;
    }

    /**
     * Method to detect links, initial method
     * @param string $source html code source
     */
    public function detect(&$source)
    {
        $results = $this->getResults();
        $nodes = $this->getNodeList();
        foreach($nodes as $node) {
            if(null === $this->validateNode($node)) {
                continue;
            }
            $result = new $this->resultClass;
            $result->position = $this->getNodeLineNumber($node);
            $result->ad = $this->getNodeAd($node);
            $result->link = $this->getNodeLink($node);
            if($this instanceof Features\NodeLinkAnchorInterface) {
                $result->anchor = $this->getNodeLinkAnchor($node);
            }
            if($this instanceof Features\NodeMapProviderInterface) {
                $result->map = $this->getNodeMapLink($node);
                $this->address = $this->getNodeAddress($node);
            }
            if($this instanceof Features\NodeExtensionProviderInterface) {
                $result->extension = $this->getExtension($node);
            }
            if($this instanceof Features\NodeRichSnippetProviderInterface) {
                $result->richsnippet = $this->getRichSnippet($node);
            }

            $results->append($result);
        }
    }

    /**
     * Get the ad
     * @param \DOMElement $node
     * @return string the node ad
     */
    public function getNodeAd(\DOMElement $node)
    {
        return $node->ownerDocument->saveHtml($node);
    }

    /**
     * Get the line number
     * @param \DOMElement $node
     * @return integer the line number
     */
    public function getNodeLineNumber(\DOMElement $node)
    {
        return $node->getLineNo();
    }

    /**
     * Perform an XPath query
     *
     * @param  string|array $xpathQuery
     * @param  string|null  $query      CSS selector query
     * @throws Exception\RuntimeException
     * @return NodeList
     */
    public function xpath($xpathQuery, $query = null)
    {
        return $this->getDomQuery()->queryXpath($xpathQuery, $query);
    }

    /**
     * Get the dom query object
     * @return Query
     */
    public function getDomQuery()
    {
        if(null === $this->domQuery) {
            $this->setDomQuery(new DomQuery());
        }
        return $this->domQuery;
    }

    /**
     * Set the dom query object
     * @param DomQuery $domQuery
     * @return AbstractLink
     */
    public function setDomQuery(DomQuery $domQuery)
    {
        $this->domQuery = $domQuery;
        return $this;
    }
}
