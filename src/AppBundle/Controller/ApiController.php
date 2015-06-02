<?php namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Api controller.
 */
class ApiController extends Controller
{
    /**
     * @Route("/status", name="status")
     * @Method({"GET"})
     */
    public function status()
    {
        return new JsonResponse(['code' => 200]);
    }
}
