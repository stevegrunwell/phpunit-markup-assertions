<?php

namespace SteveGrunwell\PHPUnit_Markup_Assertions\Constraints;

use PHPUnit\Framework\Constraint\Constraint;
use SteveGrunwell\PHPUnit_Markup_Assertions\DOM;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

/**
 * Evaluate whether or not markup contains at least one instance of the given selector.
 */
class ContainsSelector extends Constraint
{
    /**
     * @var Selector
     */
    private $selector;

    /**
     * @param Selector $selector The query selector.
     */
    public function __construct(Selector $selector)
    {
        $this->selector = $selector;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function toString(): string
    {
        return 'contains selector ' . $this->exporter()->export($this->selector->getValue());
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $html The HTML to evaluate.
     *
     * @return bool
     */
    protected function matches($html): bool
    {
        $dom = new DOM($html);

        return $dom->countInstancesOfSelector($this->selector) > 0;
    }
}
