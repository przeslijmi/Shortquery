<?php declare(strict_types=1);

namespace Przeslijmi\Shortquery\Tools;

/**
 * Tool used for advanced splitting of string.
 */
class Splitter
{

    /**
     * Text that has to be spitted.
     *
     * @var string
     */
    private $text = '';

    /**
     * Starting string or char to look for.
     *
     * @var string
     */
    private $start = '';

    /**
     * Stopping string[] or char[] to look for.
     *
     * @var string[]
     */
    private $stop = [];

    /**
     * String (char) of incrementation of one level that make tool blind for stop sign.
     *
     * @var string
     */
    private $incr = '';

    /**
     * String (char) of decrementation of one level that make tool sightfull again (if this is decrementation to lvl 0).
     *
     * @var string
     */
    private $decr = '';

    /**
     * Escape char that make tool blind for this and next character.
     *
     * @var string
     */
    private $esc = '';

    /**
     * Constructor.
     *
     * @param string $text  Text that has to be spitted.
     * @param string $start Starting string or char to look for.
     * @param array  $stop  Stopping string[] or char[] to look for.
     * @param string $incr  Opt., `(`. String (char) of incrementation of one level that make tool blind for stop sign.
     * @param string $decr  Opt., `)`. String (char) of decrementation of one level that make tool sightfull again.
     * @param string $esc   Opt., `\`. Escape char that make tool blind for this and next character.
     */
    public function __construct(
        string $text,
        string $start,
        array $stop,
        string $incr = '(',
        string $decr = ')',
        string $esc = '\\'
    ) {

        // Save.
        $this->text  = $text;
        $this->start = $start;
        $this->stop  = $stop;
        $this->incr  = $incr;
        $this->decr  = $decr;
        $this->esc   = $esc;
    }

    /**
     * Spits text (runs the tool).
     *
     * @return array With splitting result.
     */
    public function split() : array
    {

        // Explode text.
        $text = str_split($this->text, 1);

        // Prepare mechanism.
        // Save letters only on lvl >= 0 (-1 = turned off).
        $level = -1;
        // For escaping characters.
        $ignoreNext = false;
        // Counter of result zones.
        $zone = -1;
        // Array for results.
        $result = [];

        foreach ($text as $charNo => $char) {

            // Serve escaping characters.
            if ($char === $this->esc) {
                $ignoreNext = true;
                continue;
            } elseif ($ignoreNext === true) {
                $ignoreNext = false;
                continue;
            }

            // Main splitting - decide on zone.
            if ($char === $this->start && $level === -1) {
                ++$level;

                ++$zone;
                $result[$zone] = [
                    'text'  => '',
                    'start' => $charNo,
                ];

            } elseif (in_array($char, $this->stop) === true && $level === 0) {
                --$level;
                $result[$zone]['text'] .= $char;

            } elseif ($char === $this->incr && $level >= 0) {
                ++$level;

            } elseif ($char === $this->decr && $level >= 1) {
                --$level;
            }

            // Save results.
            if ($level >= 0) {
                $result[$zone]['text'] .= $char;
            }
        }//end foreach

        return $result;
    }
}
