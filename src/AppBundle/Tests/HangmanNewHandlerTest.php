<?php namespace AppBundle\Tests;

use AppBundle\Model\Api\GameState;
use AppBundle\Model\Api\Handler\HangmanNewHandler;

/** New handler test */
class HangmanNewHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var HangmanNewHandler */
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

        $this->handler = new HangmanNewHandler($gameState);
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

    public function testNewGame()
    {
        $gameState = $this->getMockGameState();
        $gameState->expects($this->once())->method('reset');
        $gameState->expects($this->once())->method('newGame');
        $this->setup($gameState);

        $handler = $this->handler->createNewGame();

        $this->assertSame($this->handler, $handler);
    }

    public function testResponse()
    {
        $gameState = $this->getMockGameState();
        $gameState->expects($this->once())->method('getGuesses')->will($this->returnValue(['a','b']));
        $gameState->expects($this->once())->method('getWrongGuesses')->will($this->returnValue([]));
        $gameState->expects($this->once())->method('getMaxMistakes')->will($this->returnValue(5));
        $gameState->expects($this->once())->method('getBoard')->will($this->returnValue(['a','_', '_']));
        $this->setup($gameState);

        $response = $this->handler->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"code":200,"guesses":["a","b"],"wrongGuesses":[],"maxMistakes":5,"board":["a","_","_"],"message":"Game has been reset and a new one created."}', $response->getContent());
    }
}
