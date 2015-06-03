<?php namespace AppBundle\Tests;

use AppBundle\Model\Api\GameState;
use AppBundle\Model\Api\Handler\HangmanGuessHandler;
use AppBundle\Model\Api\Validators\GuessValidator;

/** Guess handler test */
class HangmanGuessHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var HangmanGuessHandler */
    protected $handler;

    /**
     * Set up the test.
     *
     * @param GameState      $gameState
     * @param GuessValidator $guessValidator
     */
    public function setup(GameState $gameState = null, GuessValidator $guessValidator = null)
    {
        if ($gameState === null) {
            $gameState = $this->getMockGameState();
        }
        if ($guessValidator === null) {
            $guessValidator = $this->getMockGuessValidator();
        }

        $this->handler = new HangmanGuessHandler($gameState, $guessValidator);
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|GuessValidator
     */
    protected function getMockGuessValidator()
    {
        return $this->getMockBuilder('AppBundle\Model\Api\Validators\GuessValidator')
            ->disableOriginalConstructor()
            ->getMock();
    }

    // tests

    public function testValidGuess()
    {
        $validator = $this->getMockGuessValidator();
        $validator->expects($this->once())->method('setLetter');
        $validator->expects($this->once())->method('validate');
        $validator->expects($this->once())->method('persistGuess');

        $gameState = $this->getMockGameState();
        $gameState->expects($this->once())->method('getGuesses')->will($this->returnValue(['a','b']));
        $gameState->expects($this->once())->method('getMaxGuesses')->will($this->returnValue(5));
        $gameState->expects($this->once())->method('getBoard')->will($this->returnValue(['a','_', '_']));

        $this->setup($gameState, $validator);

        $handler = $this->handler->guess('a');

        $this->assertSame($this->handler, $handler);

        $response = $this->handler->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"code":200,"guesses":["a","b"],"maxGuesses":5,"board":["a","_","_"],"message":"You guessed a"}', $response->getContent());
    }

    public function testInvalidArgument()
    {
        $validator = $this->getMockGuessValidator();
        $validator->expects($this->once())->method('setLetter');
        $validator->expects($this->once())->method('validate')->willThrowException(new \InvalidArgumentException('Argument not valid.'));

        $gameState = $this->getMockGameState();
        $gameState->expects($this->once())->method('getGuesses')->will($this->returnValue(['a','b']));
        $gameState->expects($this->once())->method('getMaxGuesses')->will($this->returnValue(5));
        $gameState->expects($this->once())->method('getBoard')->will($this->returnValue(['a','_', '_']));

        $this->setup($gameState, $validator);

        $handler = $this->handler->guess('a');

        $this->assertSame($this->handler, $handler);

        $response = $this->handler->getResponse();

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('{"code":400,"guesses":["a","b"],"maxGuesses":5,"board":["a","_","_"],"message":"Argument not valid."}', $response->getContent());
    }

    public function testTooManyGuesses()
    {
        $validator = $this->getMockGuessValidator();
        $validator->expects($this->once())->method('setLetter');
        $validator->expects($this->once())->method('validate')->willThrowException(new \OverflowException('Too many guesses.'));

        $gameState = $this->getMockGameState();
        $gameState->expects($this->once())->method('getGuesses')->will($this->returnValue(['a','b']));
        $gameState->expects($this->once())->method('getMaxGuesses')->will($this->returnValue(5));
        $gameState->expects($this->once())->method('getBoard')->will($this->returnValue(['a','_', '_']));

        $this->setup($gameState, $validator);

        $handler = $this->handler->guess('a');

        $this->assertSame($this->handler, $handler);

        $response = $this->handler->getResponse();

        $this->assertSame(413, $response->getStatusCode());
        $this->assertSame('{"code":413,"guesses":["a","b"],"maxGuesses":5,"board":["a","_","_"],"message":"Too many guesses."}', $response->getContent());
    }
}
