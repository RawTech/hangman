<?php namespace AppBundle\Model\Api\Handler;

/** Hangman new handler. */
class HangmanNewHandler extends AbstractHangmanHandler
{
    /**
     * Create a new game.
     *
     * @return $this
     */
    public function createNewGame()
    {
        $this->state->reset();
        $this->state->newGame();

        return $this;
    }

    /** {@inheritdoc} */
    protected function getAdditionalResponseData()
    {
        return ['message' => 'Game has been reset and a new one created.'];
    }
}
