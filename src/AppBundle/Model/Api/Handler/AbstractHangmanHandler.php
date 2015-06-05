<?php namespace AppBundle\Model\Api\Handler;

use AppBundle\Model\Api\GameState;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/** Abstract hangman handler. */
abstract class AbstractHangmanHandler
{
    /** @var GameState */
    protected $state;

    /** @var integer */
    protected $statusCode = Response::HTTP_OK;

    /** @param GameState $state */
    public function __construct(GameState $state)
    {
        $this->state = $state;
    }

    /** @return JsonResponse */
    public function getResponse()
    {
        $response = array_merge(
            [
                'code' => $this->statusCode,
                'guesses' => $this->state->getGuesses(),
                'wrongGuesses' => $this->state->getWrongGuesses(),
                'maxMistakes' => $this->state->getMaxMistakes(),
                'board' => $this->state->getBoard(),
            ],
            $this->getAdditionalResponseData()
        );

        return new JsonResponse($response);
    }

    /**
     * Gets additional response data.
     *
     * @return array
     */
    abstract protected function getAdditionalResponseData();
}
