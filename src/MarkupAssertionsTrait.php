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
     * Assert that an element with the given attributes exists in the given markup.
     *
     * @param array  $attributes An array of HTML attributes that should be found on the element.
     * @param string $output     The output that should contain an element with the
     *                           provided $attributes.
     * @param string $message    A message to display if the assertion fails.
     */
    public function assertHasElementWithAttributes($attributes = [], $output = '', $message = '')
    {
        $this->assertContainsSelector(
            '*' . $this->flattenAttributeArray($attributes),
            $output,
            $message
        );
    }

    /**
     * Assert that an element with the given attributes does not exist in the given markup.
     *
     * @param array  $attributes An array of HTML attributes that should be found on the element.
     * @param string $output     The output that should not contain an element with the
     *                           provided $attributes.
     * @param string $message    A message to display if the assertion fails.
     */
    public function assertNotHasElementWithAttributes($attributes = [], $output = '', $message = '')
    {
        $this->assertNotContainsSelector(
            '*' . $this->flattenAttributeArray($attributes),
            $output,
            $message
        );
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

    /**
     * Given an array of HTML attributes, flatten them into a XPath attribute selector.
     *
     * @throws RiskyTestError When the $attributes array is empty.
     *
     * @param array $attributes HTML attributes and their values.
     *
     * @return string A XPath attribute query selector.
     */
    protected function flattenAttributeArray(array $attributes)
    {
        if (empty($attributes)) {
            throw new RiskyTestError('Attributes array is empty.');
        }

        array_walk($attributes, function (&$value, $key) {
            // Boolean attributes.
            if (null === $value) {
                $value = sprintf('[%s]', $key);
            } else {
                $value = sprintf('[%s="%s"]', $key, htmlspecialchars($value));
            }
        });

        return implode('', $attributes);
    }
}
