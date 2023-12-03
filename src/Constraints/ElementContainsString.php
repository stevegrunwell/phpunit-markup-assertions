<?php

namespace SteveGrunwell\PHPUnit_Markup_Assertions\Constraints;

use PHPUnit\Framework\Constraint\Constraint;
use SteveGrunwell\PHPUnit_Markup_Assertions\DOM;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

class ElementContainsString extends Constraint
{
    /**
     * @var bool
     */
    private $ignore_case;

    /**
     * @var string
     */
    private $needle;

    /**
     * @var Selector
     */
    private $selector;

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
            'contains string %s',
            $this->exporter()->export($this->needle)
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $other evaluated value or object
     *
     * @return string
     */
    protected function failureDescription($other): string
    {
        return sprintf(
            'element with selector %s in %s %s',
            $this->exporter()->export($this->selector->getValue()),
            $this->exporter()->export($other),
            $this->toString()
        );
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     *
     * @return bool
     */
    protected function matches($value): bool
    {
        $dom = new DOM($value);
        $fn = $this->ignore_case ? 'stripos' : 'strpos';

        // Iterate through each matching element and look for the text.
        foreach ($dom->getInnerHtml($this->selector) as $html) {
            if ($fn($html, $this->needle) !== false) {
                return true;
            }
        }

        return false;
    }
}
