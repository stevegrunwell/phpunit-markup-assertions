<?php

namespace Tests\Unit\Constraints;

use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ElementMatchesRegExp;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

/**
 * @testdox Constraints\ElementMatchesRegExp
 *
 * @covers SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ElementMatchesRegExp
 *
 * @group Constraints
 */
class ElementMatchesRegExpTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_match_a_pattern_in_the_given_markup()
    {
        $constraint = new ElementMatchesRegExp(new Selector('p'), '/\d+/');
        $html = '<h1>Title</h1><p>12345</p>';

        $this->assertTrue($constraint->evaluate($html, '', true));
    }

    /**
     * @test
     */
    public function it_should_respect_flags()
    {
        $constraint = new ElementMatchesRegExp(new Selector('p'), '/[A-Z]+/i');
        $html = '<h1>Title</h1><p>123hello456</p>';

        $this->assertTrue($constraint->evaluate($html, '', true));
    }

    /**
     * @test
     */
    public function it_should_fail_if_no_match_is_found()
    {
        $constraint = new ElementMatchesRegExp(new Selector('p'), '/[a-z]+/');
        $html = '<h1>Title</h1><p>12345</p>';

        $this->assertFalse(
            $constraint->evaluate($html, '', true),
            '"12345" does not match pattern /[a-z]/'
        );
    }

    /**
     * @test
     */
    public function it_should_test_against_the_inner_contents_of_the_found_nodes()
    {
        $constraint = new ElementMatchesRegExp(new Selector('p'), '/class/');
        $html = '<p class="first">First</p><p class="second">Second</p>';

        $this->assertFalse(
            $constraint->evaluate($html, '', true),
            'The string "class" does not appear in either paragraph, and thus should not be matched.'
        );
    }

    /**
     * @test
     */
    public function it_should_scope_queries_to_the_selector()
    {
        $constraint = new ElementMatchesRegExp(new Selector('h1'), '/\d+/');
        $html = '<h1>Title</h1><p>12345</p>';

        $this->assertFalse($constraint->evaluate($html, '', true));
    }

    /**
     * @test
     */
    public function it_should_fail_with_a_useful_error_message()
    {
        $html = '<p>Some other string</p>';
        $expected = <<<'MSG'
Failed asserting that element matching selector 'p' matches regular expression '/some\sstring/'.
Matching element:
[
    <p>Some other string</p>
]
MSG;

        try {
            (new ElementMatchesRegExp(new Selector('p'), '/some\sstring/'))->evaluate($html);
        } catch (\Throwable $e) {
            $this->assertSame($expected, $e->getMessage());
            return;
        }

        $this->fail('Did not receive the expected error message.');
    }

    /**
     * @test
     */
    public function it_should_include_all_relevant_matches_in_error_messages()
    {
        $selector = new Selector('p');
        $html = '<p>Some other string</p><p>Yet another string</p>';
        $expected = <<<'MSG'
Failed asserting that any elements matching selector 'p' match regular expression '/some\sstring/'.
Matching elements:
[
    <p>Some other string</p>
    <p>Yet another string</p>
]
MSG;

        try {
            (new ElementMatchesRegExp($selector, '/some\sstring/'))->evaluate($html);
        } catch (\Throwable $e) {
            $this->assertSame($expected, $e->getMessage());
            return;
        }

        $this->fail('Did not receive the expected error message.');
    }

    /**
     * @test
     */
    public function it_should_provide_a_simple_error_message_if_no_selector_matches_are_found()
    {
        $html = '<p>Some other string</p><p>Yet another string</p>';
        $expected = "Failed asserting that any elements match selector 'h1'.";

        try {
            (new ElementMatchesRegExp(new Selector('h1'), 'some string'))->evaluate($html);
        } catch (\Throwable $e) {
            $this->assertSame($expected, $e->getMessage());
            return;
        }

        $this->fail('Did not receive the expected error message.');
    }
}
