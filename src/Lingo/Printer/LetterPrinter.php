<?php

declare(strict_types=1);

namespace Lingo\Printer;

use Lingo\Letter\Correct;
use Lingo\Letter\Faulty;
use Lingo\Letter\Letter;
use Lingo\Letter\Unknown;
use Lingo\Letter\WrongPlace;

class LetterPrinter
{
    public static function print(Letter $letter): string
    {
        return sprintf(self::getStyle($letter), mb_strtoupper($letter->__toString()));
    }

    private static function getStyle(Letter $letter)
    {
        $style = [
            Correct::class => '<fg=green;options=bold>%s</>',
            Faulty::class => '<fg=white>%s</>',
            Unknown::class => '<fg=white>%s</>',
            WrongPlace::class => '<fg=yellow;options=underscore>%s</>',
        ];

        return $style[get_class($letter)];
    }
}
