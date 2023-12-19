<?php

namespace SteveGrunwell\PHPUnit_Markup_Assertions\Constraints;

use SebastianBergmann\Exporter\Exporter;

/**
 * PHPUnit's exporter() method wasn't introduced until PHPUnit 8, which causes errors when we try
 * to call $this->exporter()->export() in assertions.
 *
 * Instead, this trait exposes an exportValue() method that verifies that the relevant export
 * method is present.
 */
trait ExporterTrait
{
    /**
     * Exports a value as a string.
     *
     * @param mixed $value The value to be exported.
     *
     * @return string A string representation.
     */
    protected function exportValue($value): string
    {
        // PHPUnit 8.x and newer only instantiate the exporter when needed.
        if (method_exists($this, 'exporter') && $this->exporter() instanceof Exporter) {
            return $this->exporter()->export($value);
        }

        // PHPUnit 7.x creates the exporter in the constructor.
        if (isset($this->exporter) && $this->exporter instanceof Exporter) {
            return $this->exporter->export($value);
        }

        // For everything else, just use var_export() and hope for the best.
        return var_export($value, true);
    }
}
