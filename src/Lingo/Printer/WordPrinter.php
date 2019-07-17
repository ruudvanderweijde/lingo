<?php

declare(strict_types=1);

namespace Lingo\Printer;

use Lingo\Letter\Letter;
use Lingo\Word\Word;

class WordPrinter
{
    public static function transform(Word $word): array
    {
        return
            array_map(
                function (Letter $letter) {
                    return LetterPrinter::print(($letter));
                },
                $word->getLetters()
            );
    }
}
