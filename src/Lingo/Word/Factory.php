<?php

declare(strict_types=1);

namespace Lingo\Word;

use Lingo\Letter\Correct;
use Lingo\Letter\Faulty;
use Lingo\Letter\Unknown;
use Lingo\Letter\WrongPlace;

class Factory
{
    public static function init(string $word): Word
    {
        $numberOfUnknowns = strlen($word) - 1;

        return new Word(new Correct($word[0]), ...array_fill(0, $numberOfUnknowns, new Unknown()));
    }

    public static function match(string $input, string $word): Word
    {
        $inputArray = str_split($input);
        $wordArray = str_split($word);
        $resultArray = [];

        $perfectMatches = array_intersect_assoc($wordArray, $inputArray);
        foreach ($perfectMatches as $key => $letter) {
            $resultArray[$key] = new Correct($letter);
        }

        $notPerfectInWord = array_diff_assoc($wordArray, $inputArray);
        $notPerfectInInput = array_diff_assoc($inputArray, $wordArray);
        foreach ($notPerfectInInput as $key => $letter) {
            if (in_array($letter, $notPerfectInWord)) {
                $numberOfTotalWrongPlace = array_count_values($notPerfectInWord)[$letter];
                $numberOfCurrentWrongPlace = array_reduce($resultArray, function ($carry, $resultLetter) use ($letter) {
                    $count = $resultLetter instanceof WrongPlace && $resultLetter->__toString() === $letter ? 1 : 0;

                    return $carry + $count;
                }, 0);
                if ($numberOfCurrentWrongPlace < $numberOfTotalWrongPlace) {
                    $resultArray[$key] = new WrongPlace($letter);
                }
            }
        }

        $allFaulty = array_map(function (string $letter) { return new Faulty($letter); }, $inputArray);

        $resultArray = ($resultArray + $allFaulty);

        ksort($resultArray);

        return new Word(...($resultArray + $allFaulty));
    }
}
