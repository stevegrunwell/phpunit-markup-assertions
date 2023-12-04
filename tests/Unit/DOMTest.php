<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\DOM;
use SteveGrunwell\PHPUnit_Markup_Assertions\Exceptions\SelectorException;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

/**
 * @testdox DOM class
 *
 * @covers SteveGrunwell\PHPUnit_Markup_Assertions\DOM
 */
final class DOMTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider provideMarkupWithInnerClass
     */
    public function it_should_be_able_to_count_selectors(string $markup, int $expected)
    {
        $dom = new DOM($markup);
        $selector = new Selector('.inner');

        $this->assertSame($expected, $dom->countInstancesOfSelector($selector));
    }

    /**
     * @test
     * @testdox getInnerHtml() should retrieve the inner HTML for each matching element.
     */
    public function getInnerHtml_should_retrieve_the_inner_HTML_for_each_matching_element()
    {
        $markup = <<<'HTML'
<ul>
    <li>The <strong>strong</strong> element</li>
    <li>The <em>em</em> element</li>
    <li>The <kbd>kbd</kbd> element</li>
</ul>
HTML;
        $dom = new DOM($markup);

        $this->assertSame(
            [
                'The <strong>strong</strong> element',
                'The <em>em</em> element',
                'The <kbd>kbd</kbd> element',
            ],
            $dom->getInnerHtml(new Selector('li'))
        );
    }

    /**
     * @test
     * @testdox getInnerHtml() should return an empty array if there are no matches
     */
    public function getInnerHtml_should_return_an_empty_array_if_there_are_no_matches()
    {
        $dom = new DOM('<h1>A title</h1>');

        $this->assertEmpty($dom->getInnerHtml(new Selector('h2')));
    }

    /**
     * @test
     * @testdox getOuterHtml() should retrieve the outer HTML for each matching element.
     */
    public function getOuterHtml_should_retrieve_the_outer_HTML_for_each_matching_element()
    {
        $markup = <<<'HTML'
<ul>
    <li>The <strong>strong</strong> element</li>
    <li>The <em>em</em> element</li>
    <li>The <kbd>kbd</kbd> element</li>
</ul>
HTML;
        $dom = new DOM($markup);

        $this->assertSame(
            [
                '<li>The <strong>strong</strong> element</li>',
                '<li>The <em>em</em> element</li>',
                '<li>The <kbd>kbd</kbd> element</li>',
            ],
            $dom->getOuterHtml(new Selector('li'))
        );
    }

    /**
     * @test
     * @testdox getOuterHtml() should return an empty array if there are no matches
     */
    public function getOuterHtml_should_return_an_empty_array_if_there_are_no_matches()
    {
        $dom = new DOM('<h1>A title</h1>');

        $this->assertEmpty($dom->getOuterHtml(new Selector('h2')));
    }

    /**
     * @test
     * @testdox query() should throw a SelectorException if the selector is invalid
     */
    public function query_should_throw_a_SelectorException_if_the_selector_is_invalid()
    {
        $dom = new DOM('<p>Some markup</p>');
        $selector = new Selector('#');

        try {
            $dom->query($selector);
        } catch (\Exception $e) {
            $this->assertInstanceOf(SelectorException::class, $e);
            return;
        }

        $this->fail('Failed to catch a SelectorException from invalid selector "#".');
    }

    /**
     * Return test cases with varying numbers of .inner elements.
     *
     * @return iterable<string, array{string, int}>
     */
    public function provideMarkupWithInnerClass()
    {
        yield 'No matches' => [
            '<div class="outer"></div>',
            0,
        ];

        yield 'One match' => [
            '<div class="outer"><div class="inner">Hello</div></div>',
            1,
        ];

        yield 'Two matches' => [
            '<div class="outer"><div class="inner">One</div><div class="inner">Two</div></div>',
            2
        ];
    }
}
