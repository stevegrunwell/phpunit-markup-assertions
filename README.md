# PHPUnit Markup Assertions

[![Build Status](https://travis-ci.org/stevegrunwell/phpunit-markup-assertions.svg?branch=develop)](https://travis-ci.org/stevegrunwell/phpunit-markup-assertions)
[![Coverage Status](https://coveralls.io/repos/github/stevegrunwell/phpunit-markup-assertions/badge.svg?branch=develop)](https://coveralls.io/github/stevegrunwell/phpunit-markup-assertions?branch=develop)

This library introduces the `MarkupAssertionsTrait` trait for use in [PHPUnit](https://phpunit.de) tests.

These assertions enable you to inspect generated markup without having to muddy tests with [`DOMDocument`](http://php.net/manual/en/class.domdocument.php) or nasty regular expressions. If you're generating markup at all with PHP, the PHPUnit Markup Assertions trait aims to make the output testable.

## Example

```php
use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\MarkupAssertionsTrait;

class MyUnitTest extends TestCase
{
    use MarkupAssertionsTrait;

    /**
     * Ensure the #first-name and #last-name selectors are present in the form.
     */
    public function testRenderFormContainsInputs()
    {
        $output = render_form();

        $this->assertContainsSelector('#first-name', $output);
        $this->assertContainsSelector('#last-name', $output);
    }
}
```

## Installation

To add PHPUnit Markup Assertions to your project, first install the library via Composer:

```sh
$ composer require --dev stevegrunwell/phpunit-markup-assertions
```

Next, import the `SteveGrunwell\PHPUnit_Markup_Assertions\MarkupAssertionsTrait` trait into each test case that will leverage the assertions:

```php
use PHPUnit\Framework\TestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\MarkupAssertionsTrait;

class MyTestCase extends TestCase
{
    use MarkupAssertionsTrait;
}
```

### Making PHPUnit Markup Assertions available globally

If you'd like the methods to be available across your entire test suite, you might consider [sub-classing the PHPUnit test case and applying the trait there](https://phpunit.de/manual/current/en/extending-phpunit.html#extending-phpunit.PHPUnit_Framework_TestCase):

```php
# tests/TestCase.php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use SteveGrunwell\PHPUnit_Markup_Assertions\MarkupAssertionsTrait;

class TestCase extends BaseTestCase
{
    use MarkupAssertionsTrait;
}
```

Then update your other test cases to use your new base:

```php
# tests/Unit/ExampleTest.php

namespace Tests/Unit;

use Tests\TestCase;

class MyUnitTest extends TestCase
{
    // This class now automatically has markup assertions.
}
```

## Available methods

### assertContainsSelector()

Assert that the given string contains an element matching the given selector.

<dl>
    <dt>$selector</dt>
    <dd>A query selector for the element to find.</dd>
    <dt>$output</dt>
    <dd>The output that should contain the $selector.</dd>
    <dt>$message</dt>
    <dd>A message to display if the assertion fails.</dd>
</dl>

#### Example

```php
public function testBodyContainsImage()
{
    $body = getPageBody();

    $this->assertContainsSelector('img', $body, 'Did not find an image in the page body.');
}
```

### assertNotContainsSelector()

Assert that the given string does not contain an element matching the given selector.

This method is the inverse of [`assertContainsSelector()`](#assertcontainsselector).

<dl>
    <dt>$selector</dt>
    <dd>A query selector for the element to find.</dd>
    <dt>$output</dt>
    <dd>The output that should not contain the $selector.</dd>
    <dt>$message</dt>
    <dd>A message to display if the assertion fails.</dd>
</dl>
