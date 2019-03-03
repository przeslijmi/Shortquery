<?php

namespace Przeslijmi\Shortquery\Tools;

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
    private $decr = ''; // a

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
     * @param string $incr  (opt., `(`) String (char) of incrementation of one level that make tool blind for stop sign.
     * @param string $decr  (opt., `)`) String (char) of decrementation of one level that make tool sightfull again.
     * @param string $esc   (opt., `\`) Escape char that make tool blind for this and next character.
     *
     * @since v1.0
     */
    public function __construct(string $text, string $start, array $stop, string $incr='(', string $decr=')', string $esc='\\')
    {

        // save
        $this->text = $text;
        $this->start = $start;
        $this->stop = $stop;
        $this->incr = $incr;
        $this->decr = $decr;
        $this->esc = $esc;

        // @todo text not empty
        // @todo others only one char length
    }

    /**
     * Spits text (runs the tool).
     *
     * @return array With splitting result.
     * @since  v1.0
     */
    public function split() : array
    {

        // explode text
        $text = str_split($this->text, 1);

        // prepare mechanism
        $level = -1;          // save letters only on lvl >= 0 (-1 = turned off)
        $ignoreNext = false;  // for escaping characters
        $zone = -1;           // counter of result zones
        $result = [];         // array for results

        foreach ($text as $charNo => $char) {

            // serve escaping characters
            if ($char === $this->esc) {
                $ignoreNext = true;
                continue;
            } else if ($ignoreNext === true) {
                $ignoreNext = false;
                continue;
            }

            // main splitting - decide on zone
            if ($char === $this->start && $level === -1) {
                ++$level;

                ++$zone;
                $result[$zone] = [
                    'text'  => '',
                    'start' => $charNo,
                ];

            } else if (in_array($char, $this->stop) === true && $level === 0) {
                --$level;
                $result[$zone]['text'] .= $char;

            } else if ($char === $this->incr && $level >= 0) {
                ++$level;

            } else if ($char === $this->decr && $level >= 1) {
                --$level;
            }

            // save results
            if ($level >= 0) {
                $result[$zone]['text'] .= $char;
            }
        }//end foreach

        return $result;
    }
}
