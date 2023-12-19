<?php

namespace Tests\Unit\Constraints;

use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\SelectorCount;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

/**
 * @testdox Constraints\SelectorCount
 *
 * @covers SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\SelectorCount
 *
 * @group Constraints
 */
class SelectorCountTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideMarkupVariants
     */
    public function it_should_determine_if_the_expected_number_of_instances_are_present(
        string $markup,
        Selector $selector,
        int $expected
    ) {
        $constraint = new SelectorCount($selector, $expected);

        $this->assertTrue($constraint->evaluate($markup, '', true));
    }

    /**
     * @test
     * @dataProvider provideMarkupVariants
     */
    public function it_should_fail_if_it_contains_a_different_number_of_matches()
    {
        $markup = '<p>This is the only paragraph</p>';

        $this->assertFalse((new SelectorCount(new Selector('p'), 0))->evaluate($markup, '', true));
        $this->assertFalse((new SelectorCount(new Selector('p'), 2))->evaluate($markup, '', true));
    }

    /**
     * @test
     */
    public function it_should_fail_with_a_useful_error_message()
    {
        $selector = new Selector('p.body');
        $html = '<h1>Some Title</h1>';

        try {
            (new SelectorCount($selector, 5))->evaluate($html);
        } catch (\Throwable $e) {
            $this->assertSame(
                "Failed asserting that '{$html}' contains 5 instance(s) of selector '{$selector}'.",
                $e->getMessage()
            );
            return;
        }

        $this->fail('Did not receive the expected error message.');
    }

    /**
     * Data provider for testAssertContainsSelector().
     *
     * @return iterable<string,array{string, Selector, int}>
     */
    public function provideMarkupVariants()
    {
        yield 'Simple count' => [
            '<h1>This is a title</h1><p>Content</p>',
            new Selector('h1'),
            1,
        ];

        yield 'Multiple siblings' => [
            '<ul><li>1</li><li>2</li><li>3</li></ul>',
            new Selector('li'),
            3
        ];

        yield 'Nested elements with low specificity' => [
            '<div class="top">Hello <div class="middle">There</div></div>',
            new Selector('div'),
            2,
        ];

        yield 'Nested elements with high-specificity' => [
            '<div class="top">Hello <div class="middle">There</div></div>',
            new Selector('div>div'),
            1,
        ];
    }
}
