<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\TestFiles\TestCaseWithMarkupAssertions;

class MarkupAssertionsTraitTest extends TestCase
{
    /**
     * Holds a clean instance of TestCaseWithMarkupAssertions.
     *
     * @var TestCaseWithMarkupAssertions
     */
    protected $testcase;

    public function setUp()
    {
        $this->testcase = new TestCaseWithMarkupAssertions;
    }

    /**
     * @dataProvider selectorVariantProvider()
     */
    public function testAssertContainsSelector($selector)
    {
        $this->testcase->assertContainsSelector(
            $selector,
            '<a href="https://example.com" id="my-link" class="link another-class">Example</a>'
        );
    }

    public function testAssertContainsWithMultipleMatches()
    {
        $test = new TestCaseWithMarkupAssertions;
        $html = '<a href="#home">Home</a> | <a href="#about">About</a> | <a href="#contact">Contact</a>';

        $this->testcase->assertContainsSelector('a', $html);
    }

    /**
     * @dataProvider selectorVariantProvider()
     */
    public function testAssertNotContainsSelector($selector)
    {
        $this->testcase->assertNotContainsSelector(
            $selector,
            '<h1 id="page-title" class="foo bar">This element has little to do with the link.</h1>'
        );
    }

    /**
     * Data provider for testAssertContainsSelector().
     */
    public function selectorVariantProvider()
    {
        return [
            'Simple tag name' => ['a'],
            'Class name' => ['.link'],
            'Multiple class names' => ['.link.another-class'],
            'Element ID' => ['#my-link'],
            'Tag name with class' => ['a.link'],
            'Tag name with ID' => ['a#my-link'],
            'Tag with href attribute' => ['a[href="https://example.com"]'],
        ];
    }
}
