<?php

namespace Tests\TestFiles;

use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\MarkupAssertionsTrait;

class TestCaseWithMarkupAssertions extends TestCase
{
    use MarkupAssertionsTrait;
}
