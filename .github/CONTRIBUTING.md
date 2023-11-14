# Contributing to PHPUnit Markup Assertions

Thank you for your interest in contributing the ongoing development of the PHPUnit Markup Assertion library!


## Contributing code

Begin by cloning the GitHub repo locally and installing the dependencies with [Composer](https://getcomposer.org):

```sh
# Clone the repository + change into the directory
$ git clone https://github.com/stevegrunwell/phpunit-markup-assertions.git \
    && cd phpunit-markup-assertions

# Install local dependencies
$ composer install
```


### Branching

Pull requests should be based off the `develop` branch, which represents the current development state of the library. The only thing ever merged into `master` should be new release branches, at the time a release is tagged.

To create a new feature branch:

```bash
# Start on develop, making sure it's up-to-date
$ git checkout develop && git pull

# Create a new branch for your feature
$ git checkout -b feature/my-cool-new-feature
```

When submitting a new pull request, your `feature/my-cool-new-feature` should be compared against `develop`.


### Coding standards

This project uses [the PSR-2 coding standards](http://www.php-fig.org/psr/psr-2/).


### Running unit tests

[PHPUnit](https://phpunit.de/) is included as a development dependency, and should be run regularly. When submitting changes, please be sure to add or update unit tests accordingly. You may run unit tests at any time by running:

```bash
$ ./vendor/bin/phpunit
```

#### Code coverage

[![Coverage Status](https://coveralls.io/repos/github/stevegrunwell/phpunit-markup-assertions/badge.svg?branch=develop)](https://coveralls.io/github/stevegrunwell/phpunit-markup-assertions?branch=develop)

To generate a report of code coverage for the current branch, you may run the following Composer script, which will generate an HTML report in `tests/coverage/`:

```bash
$ composer test-coverage
```

Note that [both the Xdebug and tokenizer PHP extensions must be installed and active](https://phpunit.de/manual/current/en/textui.html) on the machine running the tests.
