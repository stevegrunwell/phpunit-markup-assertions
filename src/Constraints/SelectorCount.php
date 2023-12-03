<?php

namespace SteveGrunwell\PHPUnit_Markup_Assertions\Constraints;

use PHPUnit\Framework\Constraint\Constraint;
use SteveGrunwell\PHPUnit_Markup_Assertions\DOM;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

class SelectorCount extends Constraint
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var Selector
     */
    private $selector;

    /**
     * @param Selector $selector The query selector.
     * @param int      $count    The expected number of matches.
     */
    public function __construct(Selector $selector, $count)
    {
        $this->selector = $selector;
        $this->count = $count;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function toString(): string
    {
        return sprintf(
            'contains %d instance(s) of selector %s',
            $this->count,
            $this->exporter()->export($this->selector->getValue())
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

        return $dom->countInstancesOfSelector($this->selector) === $this->count;
    }
}
