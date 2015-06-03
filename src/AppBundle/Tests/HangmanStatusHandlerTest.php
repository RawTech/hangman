<?php namespace AppBundle\Tests;

use AppBundle\Model\Api\GameState;
use AppBundle\Model\Api\Handler\HangmanStatusHandler;

/** Status handler test */
class HangmanStatusHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var HangmanStatusHandler */
    protected $handler;

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

        $this->handler = new HangmanStatusHandler($gameState);
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

    public function testResponse()
    {
        $gameState = $this->getMockGameState();
        $gameState->expects($this->once())->method('getGuesses')->will($this->returnValue(['a','b']));
        $gameState->expects($this->once())->method('getMaxGuesses')->will($this->returnValue(5));
        $gameState->expects($this->once())->method('getBoard')->will($this->returnValue(['a','_', '_']));
        $this->setup($gameState);

        $response = $this->handler->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"code":200,"guesses":["a","b"],"maxGuesses":5,"board":["a","_","_"]}', $response->getContent());
    }
}
