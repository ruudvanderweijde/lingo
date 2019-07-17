<?php

declare(strict_types=1);

namespace Lingo;

use Lingo\Collection\Collection;
use Lingo\Collection\FiveLetterWords;
use Lingo\Letter\Correct;
use Lingo\Letter\Letter;
use Lingo\Word\Factory;
use Lingo\Word\Word;

class Game
{
    const MAX_TRIES = 5;

    /** @var string[] */
    private $words = [];
    /** @var string */
    private $word = '';
    /** @var int */
    private $tries = 0;
    /** @var Word */
    private $result;
    /** @var Letter[] */
    private $knownLetters = [];

    /**
     * @var Collection
     */
    private $collection;

    public function __construct(Collection $collection = null)
    {
        $this->collection = $collection ?? new FiveLetterWords();
    }

    public function start(): Word
    {
        $this->word = $this->collection->pickOne();

        $word = Factory::init($this->word);

        $this->knownLetters = $word->getLetters();

        return $word;
    }

    public function try(string $input): Word
    {
        $this->result = Factory::match($input, $this->word);
        ++$this->tries;

        $this->knownLetters =
            array_replace($this->knownLetters, array_filter(
                $this->result->getLetters(),
                function (Letter $letter) { return $letter instanceof Correct; }
            ));

        return $this->result;
    }

    public function isFinished(): bool
    {
        return $this->tries >= self::MAX_TRIES || $this->isSuccess();
    }

    public function isSuccess(): bool
    {
        return $this->result && $this->result->__toString() === $this->word;
    }

    public function getWord(): string
    {
        if (!$this->isFinished()) {
            throw new \LogicException('Je mag het woord pas zien als het spel is afgelopen!');
        }

        return $this->word;
    }

    public function getKnownLetters(): Word
    {
        return new Word(...$this->knownLetters);
    }
}
