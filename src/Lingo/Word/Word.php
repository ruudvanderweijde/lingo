<?php

declare(strict_types=1);

namespace Lingo\Word;

use Lingo\Letter\Letter;

class Word
{
    /**
     * @var Letter[]
     */
    private $letters;

    public function __construct(Letter ...$letters)
    {
        $this->letters = $letters;
    }

    public function getFirstLetter(): Letter
    {
        return $this->letters[0];
    }

    /**
     * @return Letter[]
     */
    public function getLetters(): array
    {
        return $this->letters;
    }

    public function __toString(): string
    {
        return join('', array_map(function (Letter $letter) {
            return $letter->__toString();
        }, $this->letters));
    }
}
