<?php

namespace SteveGrunwell\PHPUnit_Markup_Assertions;

use SteveGrunwell\PHPUnit_Markup_Assertions\Exceptions\SelectorException;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use Symfony\Component\DomCrawler\Crawler;

class DOM
{
    /**
     * The Crawler instance created during instantiation.
     *
     * @var Crawler
     */
    private $crawler;

    /**
     * Construct a new DOM instance from the given markup.
     *
     * @param string $markup The markup to be parsed.
     */
    public function __construct($markup)
    {
        $this->crawler = new Crawler($markup);
    }

    /**
     * Count the number of matches for $selector we find in $this->crawler.
     *
     * @param Selector $selector The query selector.
     *
     * @return int
     */
    public function countInstancesOfSelector(Selector $selector)
    {
        return count($this->query($selector));
    }

    /**
     * Retrieve the inner contents of elements matching the given selector.
     *
     * @param Selector $selector The query selector.
     *
     * @return array<string> The inner contents of the matched selector. Each match is a separate
     *                       value in the array.
     */
    public function getInnerHtml(Selector $selector)
    {
        return $this->query($selector)->each(function ($element) {
            return $element->html();
        });
    }

    /**
     * Retrieve the inner contents of elements matching the given selector.
     *
     * @param Selector $selector The query selector.
     *
     * @return array<string> The inner contents of the matched selector. Each match is a separate
     *                       value in the array.
     */
    public function getOuterHtml(Selector $selector)
    {
        return $this->query($selector)->each(function ($element) {
            return $element->outerHtml();
        });
    }

    /**
     * @param Selector $selector The query selector.
     *
     * @return Crawler A filtered version of $this->crawler.
     */
    public function query(Selector $selector)
    {
        try {
            return $this->crawler->filter($selector->getValue());
        } catch (SyntaxErrorException $e) {
            throw new SelectorException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
