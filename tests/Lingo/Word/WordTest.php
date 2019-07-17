<?php

declare(strict_types=1);

namespace Lingo\Word;

use Lingo\Letter\Correct;
use Lingo\Letter\Faulty;
use Lingo\Letter\Unknown;
use Lingo\Letter\WrongPlace;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class WordTest extends TestCase
{
    /**
     * @test
     */
    public function to_string(): void
    {
        $word = new Word(new Correct('a'), new Correct('b'), new WrongPlace('c'), new Faulty('d'), new Unknown());

        self::assertSame('abcd.', $word->__toString());
    }
}
