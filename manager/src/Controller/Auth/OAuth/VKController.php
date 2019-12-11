<?php


namespace App\Controller\Auth\OAuth;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VKController extends AbstractController
{
    /**
     * @Route("/oauth/vk", name="oauth.vk")
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connect(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('vk_main')
            ->redirect();
    }
    /**
     * @Route("/oauth/facebook/check", name="oauth.vk.check")
     * @return Response
     */
    public function check(): Response
    {
        return $this->redirectToRoute('home');
    }
}