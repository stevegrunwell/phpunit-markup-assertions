<?php
/**
 * Markup assertions for PHPUnit.
 *
 * @package PHPUnit_Markup_Assertions
 * @author  Steve Grunwell
 */

namespace SteveGrunwell\PHPUnit_Markup_Assertions;

use PHPUnit\Framework\RiskyTestError;
use Zend\Dom\Query;

trait MarkupAssertionsTrait
{

    /**
     * Assert that the given string contains an element matching the given selector.
     *
     * @param string $selector A query selector for the element to find.
     * @param string $output   The output that should contain the $selector.
     * @param string $message  A message to display if the assertion fails.
     */
    public function assertContainsSelector($selector, $output = '', $message = '')
    {
        $results = $this->executeDomQuery($output, $selector);

        $this->assertGreaterThan(0, count($results), $message);
    }

    /**
     * Assert that the given string does not contain an element matching the given selector.
     *
     * @param string $selector A query selector for the element to find.
     * @param string $output   The output that should not contain the $selector.
     * @param string $message  A message to display if the assertion fails.
     */
    public function assertNotContainsSelector($selector, $output = '', $message = '')
    {
        $results = $this->executeDomQuery($output, $selector);

        $this->assertEquals(0, count($results), $message);
    }

    /**
     * Build a new DOMDocument from the given markup, then execute a query against it.
     *
     * @param string $markup The HTML for the DOMDocument.
     * @param string $query  The DOM selector query.
     *
     * @return NodeList
     */
    protected function executeDomQuery($markup, $query)
    {
        $dom = new Query($markup);

        return $dom->execute($query);
    }
}
