<?php

namespace Tests\Unit\Constraints;

use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ElementContainsString;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

/**
 * @testdox Constraints\ElementContainsString
 *
 * @covers SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ElementContainsString
 *
 * @group Constraints
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
     *
     * @dataProvider provideGreetingsInDifferentLanguages
     *
     * @ticket https://github.com/stevegrunwell/phpunit-markup-assertions/issues/31
     */
    public function it_should_be_able_to_handle_various_character_sets($greeting)
    {
        $constraint = new ElementContainsString(new Selector('h1'), $greeting);
        $html = sprintf('<div><h1>%s</h1></div>', $greeting);

        $this->assertTrue($constraint->evaluate($html, '', true));
    }

    /**
     * @test
     */
    public function it_should_test_against_the_inner_contents_of_the_found_nodes()
    {
        $constraint = new ElementContainsString(new Selector('p'), 'class');
        $html = '<p class="first">First</p><p class="second">Second</p>';

        $this->assertFalse(
            $constraint->evaluate($html, '', true),
            'The string "class" does not appear in either paragraph, and thus should not be matched.'
        );
    }

    /**
     * @test
     */
    public function it_should_fail_with_a_useful_error_message()
    {
        $html = '<p>Some other string</p>';
        $expected = <<<'MSG'
Failed asserting that element matching selector 'p' contains string 'some string'.
Matching element:
[
    <p>Some other string</p>
]
MSG;

        try {
            (new ElementContainsString(new Selector('p'), 'some string'))->evaluate($html);
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
        $html = '<p>Some other string</p><p>Yet another string</p>';
        $expected = <<<'MSG'
Failed asserting that any elements matching selector 'p' contain string 'some string'.
Matching elements:
[
    <p>Some other string</p>
    <p>Yet another string</p>
]
MSG;

        try {
            (new ElementContainsString(new Selector('p'), 'some string'))->evaluate($html);
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
            (new ElementContainsString(new Selector('h1'), 'some string'))->evaluate($html);
        } catch (\Throwable $e) {
            $this->assertSame($expected, $e->getMessage());
            return;
        }

        $this->fail('Did not receive the expected error message.');
    }

    /**
     * Provide a list of strings in various language.
     *
     * @return Iterable<string,array<string>>
     */
    public function provideGreetingsInDifferentLanguages()
    {
        yield 'Arabic'    => ['مرحبا!'];
        yield 'Chinese'   => ['你好'];
        yield 'English'   => ['Hello'];
        yield 'Hebrew'    => ['שלום'];
        yield 'Japanese'  => ['こんにちは'];
        yield 'Korean'    => ['안녕하십니까'];
        yield 'Punjabi'   => ['ਸਤ ਸ੍ਰੀ ਅਕਾਲ'];
        yield 'Ukrainian' => ['Привіт'];
    }
}
