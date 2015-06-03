<?php namespace AppBundle\Model\Api\Handler;

use AppBundle\Model\Api\Validators\GuessValidator;
use AppBundle\Model\Api\GameState;
use Symfony\Component\HttpFoundation\Response;

/** Hangman guess handler. */
class HangmanGuessHandler extends AbstractHangmanHandler
{
    /** @var GuessValidator */
    protected $validator;

    /** @var string */
    protected $message;

    /**
     * @param GameState      $state
     * @param GuessValidator $validator
     */
    public function __construct(GameState $state, GuessValidator $validator)
    {
        parent::__construct($state);

        $this->validator = $validator;
    }

    /**
     * @param string $letter
     *
     * @return $this
     */
    public function guess($letter)
    {
        $this->validator->setLetter($letter);

        try {
            $this->validator->validate();

            $this->message = sprintf('You guessed %s', $letter);
            $this->validator->persistGuess();
        } catch (\InvalidArgumentException $e) {
            $this->statusCode = Response::HTTP_BAD_REQUEST;
            $this->message = $e->getMessage();
        } catch (\OverflowException $e) {
            $this->statusCode = Response::HTTP_REQUEST_ENTITY_TOO_LARGE;
            $this->message = $e->getMessage();
        }

        return $this;
    }

    /** {@inheritdoc} */
    protected function getAdditionalResponseData()
    {
        return ['message' => $this->message];
    }
}
