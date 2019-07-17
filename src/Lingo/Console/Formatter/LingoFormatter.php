<?php

declare(strict_types=1);

namespace Lingo\Console\Formatter;

use Lingo\Letter;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

final class LingoFormatter extends OutputFormatter
{
    public function __construct()
    {
        $decorated = false;
        $extraStyles = [
            Letter\Correct::class => new OutputFormatterStyle('green', null, ['bold']),
            Letter\Faulty::class => new OutputFormatterStyle(),
            Letter\Unknown::class => new OutputFormatterStyle(),
            Letter\WrongPlace::class => new OutputFormatterStyle('yellow', null, ['underscore']),
        ];

        parent::__construct($decorated, $extraStyles);
    }
}
