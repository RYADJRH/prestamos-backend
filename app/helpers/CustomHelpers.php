<?php

use Illuminate\Support\Collection;

if (!function_exists('create_acronym')) {
    function create_acronym($name, $length = 2)
    {
        $acronym = "";
        $words = new Collection(explode(' ', $name));

        if ($words->count() === 1) {
            $acronym = Str::substr($words[0], 0, 2);
        } else {
            foreach ($words as $word) {
                $acronym .= Str::substr($word, 0, 1);
            }
        }

        $acronym = Str::substr($acronym, 0, $length);
        $acronym = Str::upper($acronym);

        if (Str::length($acronym) === 0)
            $acronym = "NN";

        $acronym = Str::ascii($acronym);
        return $acronym;
    }
}

