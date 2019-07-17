<?php

declare(strict_types=1);

namespace Lingo\Letter;

class Unknown implements Letter
{
    public function __toString(): string
    {
        return '.';
    }

    public function getColor(): string
    {
        return 'white';
    }
}
