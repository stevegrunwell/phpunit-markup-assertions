# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.4.0] – 2022-12-28

* Force UTF-8 encoding for better support for non-Latin character sets ([#35])
* Move away from deprecated classes in laminas/laminas-dom ([#32])

## [1.3.1] — 2020-01-14

* Fix PHPUnit warnings regarding `assertContains()` and `assertRegExp()`. Props [@jakobbuis](https://github.com/jakobbuis) ([#20], [#27], [#28])
* Refactor the internal test scaffolding, including a move from Travis CI to GitHub Actions. Props [@peter279k](https://github.com/peter279k) for the assist with GitHub Actions ([#24])
* Added PHP 8.0 support ([#26])

## [1.3.0] — 2020-01-27

* Replace `zendframework/zend-dom` with `laminas/laminas-dom` ([#16])
* Update Composer dependencies, add a `composer test` script ([#15])


## [1.2.0] - 2018-03-27

* Bumped the minimum version of zendframework/zend-dom to 2.7, which includes a fix for attribute values that include spaces ([#13]).


## [1.1.0] - 2018-01-14

* Added the `assertElementContains()`, `assertElementNotContains()`, `assertElementRegExp()`, and `assertElementNotRegExp()` assertions, for verifying the contents of elements that match the given DOM query ([#6])
* Moved the `Tests` namespace into a development-only autoloader, to prevent them from potentially being included in projects using this library ([#7])
* [Based on this article by Martin Hujer](https://blog.martinhujer.cz/17-tips-for-using-composer-efficiently/#tip-%236%3A-put-%60composer.lock%60-into-%60.gitignore%60-in-libraries), remove the `composer.lock` file from the library ([#8])
* _Lower_ the minimum version of [zendframework/zend-dom](https://packagist.org/packages/zendframework/zend-dom) to 2.2.5 for maximum portability ([#9])


## [1.0.0] - 2017-10-24

* Initial release of the PHPUnit Markup Assertions Composer package.


[Unreleased]: https://github.com/stevegrunwell/phpunit-markup-assertions/compare/main...develop
[1.4.0]: https://github.com/stevegrunwell/phpunit-markup-assertions/releases/tag/v1.4.0
[1.3.1]: https://github.com/stevegrunwell/phpunit-markup-assertions/releases/tag/v1.3.1
[1.3.0]: https://github.com/stevegrunwell/phpunit-markup-assertions/releases/tag/v1.3.0
[1.2.0]: https://github.com/stevegrunwell/phpunit-markup-assertions/releases/tag/v1.2.0
[1.1.0]: https://github.com/stevegrunwell/phpunit-markup-assertions/releases/tag/v1.1.0
[1.0.0]: https://github.com/stevegrunwell/phpunit-markup-assertions/releases/tag/v1.0.0
[#6]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/6
[#7]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/7
[#8]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/8
[#9]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/9
[#13]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/13
[#15]: https://github.com/stevegrunwell/phpunit-markup-assertions/pull/15
[#16]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/16
[#20]: https://github.com/stevegrunwell/phpunit-markup-assertions/pull/20
[#24]: https://github.com/stevegrunwell/phpunit-markup-assertions/pull/24
[#26]: https://github.com/stevegrunwell/phpunit-markup-assertions/pull/26
[#27]: https://github.com/stevegrunwell/phpunit-markup-assertions/pull/27
[#28]: https://github.com/stevegrunwell/phpunit-markup-assertions/pull/28
[#32]: https://github.com/stevegrunwell/phpunit-markup-assertions/pull/32
[#35]: https://github.com/stevegrunwell/phpunit-markup-assertions/pull/35
