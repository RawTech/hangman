<?php namespace AppBundle\Controller;

use AppBundle\Model\Qwerty;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/** Hangman controller. */
class HangmanController extends Controller
{
    /**
     * @Route("/", name="hangman")
     * @Method({"GET"})
     */
    public function hangman()
    {
        return $this->render('index.html.twig', ['keys' => Qwerty::getKeys(), 'showNewGame' => $this->showNewGame()]);
    }

    /** @return boolean */
    private function showNewGame()
    {
        return empty($this->get('rawtech.hangman.game_state')->getBoard());
    }
}
