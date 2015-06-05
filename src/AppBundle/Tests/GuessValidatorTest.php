<?php namespace AppBundle\Tests;

use AppBundle\Model\Api\GameState;
use AppBundle\Model\Api\Validators\GuessValidator;

/** Guess validator test */
class GuessValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var GuessValidator */
    protected $guessValidator;

    /**
     * Set up the test.
     *
     * @param GameState $gameState
     */
    public function setup(GameState $gameState = null)
    {
        if ($gameState === null) {
            $gameState = $this->getMockGameState();
        }

        $this->guessValidator = new GuessValidator($gameState);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|GameState
     */
    protected function getMockGameState()
    {
        return $this->getMockBuilder('AppBundle\Model\Api\GameState')
            ->disableOriginalConstructor()
            ->getMock();
    }

    // tests

    public function testInvalidCharacter()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Guess is invalid, (a-z) only'
        );
        $this->guessValidator->setLetter(1);
        $this->guessValidator->validate();
    }

    public function testInvalidCharacter1()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Guess is invalid, (a-z) only'
        );
        $this->guessValidator->setLetter(11);
        $this->guessValidator->validate();
    }

    public function testInvalidCharacter2()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Guess is invalid, (a-z) only'
        );
        $this->guessValidator->setLetter('aa');
        $this->guessValidator->validate();
    }

    public function testDuplicateGuess()
    {
        $gameState = $this->getMockGameState();
        $gameState->expects($this->once())->method('hasGuessed')->will($this->returnValue(true));
        $this->setup($gameState);

        $this->setExpectedException(
            'InvalidArgumentException',
            "You've already guessed a!"
        );

        $this->guessValidator->setLetter('a');
        $this->guessValidator->validate();
    }

    public function testOutOfGuesses()
    {
        $gameState = $this->getMockGameState();
        $gameState->expects($this->once())->method('hasGuessed')->will($this->returnValue(false));
        $gameState->expects($this->once())->method('getWrongGuesses')->will($this->returnValue([1,2,3,4]));
        $gameState->expects($this->once())->method('getMaxMistakes')->will($this->returnValue(4));
        $this->setup($gameState);

        $this->setExpectedException(
            'OverflowException',
            'No more guesses!'
        );

        $this->guessValidator->setLetter('a');
        $this->guessValidator->validate();
    }

    public function testCorrect()
    {
        $gameState = $this->getMockGameState();
        $gameState->expects($this->once())->method('hasGuessed')->will($this->returnValue(false));
        $gameState->expects($this->once())->method('getWrongGuesses')->will($this->returnValue([1,2,3,4]));
        $gameState->expects($this->once())->method('getMaxMistakes')->will($this->returnValue(5));
        $this->setup($gameState);

        $this->guessValidator->setLetter('a');
        $this->guessValidator->validate();
    }

    public function testPersist()
    {
        $gameState = $this->getMockGameState();
        $gameState->expects($this->once())->method('addGuess')->willReturnCallback(
            function($letter) {
                \PHPUnit_Framework_Assert::assertSame('a', $letter);
            }
        );
        $this->setup($gameState);

        $this->guessValidator->setLetter('a');
        $this->guessValidator->persistGuess();
    }
}
