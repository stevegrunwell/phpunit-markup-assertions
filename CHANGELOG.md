# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.2.0] - 2018-03-27

* Bumped the minimum version of zendframework/zend-dom to 2.7, which includes a fix for attribute values that include spaces ([#13]).

## [1.1.0] - 2018-01-14

* Added the `assertElementContains()`, `assertElementNotContains()`, `assertElementRegExp()`, and `assertElementNotRegExp()` assertions, for verifying the contents of elements that match the given DOM query ([#6]).
* Moved the `Tests` namespace into a development-only autoloader, to prevent them from potentially being included in projects using this library ([#7]).
* [Based on this article by Martin Hujer](https://blog.martinhujer.cz/17-tips-for-using-composer-efficiently/#tip-%236%3A-put-%60composer.lock%60-into-%60.gitignore%60-in-libraries), remove the `composer.lock` file from the library ([#8]).
* _Lower_ the minimum version of [zendframework/zend-dom](https://packagist.org/packages/zendframework/zend-dom) to 2.2.5 for maximum portability ([#9]).

## [1.0.0] - 2017-10-24

* Initial release of the PHPUnit Markup Assertions Composer package.


[Unreleased]: https://github.com/stevegrunwell/phpunit-markup-assertions/compare/master...develop
[1.2.0]: https://github.com/stevegrunwell/phpunit-markup-assertions/releases/tag/v1.2.0
[1.1.0]: https://github.com/stevegrunwell/phpunit-markup-assertions/releases/tag/v1.1.0
[1.0.0]: https://github.com/stevegrunwell/phpunit-markup-assertions/releases/tag/v1.0.0
[#6]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/6
[#7]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/7
[#8]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/8
[#9]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/9
[#13]: https://github.com/stevegrunwell/phpunit-markup-assertions/issues/13
