<?php

declare(strict_types=1);

namespace Lingo\Letter;

class WrongPlace implements Letter
{
    private $input;

    public function __construct(string $input)
    {
        $this->input = $input;
    }

    public function __toString(): string
    {
        return $this->input;
    }

    public function getColor(): string
    {
        return 'yellow';
    }
}
