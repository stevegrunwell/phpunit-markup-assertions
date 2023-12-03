<?php

namespace Tests\Unit\Constraints;

use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ElementContainsString;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

/**
 * @testdox Constraints\ElementContainsString
 *
 * @covers SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ElementContainsString
 */
class ElementContainsStringTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_match_a_string_in_the_given_markup()
    {
        $constraint = new ElementContainsString(new Selector('p'), 'This is the content', true);
        $html = '<h1>Title</h1><p>This is the content</p>';

        $this->assertTrue($constraint->evaluate($html, '', true));
    }

    /**
     * @test
     */
    public function it_should_match_in_a_case_sensitive_manner_by_default()
    {
        $constraint = new ElementContainsString(new Selector('p'), 'THIS IS THE CONTENT');
        $html = '<h1>Title</h1><p>This is the content</p>';

        $this->assertFalse(
            $constraint->evaluate($html, '', true),
            'By default, searches should be case-sensitive.'
        );
    }

    /**
     * @test
     */
    public function it_should_be_able_to_ignore_case()
    {
        $constraint = new ElementContainsString(new Selector('p'), 'THIS IS THE CONTENT', true);
        $html = '<h1>Title</h1><p>This is the content</p>';

        $this->assertTrue(
            $constraint->evaluate($html, '', true),
            'When $ignore_case is true, searches should be case-insensitive.'
        );
    }

    /**
     * @test
     */
    public function it_should_fail_if_no_match_is_found()
    {
        $constraint = new ElementContainsString(new Selector('p'), 'This is the content');
        $html = '<h1>This is the content</h1><p>But this is not</p>';

        $this->assertFalse($constraint->evaluate($html, '', true));
    }

    /**
     * @test
     */
    public function it_should_fail_with_a_useful_error_message()
    {
        $selector = new Selector('p.body');
        $html = '<p>Some other string</p>';

        try {
            (new ElementContainsString($selector, 'some string'))->evaluate($html);
        } catch (\Exception $e) {
            $this->assertSame(
                "Failed asserting that element with selector '{$selector}' in '{$html}' contains string 'some string'.",
                $e->getMessage()
            );
            return;
        }

        $this->fail('Did not receive the expected error message.');
    }
}
