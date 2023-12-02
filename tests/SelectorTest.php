<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\Exceptions\AttributeArrayException;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

/**
 * @testdox Selector class
 *
 * @covers SteveGrunwell\PHPUnit_Markup_Assertions\Selector
 */
final class SelectorTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_accept_string_selectors()
    {
        $selector = new Selector('a.some-class');

        $this->assertSame('a.some-class', $selector->getValue());
    }

    /**
     * @test
     *
     * @dataProvider provideAttributes
     */
    public function it_should_automatically_convert_attribute_arrays_to_strings($attributes, $expected)
    {
        $selector = new Selector($attributes);

        $this->assertSame($expected, $selector->getValue());
    }

    /**
     * @test
     */
    public function it_should_throw_if_unable_to_handle_attribute_array()
    {
        $this->expectException(AttributeArrayException::class);

        new Selector([]);
    }

    /**
     * @test
     */
    public function it_should_be_able_to_be_cast_to_a_string()
    {
        $selector = new Selector('a.some-class');

        $this->assertSame('a.some-class', (string) $selector);
    }

    /**
     * Data provider for testFlattenAttributeArray().
     *
     * @return Iterable{array<string, scalar>, string} The attribute array and teh expected string.
     */
    public function provideAttributes()
    {
        yield 'Single attribute' => [
            [
                'id' => 'first-name',
            ],
            '*[id="first-name"]',
        ];

        yield 'Multiple attributes' => [
            [
                'id' => 'first-name',
                'value' => 'Ringo',
            ],
            '*[id="first-name"][value="Ringo"]',
        ];

        yield 'Boolean attribute' => [
            [
                'checked' => null,
            ],
            '*[checked]',
        ];

        yield 'Data attribute' => [
            [
                'data-foo' => 'bar',
            ],
            '*[data-foo="bar"]',
        ];

        yield 'Value contains quotes' => [
            [
                'name' => 'Austin "Danger" Powers',
            ],
            '*[name="Austin &quot;Danger&quot; Powers"]',
        ];
    }
}
