<?php

namespace SteveGrunwell\PHPUnit_Markup_Assertions;

use SteveGrunwell\PHPUnit_Markup_Assertions\Exceptions\AttributeArrayException;

/**
 * The Selector class presents a unified way of constructing CSS and XPath selectors.
 *
 * When given an array of attributes, the selector will automatically be collapsed into an XPath
 * attribute query string, while regular string selectors will be left alone.
 */
class Selector
{
    /**
     * The underlying selector, either as a CSS selector or XPath attribute query.
     *
     * @var string
     */
    private $selector;

    /**
     * @param string|array<string, ?scalar> $selector Either a CSS selector string or an array of
     *                                                attributes, the latter of which will be flattened
     *                                                automatically into an XPath attribute query.
     */
    public function __construct($selector)
    {
        if (is_array($selector)) {
            $selector = $this->attributeArrayToString($selector);
        }

        $this->selector = $selector;
    }

    /**
     * Magic method to enable Selectors to be cast to strings.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * Retrieve the selector string.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->selector;
    }

    /**
     * Given an array of attributes, flatten them into a CSS-compatible syntax.
     *
     * @param array<string, ?scalar> $attributes An array of attributes to flatten into an XPath
     *                                           attribute query path.
     *
     * @return string The flattened attribute array.
     */
    private function attributeArrayToString($attributes)
    {
        if (empty($attributes)) {
            throw new AttributeArrayException('Attributes array is empty.');
        }

        array_walk($attributes, function (&$value, $key) {
            // Boolean attributes.
            if (null === $value) {
                $value = sprintf('[%s]', $key);
            } else {
                $value = sprintf('[%s="%s"]', $key, htmlspecialchars($value));
            }
        });

        return '*' . implode('', $attributes);
    }
}
