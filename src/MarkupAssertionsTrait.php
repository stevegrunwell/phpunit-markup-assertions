<?php

/**
 * Markup assertions for PHPUnit.
 *
 * @package PHPUnit_Markup_Assertions
 * @author  Steve Grunwell
 */

namespace SteveGrunwell\PHPUnit_Markup_Assertions;

use PHPUnit\Framework\Constraint\LogicalNot;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ContainsSelector;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ElementContainsString;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ElementMatchesRegExp;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\SelectorCount;

trait MarkupAssertionsTrait
{
    /**
     * Assert that the given string contains an element matching the given selector.
     *
     * @since 1.0.0
     *
     * @param string|array<string, scalar> $selector A query selector to search for.
     * @param string                       $markup   The output that should contain the $selector.
     * @param string                       $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertContainsSelector(
        $selector,
        string $markup = '',
        string $message = ''
    ) {
        $constraint = new ContainsSelector(new Selector($selector));

        static::assertThat($markup, $constraint, $message);
    }

    /**
     * Assert that the given string does not contain an element matching the given selector.
     *
     * @since 1.0.0
     *
     * @param string|array<string, scalar> $selector A query selector to search for.
     * @param string                       $markup   The output that should not contain the $selector.
     * @param string                       $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertNotContainsSelector(
        $selector,
        string $markup = '',
        string $message = ''
    ) {
        $constraint = new LogicalNot(new ContainsSelector(new Selector($selector)));

        static::assertThat($markup, $constraint, $message);
    }

    /**
     * Assert the number of times an element matching the given selector is found.
     *
     * @since 1.0.0
     *
     * @param int                          $count    The number of matching elements expected.
     * @param string|array<string, scalar> $selector A query selector to search for.
     * @param string                       $markup   The markup to run the assertion against.
     * @param string                       $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertSelectorCount(
        int $count,
        $selector,
        string $markup = '',
        string $message = ''
    ) {
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
    public function assertHasElementWithAttributes(
        array $attributes = [],
        string $markup = '',
        string $message = ''
    ) {
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
    public function assertNotHasElementWithAttributes(
        $attributes = [],
        $markup = '',
        $message = ''
    ) {
        $constraint = new LogicalNot(new ContainsSelector(new Selector($attributes)));

        static::assertThat($markup, $constraint, $message);
    }

    /**
     * Assert an element's contents contain the given string.
     *
     * @since 1.1.0
     *
     * @param string                       $contents The string to look for within the DOM node's contents.
     * @param string|array<string, scalar> $selector A query selector to search for.
     * @param string                       $markup   The output that should contain the $selector.
     * @param string                       $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertElementContains(
        string $contents,
        $selector = '',
        string $markup = '',
        string $message = ''
    ) {
        $constraint = new ElementContainsString(new Selector($selector), $contents);

        static::assertThat($markup, $constraint, $message);
    }

    /**
     * Assert an element's contents do not contain the given string.
     *
     * @since 1.1.0
     *
     * @param string                       $contents The string to look for within the DOM node's contents.
     * @param string|array<string, scalar> $selector A query selector to search for.
     * @param string                       $markup   The output that should not contain the $selector.
     * @param string                       $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertElementNotContains(
        string $contents,
        $selector = '',
        string $markup = '',
        string $message = ''
    ) {
        $constraint = new LogicalNot(new ElementContainsString(new Selector($selector), $contents));

        static::assertThat($markup, $constraint, $message);
    }

    /**
     * Assert an element's contents contain the given regular expression pattern.
     *
     * @since 1.1.0
     *
     * @param string                       $regexp   The regular expression pattern to look for within the DOM node.
     * @param string|array<string, scalar> $selector A query selector to search for.
     * @param string                       $markup   The output that should contain the $selector.
     * @param string                       $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertElementRegExp(
        string $regexp,
        $selector = '',
        string $markup = '',
        string $message = ''
    ) {
        $constraint = new ElementMatchesRegExp(new Selector($selector), $regexp);

        static::assertThat($markup, $constraint, $message);
    }

    /**
     * Assert an element's contents do not contain the given regular expression pattern.
     *
     * @since 1.1.0
     *
     * @param string                       $regexp   The regular expression pattern to look for within the DOM node.
     * @param string|array<string, scalar> $selector A query selector to search for.
     * @param string                       $markup   The output that should not contain the $selector.
     * @param string                       $message  A message to display if the assertion fails.
     *
     * @return void
     */
    public function assertElementNotRegExp(
        string $regexp,
        $selector = '',
        string $markup = '',
        string $message = ''
    ) {
        $constraint = new LogicalNot(new ElementMatchesRegExp(new Selector($selector), $regexp));

        static::assertThat($markup, $constraint, $message);
    }
}
