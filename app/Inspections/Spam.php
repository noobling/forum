<?php

namespace App\Inspections;

class Spam
{
    protected $inspections = [
      InvalidKeyWords::class,
      KeyHeldDown::class
    ];

    /**
     * Detects if given string is spam or not
     *
     * @param $string
     * @return bool
     * @throws \Exception
     */
    public function detect($string)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($string);
        }

        return false;
    }
}