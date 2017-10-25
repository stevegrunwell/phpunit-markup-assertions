<?php

namespace Tests;

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

    public function testAssertNotHasElementWithAttributes()
    {
        $this->testcase->assertNotHasElementWithAttributes(
            [
                'type' => 'email',
                'value' => 'test@example.com',
            ],
            '<label>City</label><br><input type="text" value="New York" />'
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
            'Value contains quotes' => [
                [
                    'name' => 'Austin "Danger" Powers',
                ],
                '[name="Austin &quot;Danger&quot; Powers"]',
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
