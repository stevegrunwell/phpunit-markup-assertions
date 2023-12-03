<?php

namespace Tests\Unit\Constraints;

use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ContainsSelector;
use SteveGrunwell\PHPUnit_Markup_Assertions\Selector;

/**
 * @testdox Constraints\ContainsSelector
 *
 * @covers SteveGrunwell\PHPUnit_Markup_Assertions\Constraints\ContainsSelector
 */
class ContainsSelectorTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideSelectorVariants
     */
    public function it_should_find_matching_selectors_in_content($selector)
    {
        $constraint = new ContainsSelector(new Selector($selector));
        $html = '<a href="https://example.com" id="my-link" class="link another-class">Example</a>';

        $this->assertTrue($constraint->evaluate($html, '', true));
    }

    /**
     * @test
     * @dataProvider provideSelectorVariants
     */
    public function it_should_not_find_unmatched_selectors_in_content($selector)
    {
        $constraint = new ContainsSelector(new Selector($selector));
        $html = '<h1 id="page-title" class="foo bar">This element has little to do with the link.</h1>';

        $this->assertFalse($constraint->evaluate($html, '', true));
    }

    /**
     * @test
     */
    public function it_should_fail_with_a_useful_error_message()
    {
        $selector = new Selector('p.body');
        $html = '<h1>Some Title</h1>';

        try {
            (new ContainsSelector($selector))->evaluate($html);
        } catch (\Exception $e) {
            $this->assertSame(
                "Failed asserting that '{$html}' contains selector '{$selector}'.",
                $e->getMessage()
            );
            return;
        }

        $this->fail('Did not receive the expected error message.');
    }

    /**
     * Data provider for testAssertContainsSelector().
     *
     * @return Iterable<string,array<string>>
     */
    public function provideSelectorVariants()
    {
        yield 'Simple tag name' => ['a'];
        yield 'Class name' => ['.link'];
        yield 'Multiple class names' => ['.link.another-class'];
        yield 'Element ID' => ['#my-link'];
        yield 'Tag name with class' => ['a.link'];
        yield 'Tag name with ID' => ['a#my-link'];
        yield 'Tag with href attribute' => ['a[href="https://example.com"]'];
    }
}
