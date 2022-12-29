<?php
/**
 * Markup assertions for PHPUnit.
 *
 * @package PHPUnit_Markup_Assertions
 * @author  Steve Grunwell
 */

namespace SteveGrunwell\PHPUnit_Markup_Assertions;

use DOMDocument;
use Laminas\Dom\Document;
use Laminas\Dom\Document\Query;
use PHPUnit\Framework\RiskyTestError;

trait MarkupAssertionsTrait
{
    /**
     * Assert that the given string contains an element matching the given selector.
     *
     * @since 1.0.0
     *
     * @param string $selector A query selector for the element to find.
     * @param string $markup   The output that should contain the $selector.
     * @param string $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertContainsSelector($selector, $markup = '', $message = '')
    {
        $results = $this->executeDomQuery($markup, $selector);

        $this->assertGreaterThan(0, count($results), $message);
    }

    /**
     * Assert that the given string does not contain an element matching the given selector.
     *
     * @since 1.0.0
     *
     * @param string $selector A query selector for the element to find.
     * @param string $markup   The output that should not contain the $selector.
     * @param string $message  A message to display if the assertion fails.
     */
    public function assertNotContainsSelector($selector, $markup = '', $message = '')
    {
        $results = $this->executeDomQuery($markup, $selector);

        $this->assertEquals(0, count($results), $message);
    }

    /**
     * Assert the number of times an element matching the given selector is found.
     *
     * @since 1.0.0
     *
     * @param int    $count    The number of matching elements expected.
     * @param string $selector A query selector for the element to find.
     * @param string $markup   The markup to run the assertion against.
     * @param string $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertSelectorCount($count, $selector, $markup = '', $message = '')
    {
        $results = $this->executeDomQuery($markup, $selector);

        $this->assertCount($count, $results, $message);
    }

    /**
     * Assert that an element with the given attributes exists in the given markup.
     *
     * @since 1.0.0
     *
     * @param array  $attributes An array of HTML attributes that should be found on the element.
     * @param string $markup     The output that should contain an element with the
     *                           provided $attributes.
     * @param string $message    A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertHasElementWithAttributes($attributes = [], $markup = '', $message = '')
    {
        $this->assertContainsSelector(
            '*' . $this->flattenAttributeArray($attributes),
            $markup,
            $message
        );
    }

    /**
     * Assert that an element with the given attributes does not exist in the given markup.
     *
     * @since 1.0.0
     *
     * @param array  $attributes An array of HTML attributes that should be found on the element.
     * @param string $markup     The output that should not contain an element with the
     *                           provided $attributes.
     * @param string $message    A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertNotHasElementWithAttributes($attributes = [], $markup = '', $message = '')
    {
        $this->assertNotContainsSelector(
            '*' . $this->flattenAttributeArray($attributes),
            $markup,
            $message
        );
    }

    /**
     * Assert an element's contents contain the given string.
     *
     * @since 1.1.0
     *
     * @param string $contents The string to look for within the DOM node's contents.
     * @param string $selector A query selector for the element to find.
     * @param string $markup   The output that should contain the $selector.
     * @param string $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertElementContains($contents, $selector = '', $markup = '', $message = '')
    {
        $method = method_exists($this, 'assertStringContainsString')
            ? 'assertStringContainsString'
            : 'assertContains';

        $this->$method(
            $contents,
            $this->getInnerHtmlOfMatchedElements($markup, $selector),
            $message
        );
    }

    /**
     * Assert an element's contents do not contain the given string.
     *
     * @since 1.1.0
     *
     * @param string $contents The string to look for within the DOM node's contents.
     * @param string $selector A query selector for the element to find.
     * @param string $markup   The output that should not contain the $selector.
     * @param string $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertElementNotContains($contents, $selector = '', $markup = '', $message = '')
    {
        $method = method_exists($this, 'assertStringNotContainsString')
            ? 'assertStringNotContainsString'
            : 'assertNotContains';

        $this->$method(
            $contents,
            $this->getInnerHtmlOfMatchedElements($markup, $selector),
            $message
        );
    }

    /**
     * Assert an element's contents contain the given regular expression pattern.
     *
     * @since 1.1.0
     *
     * @param string $regexp   The regular expression pattern to look for within the DOM node.
     * @param string $selector A query selector for the element to find.
     * @param string $markup   The output that should contain the $selector.
     * @param string $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertElementRegExp($regexp, $selector = '', $markup = '', $message = '')
    {
        $method = method_exists($this, 'assertMatchesRegularExpression')
            ? 'assertMatchesRegularExpression'
            : 'assertRegExp';

        $this->$method(
            $regexp,
            $this->getInnerHtmlOfMatchedElements($markup, $selector),
            $message
        );
    }

    /**
     * Assert an element's contents do not contain the given regular expression pattern.
     *
     * @since 1.1.0
     *
     * @param string $regexp   The regular expression pattern to look for within the DOM node.
     * @param string $selector A query selector for the element to find.
     * @param string $markup   The output that should not contain the $selector.
     * @param string $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertElementNotRegExp($regexp, $selector = '', $markup = '', $message = '')
    {
        $method = method_exists($this, 'assertDoesNotMatchRegularExpression')
            ? 'assertDoesNotMatchRegularExpression'
            : 'assertNotRegExp';

        $this->$method(
            $regexp,
            $this->getInnerHtmlOfMatchedElements($markup, $selector),
            $message
        );
    }

    /**
     * Build a new DOMDocument from the given markup, then execute a query against it.
     *
     * @since 1.0.0
     *
     * @param string $markup The HTML for the DOMDocument.
     * @param string $query  The DOM selector query.
     *
     * @return \Laminas\Dom\Document\NodeList
     */
    protected function executeDomQuery($markup, $query)
    {
        return Query::execute($query, new Document($markup), Query::TYPE_CSS);
    }

    /**
     * Given an array of HTML attributes, flatten them into a XPath attribute selector.
     *
     * @since 1.0.0
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

    /**
     * Given HTML markup and a DOM selector query, collect the innerHTML of the matched selectors.
     *
     * @since 1.1.0
     *
     * @param string $markup The HTML for the DOMDocument.
     * @param string $query  The DOM selector query.
     *
     * @return string The concatenated innerHTML of any matched selectors.
     */
    protected function getInnerHtmlOfMatchedElements($markup, $query)
    {
        $results  = $this->executeDomQuery($markup, $query);
        $contents = [];

        // Loop through results and collect their innerHTML values.
        foreach ($results as $result) {
            $document = new DOMDocument();
            $document->appendChild($document->importNode($result->firstChild, true));

            $contents[] = trim($document->saveHTML());
        }

        return implode(PHP_EOL, $contents);
    }
}
