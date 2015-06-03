<?php namespace AppBundle\Model\Api\Validators;

use AppBundle\Model\Api\GameState;
use AppBundle\Model\Api\Interfaces\ValidatorInterface;

/** Supplies validation for guessing. */
class GuessValidator implements ValidatorInterface
{
    /** @var GameState */
    private $state;

    /** @var string */
    private $letter;

    /** @param GameState $state */
    public function __construct(GameState $state)
    {
        $this->state = $state;
    }

    /** @param string $letter */
    public function setLetter($letter)
    {
        $this->letter = $letter;
    }

    /** {@inheritdoc} */
    public function validate()
    {
        if (preg_match("/^[a-z]{1}$/", $this->letter) !== 1) {
            throw new \InvalidArgumentException('Guess is invalid, (a-z) only!');
        } elseif ($this->state->hasGuessed($this->letter)) {
            throw new \InvalidArgumentException(sprintf("You've already guessed %s!", $this->letter));
        } elseif (count($this->state->getGuesses()) + 1 > $this->state->getMaxGuesses()) {
            throw new \OverflowException('No more guesses!');
        }
    }

    /** Persists the guess in the game state. */
    public function persistGuess()
    {
        $this->state->addGuess($this->letter);
    }
}
