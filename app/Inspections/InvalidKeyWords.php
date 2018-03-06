<?php

namespace App\Inspections;

class InvalidKeyWords
{
    /**
     * All registered invalid keywords
     *
     * @var array
     */
    protected $invalids = [
        'spammer',
        'bad man'
    ];

    /**
     * Detects if given string is an invalid keyword
     *
     * @param $string
     * @throws \Exception
     */
    public function detect($string)
    {
        foreach ($this->invalids as $invalid) {
            if (stripos($string, $invalid) !== false) {
                throw new \Exception('Invalid key word found');
            }
        }
    }
}