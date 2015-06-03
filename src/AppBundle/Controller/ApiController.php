<?php namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Api controller.
 */
class ApiController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function root()
    {
        return $this->redirectToRoute('apiStatus');
    }

    /**
     * @Route("/status", name="apiStatus")
     * @Method({"GET"})
     */
    public function status()
    {
        return $this->get('rawtech.hangman.handler.status')->getResponse();
    }

    /**
     * @Route("/new", name="apiNew")
     * @Method({"GET"})
     */
    public function newGame()
    {
        return $this->get('rawtech.hangman.handler.new')->createNewGame()->getResponse();
    }

    /**
     * @Route("/guess/{letter}", name="apiGuess")
     * @Method({"GET"})
     */
    public function guess($letter)
    {
        return $this->get('rawtech.hangman.handler.guess')->guess($letter)->getResponse();
    }
}
