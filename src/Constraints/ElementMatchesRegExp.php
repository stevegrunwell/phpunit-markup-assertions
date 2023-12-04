<?php

namespace SteveGrunwell\PHPUnit_Markup_Assertions\Constraints;

use SteveGrunwell\PHPUnit_Markup_Assertions\DOM;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Evaluate whether or not the contents of elements matching the given $selector match the given
 * PCRE regular expression.
 */
class ElementMatchesRegExp extends ElementContainsString
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @param Selector $selector The query selector.
     * @param string   $pattern  The regular expression pattern to test against the matching element(s).
     */
    public function __construct(Selector $selector, string $pattern)
    {
        $this->selector = $selector;
        $this->pattern = $pattern;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function toString(): string
    {
        return sprintf(
            '%s regular expression %s',
            count($this->matchingElements) >= 2 ? 'match' : 'matches',
            $this->exporter()->export($this->pattern)
        );
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $html value or object to evaluate
     *
     * @return bool
     */
    protected function matches($html): bool
    {
        $dom = new DOM($html);

        // Iterate through each matching element and look for the pattern.
        foreach ($dom->getInnerHtml($this->selector) as $html) {
            if (preg_match($this->pattern, $html)) {
                return true;
            }
        }

        // Query again to get the outer elements for error reporting.
        $this->matchingElements = $dom->getOuterHtml($this->selector);

        return false;
    }
}
