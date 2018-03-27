<?php

namespace Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\RiskyTestError;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
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
        $this->testcase->assertContainsSelector(
            'a',
            '<a href="#home">Home</a> | <a href="#about">About</a> | <a href="#contact">Contact</a>'
        );
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

    public function testAssertSelectorCount()
    {
        $this->testcase->assertSelectorCount(
            3,
            'li',
            '<ul><li>1</li><li>2</li><li>3</li></ul>'
        );
    }

    public function testAssertHasElementWithAttributes()
    {
        $this->testcase->assertHasElementWithAttributes(
            [
                'type' => 'email',
                'value' => 'test@example.com',
            ],
            '<label>Email</label><br><input type="email" value="test@example.com" />'
        );
    }

    /**
     * @link https://github.com/stevegrunwell/phpunit-markup-assertions/issues/13
     */
    public function testAssertHasElementWithAttributesWithSpacesInTheAttributeValue()
    {
        $this->testcase->assertHasElementWithAttributes(
            [
                'data-attr' => 'foo bar baz',
            ],
            '<div data-attr="foo bar baz">Contents</div>'
        );
    }

    public function testAssertNotHasElementWithAttributes()
    {
        $this->testcase->assertNotHasElementWithAttributes(
            [
                'type' => 'email',
                'value' => 'test@example.com',
            ],
            '<label>City</label><br><input type="text" value="New York" data-foo="bar" />'
        );
    }

    public function testAssertElementContains()
    {
        $this->testcase->assertElementContains(
            'ipsum',
            '#main',
            '<header>Lorem ipsum</header><div id="main">Lorem ipsum</div>'
        );
    }

    public function testAssertElementContainsMultipleSelectors()
    {
        $this->testcase->assertElementContains(
            'ipsum',
            '#main .foo',
            '<div id="main"><span class="foo">Lorem ipsum</span></div>'
        );
    }

    public function testAssertElementContainsScopesToSelector()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('The #main div does not contain the string "ipsum".');

        $this->testcase->assertElementContains(
            'ipsum',
            '#main',
            '<header>Lorem ipsum</header><div id="main">Foo bar baz</div>',
            'The #main div does not contain the string "ipsum".'
        );
    }

    public function testAssertElementNotContains()
    {
        $this->testcase->assertElementNotContains(
            'ipsum',
            '#main',
            '<header>Foo bar baz</header><div id="main">Some string</div>'
        );
    }

    public function testAssertElementRegExp()
    {
        $this->testcase->assertElementRegExp(
            '/[A-Z0-9-]+/',
            '#main',
            '<header>Lorem ipsum</header><div id="main">ABC123</div>'
        );
    }

    public function testAssertElementRegExpWithNestedElements()
    {
        $this->testcase->assertElementRegExp(
            '/[A-Z]+/',
            '#main',
            '<header>Lorem ipsum</header><div id="main"><span>ABC</span></div>'
        );
    }

    public function testAssertElementNotRegExp()
    {
        $this->testcase->assertElementNotRegExp(
            '/[0-9-]+/',
            '#main',
            '<header>Foo bar baz</header><div id="main">ABC</div>'
        );
    }

    /**
     * @dataProvider attributeProvider()
     */
    public function testFlattenAttributeArray($attributes, $expected)
    {
        $method = new ReflectionMethod($this->testcase, 'flattenAttributeArray');
        $method->setAccessible(true);

        $this->assertEquals($expected, $method->invoke($this->testcase, $attributes));
    }

    /**
     * @expectedException PHPUnit\Framework\RiskyTestError
     */
    public function testFlattenAttributeArrayThrowsRiskyTestError()
    {
        $method = new ReflectionMethod($this->testcase, 'flattenAttributeArray');
        $method->setAccessible(true);

        $method->invoke($this->testcase, []);
    }

    /**
     * @dataProvider innerHtmlProvider().
     */
    public function testGetInnerHtmlOfMatchedElements($markup, $selector, $expected) {
        $method = new ReflectionMethod($this->testcase, 'getInnerHtmlOfMatchedElements');
        $method->setAccessible(true);

        $this->assertEquals($expected, $method->invoke($this->testcase, $markup, $selector));
    }

    /**
     * Data provider for testFlattenAttributeArray().
     */
    public function attributeProvider()
    {
        return [
            'Single attribute' => [
                [
                    'id' => 'first-name',
                ],
                '[id="first-name"]',
            ],
            'Multiple attributes' => [
                [
                    'id' => 'first-name',
                    'value' => 'Ringo',
                ],
                '[id="first-name"][value="Ringo"]',
            ],
            'Boolean attribute' => [
                [
                    'checked' => null,
                ],
                '[checked]',
            ],
            'Data attribute' => [
                [
                    'data-foo' => 'bar',
                ],
                '[data-foo="bar"]',
            ],
            'Value contains quotes' => [
                [
                    'name' => 'Austin "Danger" Powers',
                ],
                '[name="Austin &quot;Danger&quot; Powers"]',
            ],
        ];
    }

    /**
     * Data provider for testGetInnerHtmlOfMatchedElements().
     */
    public function innerHtmlProvider()
    {
        return [
            'A single match' => [
                '<body>Foo bar baz</body>',
                'body',
                'Foo bar baz',
            ],
            'Multiple matching elements' => [
                '<ul><li>Foo</li><li>Bar</li><li>Baz</li>',
                'li',
                'Foo' . PHP_EOL . 'Bar' . PHP_EOL . 'Baz',
            ],
            'Nested elements' => [
                '<h1><a href="https://example.com">Example site</a></h1>',
                'h1',
                '<a href="https://example.com">Example site</a>',
            ],
        ];
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
