<?php

declare(strict_types=1);

namespace Lingo\Word;

use Lingo\Letter\Correct;
use Lingo\Letter\Faulty;
use Lingo\Letter\WrongPlace;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FactoryTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Lingo\Word\Factory::init()
     */
    public function init(): void
    {
        $word = Factory::init('myword');

        self::assertSame('m.....', $word->__toString());
    }

    /**
     * @test
     *
     * @covers \Lingo\Word\Factory::match()
     *
     * @dataProvider getWords
     */
    public function match_matches(string $input, string $word, Word $expectedResult): void
    {
        $expected = new Word(new Correct('y'));

        $result = Factory::match($input, $word);

        self::assertEquals($expectedResult, $result);
    }

    public function getWords(): array
    {
        return [
            ['y', 'y', new Word(new Correct('y'))],
            ['y', 'x', new Word(new Faulty('y'))],
            ['xy', 'yx', new Word(new WrongPlace('x'), new WrongPlace('y'))],
            ['ritje', 'reten', new Word(new Correct('r'), new Faulty('i'), new Correct('t'), new Faulty('j'), new WrongPlace('e'))],
            ['zwaar', 'zagen', new Word(new Correct('z'), new Faulty('w'), new WrongPlace('a'), new Faulty('a'), new Faulty('r'))],
        ];
    }
}
