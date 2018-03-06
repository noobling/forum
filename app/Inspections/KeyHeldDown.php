<?php

namespace App\Inspections;

class KeyHeldDown
{
    /**
     * Detects if a key has been held down.
     *
     * @param $string
     * @throws \Exception
     */
    public function detect($string)
    {
        if (preg_match('/(.)\\1{4,}/', $string)) {
            throw new \Exception('Key held down');
        }
    }
}