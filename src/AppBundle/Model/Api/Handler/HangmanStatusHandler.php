<?php namespace AppBundle\Model\Api\Handler;

/** Hangman status handler. */
class HangmanStatusHandler extends AbstractHangmanHandler
{
    /** {@inheritdoc} */
    protected function getAdditionalResponseData()
    {
        return [];
    }
}
