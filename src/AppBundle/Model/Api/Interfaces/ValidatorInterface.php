<?php namespace AppBundle\Model\Api\Interfaces;

use Exception;

/** A class whom's contents can be validated. */
interface ValidatorInterface
{
    /**
     * Validates the content of the class.
     *
     * @throws Exception if something could not be validated properly.
     */
    public function validate();
}