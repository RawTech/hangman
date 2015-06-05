<?php namespace AppBundle\Model\Api;

use AppBundle\Model\Api\Enumeration\Words;
use Symfony\Component\HttpFoundation\Session\Session;

/** A gamestate. */
class GameState
{
    /** @var Session */
    private $session;

    /** @param Session $session */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /** Resets the game state. */
    public function reset()
    {
        $this->session->remove('word');
        $this->session->remove('guesses');
        $this->session->remove('wrongGuesses');
        $this->session->remove('maxMistakes');
    }

    /**
     * @param string|null $word
     * @param integer     $maxMistakes
     */
    public function newGame($word = null, $maxMistakes = 6)
    {
        if ($word === null) {
            $word = Words::random();
        }

        $this->session->set('word', $word);
        $this->session->set('guesses', []);
        $this->session->set('wrongGuesses', []);
        $this->session->set('maxMistakes', $maxMistakes);
    }

    /** @return string */
    public function getWord()
    {
        return $this->session->get('word');
    }

    /** @return array */
    public function getGuesses()
    {
        return $this->session->get('guesses');
    }

    /** @return array */
    public function getWrongGuesses()
    {
        return $this->session->get('wrongGuesses');
    }

    /** @return integer */
    public function getMaxMistakes()
    {
        return $this->session->get('maxMistakes');
    }

    /**
     * @param string $letter
     *
     * @return array
     */
    public function addGuess($letter)
    {
        $guesses = $this->getGuesses();
        $guesses[] = $letter;

        $this->session->set('guesses', $guesses);

        if (!$this->wordContains($letter)) {
            $wrongGuesses = $this->getWrongGuesses();
            $wrongGuesses[] = $letter;

            $this->session->set('wrongGuesses', $wrongGuesses);
        }

        return $guesses;
    }

    /**
     * @param string $letter
     *
     * @return boolean
     */
    public function hasGuessed($letter)
    {
        $guesses = $this->getGuesses();

        return in_array($letter, $guesses);
    }

    /** @return array */
    public function getBoard()
    {
        $board = [];
        $word = $this->getWord();

        for ($i = 0; $i < strlen($word); $i++) {
            $char = substr($word, $i, 1);

            if ($this->hasGuessed($char)) {
                $board[] = $char;
            } else {
                $board[] = '_';
            }
        }

        return $board;
    }

    /**
     * @param string $letter
     *
     * @return boolean
     */
    public function wordContains($letter)
    {
        return strpos($this->getWord(), $letter) !== false;
    }
}
