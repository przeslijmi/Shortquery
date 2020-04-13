<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Data;

/**
 * Field echoing methods to help on models generator.
 */
abstract class FieldEchoingMethods
{

    /**
     * Returns indentation (4 spaces) repeated x times.
     *
     * @param integer $repeatments Number of repeatments.
     *
     * @return string
     */
    public function ind(int $repeatments) : string
    {

        // Lvd.
        $indent = '    ';

        return str_repeat($indent, $repeatments);
    }

    /**
     * Create line of PHP code out of given indent, content and new lines.
     *
     * @param integer $indent     How many 4-spaces indentations to add.
     * @param string  $lineOfCode Contents of PHP line of code.
     * @param integer $newLines   Optional, 1. How many of new lines add after code.
     *
     * @return string
     */
    public function ln(int $indent, string $lineOfCode, int $newLines = 1) : string
    {

        return $this->ind($indent) . $lineOfCode . str_repeat("\n", $newLines);
    }

    /**
     * Alias for var_export.
     *
     * @param mixed $variable Any kind of variable.
     *
     * @return string
     */
    public function ex($variable) : string
    {

        return var_export($variable, true);
    }

    /**
     * Alias for implode with enhancements.
     *
     * @param array  $array  What to implode.
     * @param string $start  How to start every element.
     * @param string $end    How to end every element.
     * @param string $middle How to connect each of elements.
     *
     * @return string
     */
    public function imp(array $array, string $start = '\'', string $end = '\'', string $middle = ', ') : string
    {

        // Lvd.
        $separator = $end . $middle . $start;

        // Add enclosers.
        foreach ($array as $i => $element) {
            $array[$i] = $start . str_replace($end, '\\' . $end, $element) . $end;
        }

        return implode($middle, $array);
    }

    /**
     * Convert array to comma separated values format ('a','b','c').
     *
     * @param array $array Array to be converted.
     *
     * @return string
     */
    public function csv(array $array) : string
    {

        return $this->imp($array, '\'', '\'', ',');
    }

    /**
     * Returns camel cased Field name (with `$this->` if needed).
     *
     * @param boolean $addScope Optional, false. To add `$this->` or not.
     *
     * @return string
     */
    public function cc(bool $addScope = false) : string
    {

        // Lvd.
        $scope = '';

        // Add scope.
        if ($addScope === true) {
            $scope = '$this->';
        }

        return $scope . $this->getName('camelCase');
    }
}
