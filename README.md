# PHPUnit Markup Assertions

[![Build Status](https://travis-ci.org/stevegrunwell/phpunit-markup-assertions.svg?branch=develop)](https://travis-ci.org/stevegrunwell/phpunit-markup-assertions)
[![Coverage Status](https://coveralls.io/repos/github/stevegrunwell/phpunit-markup-assertions/badge.svg?branch=develop)](https://coveralls.io/github/stevegrunwell/phpunit-markup-assertions?branch=develop)
[![GitHub release](https://img.shields.io/github/release/stevegrunwell/phpunit-markup-assertions.svg)](https://github.com/stevegrunwell/phpunit-markup-assertions/releases)

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

These are the assertions made available to PHPUnit via the `MarkupAssertionsTrait`.

* [`assertContainsSelector()`](#assertcontainsselector)
* [`assertNotContainsSelector()`](#assertnotcontainsselector)
* [`assertSelectorCount()`](#assertselectorcount)
* [`assertHasElementWithAttributes()`](#asserthaselementwithattributes)
* [`assertNotHasElementWithAttributes()`](#assertnothaselementwithattributes)
* [`assertElementContains()`](#assertelementcontains)
* [`assertElementNotContains()`](#assertelementnotcontains)
* [`assertElementRegExp()`](#assertelementregexp)
* [`assertElementNotRegExp()`](#assertelementnotregexp)

### assertContainsSelector()

Assert that the given string contains an element matching the given selector.

<dl>
    <dt>(string) $selector</dt>
    <dd>A query selector for the element to find.</dd>
    <dt>(string) $output</dt>
    <dd>The output that should contain the <code>$selector</code>.</dd>
    <dt>(string) $message</dt>
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
    <dt>(string) $selector</dt>
    <dd>A query selector for the element to find.</dd>
    <dt>(string) $output</dt>
    <dd>The output that should not contain the <code>$selector</code>.</dd>
    <dt>(string) $message</dt>
    <dd>A message to display if the assertion fails.</dd>
</dl>

### assertSelectorCount()

Assert the number of times an element matching the given selector is found.

<dl>
    <dt>(int) $count</dt>
    <dd>The number of matching elements expected.</dd>
    <dt>(string) $selector</dt>
    <dd>A query selector for the element to find.</dd>
    <dt>(string) $output</dt>
    <dd>The markup to run the assertion against.</dd>
    <dt>(string) $message</dt>
    <dd>A message to display if the assertion fails.</dd>
</dl>

#### Example

```php
public function testPostList()
{
    factory(Post::class, 10)->create();

    $response = $this->get('/posts');

    $this->assertSelectorCount(10, 'li.post-item', $response->getBody());
}
```

### assertHasElementWithAttributes()

Assert that an element with the given attributes exists in the given markup.

<dl>
    <dt>(array) $attributes</dt>
    <dd>An array of HTML attributes that should be found on the element.</dd>
    <dt>(string) $output</dt>
    <dd>The output that should contain an element with the provided <code>$attributes</code>.</dd>
    <dt>(string) $message</dt>
    <dd>A message to display if the assertion fails.</dd>
</dl>

#### Example

```php
public function testExpectedInputsArePresent()
{
    $user = getUser();
    $form = getFormMarkup();

    $this->assertHasElementWithAttributes(
        [
            'name' => 'first-name',
            'value' => $user->first_name,
        ],
        $form,
        'Did not find the expected input for the user first name.'
    );
}
```

### assertNotHasElementWithAttributes()

Assert that an element with the given attributes does not exist in the given markup.

<dl>
    <dt>(array) $attributes</dt>
    <dd>An array of HTML attributes that should not be found on the element.</dd>
    <dt>(string) $output</dt>
    <dd>The output that should not contain an element with the provided <code>$attributes</code>.</dd>
    <dt>(string) $message</dt>
    <dd>A message to display if the assertion fails.</dd>
</dl>

### assertElementContains()

Assert that the element with the given selector contains a string.

<dl>
    <dt>(string) $contents</dt>
    <dd>The string to look for within the DOM node's contents.</dd>
    <dt>(string) $selector</dt>
    <dd>A query selector for the element to find.</dd>
    <dt>(string) $output</dt>
    <dd>The output that should contain the <code>$selector</code>.</dd>
    <dt>(string) $message</dt>
    <dd>A message to display if the assertion fails.</dd>
</dl>

#### Example

```php
public function testColumnShowsUserEmail()
{
    $user = getUser();
    $table = getTableMarkup();

    $this->assertElementContains(
        $user->email,
        'td.email',
        $table,
        'The <td class="email"> should contain the user\'s email address.'
    );
}
```

### assertElementNotContains()

Assert that the element with the given selector does not contain a string.

This method is the inverse of [`assertElementContains()`](#assertelementcontains).

<dl>
    <dt>(string) $contents</dt>
    <dd>The string to look for within the DOM node's contents.</dd>
    <dt>(string) $selector</dt>
    <dd>A query selector for the element to find.</dd>
    <dt>(string) $output</dt>
    <dd>The output that should contain the <code>$selector</code>.</dd>
    <dt>(string) $message</dt>
    <dd>A message to display if the assertion fails.</dd>
</dl>

### assertElementRegExp()

Assert that the element with the given selector contains a string.

This method works just like [`assertElementContains()`](#assertelementcontains), but uses regular expressions instead of simple string matching.

<dl>
    <dt>(string) $regexp</dt>
    <dd>The regular expression pattern to look for within the DOM node.</dd>
    <dt>(string) $selector</dt>
    <dd>A query selector for the element to find.</dd>
    <dt>(string) $output</dt>
    <dd>The output that should contain the <code>$selector</code>.</dd>
    <dt>(string) $message</dt>
    <dd>A message to display if the assertion fails.</dd>
</dl>

### assertElementNotRegExp()

Assert that the element with the given selector does not contain a string.

This method is the inverse of [`assertElementRegExp()`](#assertelementregexp) and behaves like [`assertElementNotContains()`](#assertelementnotcontains) except with regular expressions instead of simple string matching.

<dl>
    <dt>(string) $regexp</dt>
    <dd>The regular expression pattern to look for within the DOM node.</dd>
    <dt>(string) $selector</dt>
    <dd>A query selector for the element to find.</dd>
    <dt>(string) $output</dt>
    <dd>The output that should contain the <code>$selector</code>.</dd>
    <dt>(string) $message</dt>
    <dd>A message to display if the assertion fails.</dd>
</dl>
