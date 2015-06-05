<?php namespace AppBundle\Tests;

use AppBundle\Model\Api\GameState;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/** Game state test */
class GameStateTest extends \PHPUnit_Framework_TestCase
{
    /** @var GameState */
    protected $gameState;

    /** Set up the test. */
    public function setup()
    {
        $this->gameState = new GameState($this->getMockSession());
    }

    /**
     * Recommended way to mock a Symfony session as per:
     * @link http://symfony.com/doc/current/components/http_foundation/session_testing.html
     *
     * @return Session
     */
    protected function getMockSession()
    {
        return new Session(new MockArraySessionStorage());
    }

    // tests

    public function testEmptyBoardGeneration()
    {
        $this->assertEmpty($this->gameState->getBoard());

        $this->gameState->newGame('aword');

        $this->assertSame(
            ['_', '_', '_', '_', '_'],
            $this->gameState->getBoard()
        );
    }

    public function testPartiallyFilledBoardGeneration()
    {
        $this->assertEmpty($this->gameState->getBoard());

        $this->gameState->newGame('aword');

        $this->gameState->addGuess('a');

        $this->assertSame(
            ['a', '_', '_', '_', '_'],
            $this->gameState->getBoard()
        );
    }

    public function testFullyFilledBoardGeneration()
    {
        $this->assertEmpty($this->gameState->getBoard());

        $this->gameState->newGame('aword');

        $this->gameState->addGuess('a');
        $this->gameState->addGuess('w');
        $this->gameState->addGuess('o');
        $this->gameState->addGuess('r');
        $this->gameState->addGuess('d');

        $this->assertSame(
            ['a', 'w', 'o', 'r', 'd'],
            $this->gameState->getBoard()
        );
    }

    public function testGuessRegistration()
    {
        $this->assertEmpty($this->gameState->getGuesses());

        $this->gameState->newGame('aword');

        $this->gameState->addGuess('a');

        $this->assertSame(
            ['a'],
            $this->gameState->getGuesses()
        );
    }

    public function testGuessRegistration1()
    {
        $this->gameState->newGame('aword');

        $this->assertFalse($this->gameState->hasGuessed('a'));

        $this->gameState->addGuess('a');

        $this->assertTrue($this->gameState->hasGuessed('a'));
    }

    public function testMaxGuesses()
    {
        $this->gameState->newGame('aword', 20);

        $this->assertSame(20, $this->gameState->getMaxMistakes());
    }

    public function testWord()
    {
        $this->gameState->newGame('aword', 20);

        $this->assertSame('aword', $this->gameState->getWord());
    }

    public function testWordContains()
    {
        $this->gameState->newGame('aword', 20);

        $this->assertTrue($this->gameState->wordContains('a'));
        $this->assertFalse($this->gameState->wordContains('z'));
    }

    public function testWrongGuess()
    {
        $this->gameState->newGame('aword', 20);
        $this->gameState->addGuess('b');
        $this->gameState->addGuess('a');

        $this->assertSame(['b'], $this->gameState->getWrongGuesses());
        $this->assertSame(['b', 'a'], $this->gameState->getGuesses());
    }
}
