<?php

/*
 * This file is part of the SearchEngineCrawler package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace SearchEngineCrawler\Engine\Link\Builder\Google;

class Video extends AbstractGoogle
{
    protected function buildLinkWithOptions()
    {
        $params = '';
        $options = $this->getOptions();

        // add start
        $params .= sprintf('&start=%s', ($options->getNumPerPage() * ($options->getPage()-1)));
        // add num per page
        $params .= sprintf('&num=%s&complete=0', $options->getNumPerPage());
        // add language
        if($options->getLang()) {
            $params .= sprintf('&gl=%s', $options->getLang());
        }

        $keyword = urlencode(htmlspecialchars_decode(stripslashes($options->getKeyword())));
        $uri = sprintf(
            'http://%s/search?tbm=vid&q=%s&ie=utf-8&oe=utf-8&pws=0%s',
            $options->getHost(), $keyword, $params
        );

        return $uri;
    }
}
