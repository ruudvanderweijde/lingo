<?php

declare(strict_types=1);

namespace Lingo\Console\Command;

use Lingo\Collection\Collection;
use Lingo\Collection\FiveLetterWords;
use Lingo\Collection\SixLetterWords;
use Lingo\Game;
use Lingo\Letter\Letter;
use Lingo\Printer\WordPrinter;
use Lingo\Word\Word;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class Lingo extends SymfonyCommand
{
    protected static $defaultName = 'lingo';
    private const FILE = '/tmp/input.log';

    protected function configure(): void
    {
        $this->setDescription('Starts a new lingo game.');
        $this->addOption('letters', 'l', InputOption::VALUE_OPTIONAL, 'Number of letters.', 5);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $letters = (int)$input->getOption('letters');
        $collection = $this->getCollection($letters);

        $play = true;

        while ($play) {
            $output->write(sprintf("\033\143"));

            $game = new Game($collection);
            $initialWord = $game->start();

            /** @var ConsoleSectionOutput $headerSection */
            $headerSection = $output->section();
            $headerSection->writeln('Welkom bij lingo!');
            $headerSection->writeln('');

            /** @var ConsoleSectionOutput $gameSection2 */
            $gameSection2 = $output->section();

            /** @var ConsoleSectionOutput $questionSection */
            $questionSection = $output->section();
            $questionSection->writeln('');
            $questionSection->writeln('Raad het woord:');

            /** @var QuestionHelper $questionHelper */
            $questionHelper = $this->getHelper('question');

            while (!$game->isFinished()) {
                $this->writeWord($gameSection2, $game->getKnownLetters());
                $userInput = $questionHelper->ask(
                    $input,
                    $output,
                    $this->createQuestion($initialWord->getFirstLetter(), $letters));

                $gameSection2->clear(2);
                $this->writeWord($gameSection2, $game->try($userInput));
            }

            $questionSection->clear(1);
            $gameSection2->writeln('');
            $gameSection2->writeln($game->isSuccess()
                ? 'Gewonnen!'
                : sprintf('Je hebt verloren! Het woord was: <fg=green>%s</>', strtoupper($game->getWord()))
            );
            $play = $questionHelper->ask($input, $output, new ConfirmationQuestion('Wil je nog een potje spelen? (y/n)'));
        }

        $output->write(sprintf("\033\143"));

        return 0;
    }

    protected function writeWord(OutputInterface $output, Word $word): void
    {
        $output->writeln(' ' . implode(' ', $this->generateOutput($word)));
    }

    private function generateOutput(Word $word): array
    {
        return WordPrinter::transform($word);
    }

    protected function createQuestion(Letter $firstLetter, int $letters): Question
    {
        $question = new Question('', false);

        $question->setNormalizer(function ($value) {
            return $value ? strtolower($value) : '';
        });

        $question->setValidator(function ($answer) use ($firstLetter, $letters) {
            $regex = sprintf('/%s[a-z]{%d}/', $firstLetter, $letters - 1);
            if (!is_string($answer) || !preg_match($regex, $answer)) {
                throw new \RuntimeException('Ongeldige invoer! Probeer opnieuw.');
            }
            file_put_contents(self::FILE, strtolower($answer) . PHP_EOL, FILE_APPEND);

            return strtolower($answer);
        });

        return $question;
    }

    private function getCollection(int $number): Collection
    {
        $supportedCollections = [
            5 => new FiveLetterWords(),
            6 => new SixLetterWords(),
        ];

        if (!array_key_exists($number, $supportedCollections)) {
            throw new \Exception(
                'Invalid number of letters. Supported: ' . implode(', ', array_keys($supportedCollections))
            );
        }

        return $supportedCollections[$number];
    }
}
