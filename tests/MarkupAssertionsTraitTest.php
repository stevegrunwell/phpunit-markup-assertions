<?php

namespace Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\RiskyTestError;
use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\MarkupAssertionsTrait;

/**
 * @covers SteveGrunwell\PHPUnit_Markup_Assertions\MarkupAssertionsTrait
 */
class MarkupAssertionsTraitTest extends TestCase
{
    use MarkupAssertionsTrait;

    /**
     * @test
     * @testdox assertContainsSelector() should find matching selectors
     * @dataProvider provideSelectorVariants
     */
    public function assertContainsSelector_should_find_matching_selectors(string $selector): void
    {
        $this->assertContainsSelector(
            $selector,
            '<a href="https://example.com" id="my-link" class="link another-class">Example</a>'
        );
    }

    /**
     * @test
     * @testdox assertContainsSelector() should pick up multiple instances of a selector
     */
    public function assertContainsSelector_should_pick_up_multiple_instances(): void
    {
        $this->assertContainsSelector(
            'a',
            '<a href="#home">Home</a> | <a href="#about">About</a> | <a href="#contact">Contact</a>'
        );
    }

    /**
     * @test
     * @testdox assertNotContainsSelector() should verify that the given selector does not exist
     * @dataProvider provideSelectorVariants
     */
    public function assertNotContainsSelector_should_verify_that_the_given_selector_does_not_exist(
        string $selector
    ): void {
        $this->assertNotContainsSelector(
            $selector,
            '<h1 id="page-title" class="foo bar">This element has little to do with the link.</h1>'
        );
    }

    /**
     * @test
     * @testdox assertSelectorCount() should count the instances of a selector
     */
    public function assertSelectorCount_should_count_the_number_of_instances(): void
    {
        $this->assertSelectorCount(
            3,
            'li',
            '<ul><li>1</li><li>2</li><li>3</li></ul>'
        );
    }

    /**
     * @test
     * @testdox assertHasElementWithAttributes() should find an element with the given attributes
     */
    public function assertHasElementWithAttributes_should_find_elements_with_matching_attributes(): void
    {
        $this->assertHasElementWithAttributes(
            [
                'type' => 'email',
                'value' => 'test@example.com',
            ],
            '<label>Email</label><br><input type="email" value="test@example.com" />'
        );
    }

    /**
     * @test
     * @testdox assertHasElementWithAttributes() should be able to parse spaces in attribute values
     * @ticket https://github.com/stevegrunwell/phpunit-markup-assertions/issues/13
     */
    public function assertHasElementWithAttributes_should_be_able_to_handle_spaces(): void
    {
        $this->assertHasElementWithAttributes(
            [
                'data-attr' => 'foo bar baz',
            ],
            '<div data-attr="foo bar baz">Contents</div>'
        );
    }

    /**
     * @test
     * @testdox assertNotHasElementWithAttributes() should ensure no element has the provided attributes
     */
    public function assertNotHasElementWithAttributes_should_find_no_elements_with_matching_attributes(): void
    {
        $this->assertNotHasElementWithAttributes(
            [
                'type' => 'email',
                'value' => 'test@example.com',
            ],
            '<label>City</label><br><input type="text" value="New York" data-foo="bar" />'
        );
    }

    /**
     * @test
     * @testdox assertElementContains() should be able to search for a selector
     */
    public function assertElementContains_can_match_a_selector(): void
    {
        $this->assertElementContains(
            'ipsum',
            '#main',
            '<header>Lorem ipsum</header><div id="main">Lorem ipsum</div>'
        );
    }

    /**
     * @test
     * @testdox assertElementContains() should be able to chain multiple selectors
     */
    public function assertElementContains_can_chain_multiple_selectors(): void
    {
        $this->assertElementContains(
            'ipsum',
            '#main .foo',
            '<div id="main"><span class="foo">Lorem ipsum</span></div>'
        );
    }

    /**
     * @test
     * @testdox assertElementContains() should scope text to the selected element
     */
    public function assertElementContains_should_scope_matches_to_selector(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('The #main div does not contain the string "ipsum".');

        $this->assertElementContains(
            'ipsum',
            '#main',
            '<header>Lorem ipsum</header><div id="main">Foo bar baz</div>',
            'The #main div does not contain the string "ipsum".'
        );
    }

    /**
     * @test
     * @testdox assertElementContains() should handle various character sets
     * @dataProvider provideGreetingsInDifferentLanguages
     * @ticket https://github.com/stevegrunwell/phpunit-markup-assertions/issues/31
     */
    public function assertElementContains_should_handle_various_character_sets(string $greeting): void
    {
        $this->assertElementContains(
            $greeting,
            'h1',
            sprintf('<div><h1>%s</h1></div>', $greeting)
        );
    }

    /**
     * @test
     * @testdox assertElementNotContains() should be able to search for a selector
     */
    public function assertElementNotContains_can_match_a_selector(): void
    {
        $this->assertElementNotContains(
            'ipsum',
            '#main',
            '<div>Foo bar baz</div><div id="main">Some string</div>'
        );
    }

    /**
     * @test
     * @testdox assertElementNotContains() should handle various character sets
     * @dataProvider provideGreetingsInDifferentLanguages
     * @ticket https://github.com/stevegrunwell/phpunit-markup-assertions/issues/31
     */
    public function assertElementNotContains_should_handle_various_character_sets(string $greeting): void
    {
        $this->assertElementNotContains(
            $greeting,
            'h1',
            sprintf('<h1>Translation</h1><p>%s</p>', $greeting)
        );
    }

    /**
     * @test
     * @testdox assertElementRegExp() should use regular expression matching
     */
    public function assertElementRegExp_should_use_regular_expression_matching(): void
    {
        $this->assertElementRegExp(
            '/[A-Z0-9-]+/',
            '#main',
            '<header>Lorem ipsum</header><div id="main">ABC123</div>'
        );
    }

    /**
     * @test
     * @testdox assertElementRegExp() should be able to search for nested contents
     */
    public function assertElementRegExp_should_be_able_to_match_nested_contents(): void
    {
        $this->assertElementRegExp(
            '/[A-Z]+/',
            '#main',
            '<header>Lorem ipsum</header><div id="main"><span>ABC</span></div>'
        );
    }

    /**
     * @test
     * @testdox assertElementNotRegExp() should use regular expression matching
     */
    public function testAssertElementNotRegExp(): void
    {
        $this->assertElementNotRegExp(
            '/[0-9-]+/',
            '#main',
            '<header>Foo bar baz</header><div id="main">ABC</div>'
        );
    }


    /**
     * @test
     * @testdox flattenAttributeArray() should flatten an array of attributes
     * @dataProvider provideAttributes
     *
     * @param array<string,string> $attributes
     */
    public function flattenArrayAttribute_should_flatten_arrays_of_attributes(array $attributes, string $expected): void
    {
        $method = new \ReflectionMethod($this, 'flattenAttributeArray');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke($this, $attributes));
    }

    /**
     * @test
     * @testdox flattenAttributeArray() should throw a RiskyTestError if the array is empty
     * @dataProvider provideAttributes
     */
    public function flattenAttributeArray_should_throw_a_RiskyTestError_if_given_an_empty_array(): void
    {
        $this->expectException(RiskyTestError::class);

        $method = new \ReflectionMethod($this, 'flattenAttributeArray');
        $method->setAccessible(true);
        $method->invoke($this, []);
    }

    /**
     * @test
     * @testdox getInnerHtmlOfMatchedElements() should retrieve the inner HTML
     * @dataProvider provideInnerHtml
     */
    public function getInnerHtmlOfMatchedElements_should_retrieve_the_inner_HTML(
        string $markup,
        string $selector,
        string $expected
    ): void {
        $method = new \ReflectionMethod($this, 'getInnerHtmlOfMatchedElements');
        $method->setAccessible(true);

        $this->assertEquals($expected, $method->invoke($this, $markup, $selector));
    }

    /**
     * Data provider for testFlattenAttributeArray().
     *
     * @return array<string,array{array<string,string>,string}>
     */
    public function provideAttributes(): array
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
     *
     * @return array<string,array<string>>
     */
    public function provideInnerHtml(): array
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
     *
     * @return array<string,array<string>>
     */
    public function provideSelectorVariants(): array
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

    /**
     * Provide a list of strings in various language.
     *
     * @return array<string,array<string>>
     */
    public function provideGreetingsInDifferentLanguages(): array
    {
        return [
            'Arabic'    => ['مرحبا!'],
            'Chinese'   => ['你好'],
            'English'   => ['Hello'],
            'Hebrew'    => ['שלום'],
            'Japanese'  => ['こんにちは'],
            'Korean'    => ['안녕하십니까'],
            'Punjabi'   => ['ਸਤ ਸ੍ਰੀ ਅਕਾਲ'],
            'Ukrainian' => ['Привіт'],
        ];
    }
}
