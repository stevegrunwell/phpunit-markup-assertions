<?php

namespace SteveGrunwell\PHPUnit_Markup_Assertions\Constraints;

use PHPUnit\Framework\Constraint\Constraint;
use SteveGrunwell\PHPUnit_Markup_Assertions\DOM;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

/**
 * Evaluate whether or not the element(s) matching the given selector contain a given string.
 */
class ElementContainsString extends Constraint
{
    /**
     * A cache of matches that we have checked against.
     *
     * @var array<string>
     */
    protected $matchingElements = [];

    /**
     * @var Selector
     */
    protected $selector;

    /**
     * @var bool
     */
    private $ignore_case;

    /**
     * @var string
     */
    private $needle;

    /**
     * @param Selector $selector    The query selector.
     * @param string   $needle      The string to search for within the matching element(s).
     * @param bool     $ignore_case Optional. If true, search in a case-insensitive fashion.
     *                              Default is false (case-sensitive searching).
     */
    public function __construct(Selector $selector, $needle, $ignore_case = false)
    {
        $this->selector = $selector;
        $this->needle = $needle;
        $this->ignore_case = $ignore_case;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function toString(): string
    {
        return sprintf(
            '%s string %s',
            count($this->matchingElements) >= 2 ? 'contain' : 'contains',
            $this->exporter()->export($this->needle)
        );
    }

    /**
     * Return additional failure description where needed.
     *
     * The function can be overridden to provide additional failure
     * information like a diff
     *
     * @param mixed $other evaluated value or object
     */
    protected function additionalFailureDescription($other): string
    {
        if (empty($this->matchingElements)) {
            return '';
        }

        return sprintf(
            "%s\n%s",
            count($this->matchingElements) >= 2 ? 'Matching elements:' : 'Matching element:',
            $this->exportMatchesArray($this->matchingElements)
        );
    }

    /**
     * Export an array of DOM matches for a selector.
     *
     * @param array<string> $matches
     *
     * @return string
     */
    protected function exportMatchesArray(array $matches): string
    {
        return '[' . PHP_EOL . '    ' . implode(PHP_EOL . '    ', $matches) . PHP_EOL . ']';
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $html The evaluated markup. Will not actually be used, instead replaced with
     *                    {@see $this->matches}.
     *
     * @return string
     */
    protected function failureDescription($html): string
    {
        if (empty($this->matchingElements)) {
            return "any elements match selector '{$this->selector->getValue()}'";
        }

        $label = count($this->matchingElements) >= 2
            ? 'any elements matching selector %s %s'
            : 'element matching selector %s %s';

        return sprintf(
            $label,
            $this->exporter()->export($this->selector->getValue()),
            $this->toString()
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $html The HTML to match against.
     *
     * @return bool
     */
    protected function matches($html): bool
    {
        $dom = new DOM($html);
        $fn = $this->ignore_case ? 'stripos' : 'strpos';

        // Iterate through each matching element and look for the text.
        foreach ($dom->getInnerHtml($this->selector) as $html) {
            if ($fn($html, $this->needle) !== false) {
                return true;
            }
        }

        // Query again to get the outer elements for error reporting.
        $this->matchingElements = $dom->getOuterHtml($this->selector);

        return false;
    }
}
