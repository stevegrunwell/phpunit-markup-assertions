<?php

namespace SteveGrunwell\PHPUnit_Markup_Assertions\Constraints;

use PHPUnit\Framework\Constraint\Constraint;
use SteveGrunwell\PHPUnit_Markup_Assertions\DOM;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

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

        return $dom->countInstancesOfSelector($this->selector) > 0;
    }
}
