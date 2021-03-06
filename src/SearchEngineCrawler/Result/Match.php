<?php

/*
 * This file is part of the SearchEngineCrawler package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 * This work is licensed under a [Creative Commons Attribution-NonCommercial 3.0 Unported License](http://creativecommons.org/licenses/by-nc/3.0/).
 */

namespace SearchEngineCrawler\Result;

use ArrayObject;
use SearchEngineCrawler\ResultSet\Link\Result\ResultInterface;

class Match extends ArrayObject
{
    public function __construct($page, $position, ResultInterface $result)
    {
        parent::__construct(array(
            'page' => $page,
            'position' => $position,
            'link' => $result,
        ));
    }

    public function getPage()
    {
        return $this->offsetGet('page');
    }

    public function getPosition()
    {
        return $this->offsetGet('position');
    }

    public function getLink()
    {
        return $this->offsetGet('link');
    }
}
