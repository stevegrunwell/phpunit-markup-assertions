<?php

/**
 * Markup assertions for PHPUnit.
 *
 * @package PHPUnit_Markup_Assertions
 * @author  Steve Grunwell
 */

namespace SteveGrunwell\PHPUnit_Markup_Assertions;

use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\RiskyTestError;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ContainsSelector;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ElementContainsString;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\SelectorCount;
use SteveGrunwell\PHPUnit_Markup_Assertions\Exceptions\AttributeArrayException;
use Symfony\Component\DomCrawler\Crawler;

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
        $constraint = new ContainsSelector(new Selector($selector));

        static::assertThat($markup, $constraint, $message);
    }

    /**
     * Assert that the given string does not contain an element matching the given selector.
     *
     * @since 1.0.0
     *
     * @param string $selector A query selector for the element to find.
     * @param string $markup   The output that should not contain the $selector.
     * @param string $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertNotContainsSelector($selector, $markup = '', $message = '')
    {
        $constraint = new LogicalNot(new ContainsSelector(new Selector($selector)));

        static::assertThat($markup, $constraint, $message);
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
        $constraint = new SelectorCount(new Selector($selector), $count);

        static::assertThat($markup, $constraint, $message);
    }

    /**
     * Assert that an element with the given attributes exists in the given markup.
     *
     * @since 1.0.0
     *
     * @param array<string, scalar> $attributes An array of HTML attributes that should be found
     *                                          on the element.
     * @param string                $markup     The output that should contain an element with the
     *                                          provided $attributes.
     * @param string                $message    A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertHasElementWithAttributes($attributes = [], $markup = '', $message = '')
    {
        $constraint = new ContainsSelector(new Selector($attributes));

        static::assertThat($markup, $constraint, $message);
    }

    /**
     * Assert that an element with the given attributes does not exist in the given markup.
     *
     * @since 1.0.0
     *
     * @param array<string, scalar> $attributes An array of HTML attributes that should be found
     *                                          on the element.
     * @param string                $markup     The output that should not contain an element with
     *                                          the provided $attributes.
     * @param string                $message    A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertNotHasElementWithAttributes($attributes = [], $markup = '', $message = '')
    {
        $constraint = new LogicalNot(new ContainsSelector(new Selector($attributes)));

        static::assertThat($markup, $constraint, $message);
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
        $constraint = new ElementContainsString(new Selector($selector), $contents);

        static::assertThat($markup, $constraint, $message);
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
        $constraint = new LogicalNot(new ElementContainsString(new Selector($selector), $contents));

        static::assertThat($markup, $constraint, $message);
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
            : 'assertRegExp'; // @codeCoverageIgnore

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
            : 'assertNotRegExp'; // @codeCoverageIgnore

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
     * @return Crawler
     *
     * @deprecated since 2.0.0. Use {@see DOM::query()} instead.
     *             This method will be removed in a future release!
     *
     * @codeCoverageIgnore
     */
    private function executeDomQuery($markup, $query)
    {
        return (new DOM($markup))->query(new Selector($query));
    }

    /**
     * Given an array of HTML attributes, flatten them into a XPath attribute selector.
     *
     * @since 1.0.0
     *
     * @throws RiskyTestError When the $attributes array is empty.
     *
     * @param array<string, scalar> $attributes HTML attributes and their values.
     *
     * @return string A XPath attribute query selector.
     *
     * @deprecated since 2.0.0. Use the Selector object instead.
     *             This method will be removed in a future release!
     *
     * @codeCoverageIgnore
     */
    private function flattenAttributeArray(array $attributes)
    {
        try {
            return (new Selector($attributes))->getValue();
        } catch (AttributeArrayException $e) {
            throw new RiskyTestError($e->getMessage(), $e->getCode(), $e);
        }
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
     *
     * @deprecated since 2.0.0. SOME OTHER ALTERNATIVE
     *             This method will be removed in a future release!
     *
     * @codeCoverageIgnore
     */
    private function getInnerHtmlOfMatchedElements($markup, $query)
    {
        return implode(PHP_EOL, (new DOM($markup))->getInnerHtml(new Selector($query)));
    }
}
