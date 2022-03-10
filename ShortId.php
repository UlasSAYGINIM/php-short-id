<?php

namespace kotchuprik\short_id;

class ShortId
{
    protected $alphabet;
    protected $base_len;

    public function __construct($alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $this->alphabet = $alphabet;
        $this->base_len = strlen($this->alphabet);
    }

    public function encode($input, $neededLength = 0)
    {
        $output = '';
        if (is_numeric($neededLength)) {
            $neededLength--;
            if ($neededLength > 0) {
                $input += ($this->base_len ** $neededLength);
            }
        }
        for ($current = ($input != 0 ? floor(log($input, $this->base_len)) : 0); $current >= 0; $current--) {
            $powed = ($this->base_len ** $current);
            $floored = floor($input / $powed) % $this->base_len;
            $output = $output . substr($this->alphabet, $floored, 1);
            $input = $input - ($floored * $powed);
        }

        return $output;
    }

    public function decode($input, $neededLength = 0)
    {
        $output = 0;
        $length = strlen($input) - 1;
        for ($current = $length; $current >= 0; $current--) {
            $powed = ($this->base_len ** ($length - $current));
            $output = ($output + strpos($this->alphabet, substr($input, $current, 1)) * $powed);
        }
        if (is_numeric($neededLength)) {
            $neededLength--;
            if ($neededLength > 0) {
                $output -= $this->base_len ** $neededLength;
            }
        }

        return $output;
    }
}
