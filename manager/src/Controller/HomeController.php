<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/work", name="work")
     * @return Response
     */
    public function index(): Response
    {
        //return $this->render('app/home.html.twig');
        return $this->redirectToRoute('work.projects');
    }
}