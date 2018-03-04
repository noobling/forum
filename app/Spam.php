<?php

namespace App;

class Spam
{
    /**
     * @param $string
     * @return bool
     * @throws \Exception
     */
    public function detect($string)
    {
        $this->detectInvalidKeywords($string);

        return false;
    }

    /**
     * @param $string
     * @throws \Exception
     */
    public function detectInvalidKeywords($string)
    {
        $invalids = [
            'spammer',
            'bad man'
        ];

        foreach ($invalids as $invalid) {
            if (stripos($string, $invalid) !== false) {
                throw new \Exception('Invalid key word found');
            }
        }
    }
}