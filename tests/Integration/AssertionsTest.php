<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\MarkupAssertionsTrait;

/**
 * This integration test covers the actual assertion methods defined in the MarkupAssertionsTrait.
 *
 * The majority of testing for the internals can be found in the {@see tests/Unit} test suite.
 *
 * @testdox MarkupAssertionsTrait
 *
 * @covers SteveGrunwell\PHPUnit_Markup_Assertions\MarkupAssertionsTrait
 */
class MarkupAssertionsTraitTest extends TestCase
{
    use MarkupAssertionsTrait;

    /**
     * @var string
     */
    private $markup = <<<'HTML'
<main>
    <h1>Good news, everyone!</h1>
    <p><a href="https://example.com" class="link">According to the latest reports</a>,
    you can still test markup with PHPUnit_Markup_Assertions!</p>
    <p>#TestEverything</p>
</main>
HTML;

    public function testPresenceOfSelectors()
    {
        $this->assertContainsSelector('main', $this->markup);
        $this->assertContainsSelector('h1', $this->markup);
        $this->assertContainsSelector('a.link', $this->markup);
        $this->assertContainsSelector('main p', $this->markup);
        $this->assertContainsSelector('h1 + p', $this->markup);
        $this->assertContainsSelector('p > a', $this->markup);
        $this->assertContainsSelector('a[href$="example.com"]', $this->markup);

        $this->assertNotContainsSelector('h2', $this->markup);
        $this->assertNotContainsSelector('a[href="https://example.org"]', $this->markup);
        $this->assertNotContainsSelector('p main', $this->markup);

        $this->assertSelectorCount(0, 'h2', $this->markup);
        $this->assertSelectorCount(1, 'h1', $this->markup);
        $this->assertSelectorCount(2, 'p', $this->markup);

        $this->assertHasElementWithAttributes(['href' => 'https://example.com'], $this->markup);

        $this->assertNotHasElementWithAttributes(
            ['href' => 'https://example.org'],
            $this->markup,
            'URL uses .com, not .org.'
        );
    }

    public function testMatchingContentsOfSelectors()
    {
        $this->assertElementContains('Good news', 'main', $this->markup);
        $this->assertElementContains('Good news', 'h1', $this->markup);
        $this->assertElementContains('#TestEverything', 'p', $this->markup);
        $this->assertElementContains('class="link"', 'p', $this->markup);
        $this->assertElementContains('#TestEverything', 'main *:last-child', $this->markup);

        $this->assertElementNotContains('good news', 'h1', $this->markup, 'Case-sensitive by default.');
        $this->assertElementNotContains(
            '#TestEverything',
            'p:first-child',
            $this->markup,
            '#TestEverything is in the second paragraph'
        );
        $this->assertElementNotContains(
            'class="link"',
            'a',
            $this->markup,
            'The class is part of the outer, not inner HTML'
        );

        $this->assertElementRegExp('/\w+ news/', 'h1', $this->markup);
        $this->assertElementRegExp('/GOOD NEWS/i', 'h1', $this->markup);
        $this->assertElementRegExp('/latest reports/', 'p > a', $this->markup);

        $this->assertElementNotRegExp(
            '/\w+ news/',
            'p',
            $this->markup,
            'This text is in the heading, not the paragraph.'
        );
        $this->assertElementNotRegExp(
            '/#TESTEVERYTHING/',
            'p',
            $this->markup,
            'No case-insensitive flag'
        );
    }
}
