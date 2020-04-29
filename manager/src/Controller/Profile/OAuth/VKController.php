<?php

declare(strict_types=1);

namespace App\Controller\Profile\OAuth;

use App\Model\User\UseCase\Network\Attach\Command;
use App\Model\User\UseCase\Network\Attach\Handler;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use App\Controller\ErrorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/oauth/vk")
 */
class VKController extends AbstractController
{
    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("/attach", name="profile.oauth.vk")
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function connect(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('vk_attach')
            ->redirect();
    }

    /**
     * @Route("/check", name="profile.oauth.vk.check")
     * @param ClientRegistry $clientRegistry
     * @param Handler $handler
     * @return Response
     */
    public function check(ClientRegistry $clientRegistry, Handler $handler): Response
    {
        $client = $clientRegistry->getClient('vk_attach');

        $command = new Command(
            $this->getUser()->getId(),
            'vk',
            (string) $client->fetchUser()->getId()
        );

        try {
            $handler->handle($command);
            $this->addFlash('success', 'VK is successfully attached.');
            return $this->redirectToRoute('profile');
        } catch (\DomainException $e) {
            //$this->logger->warning($e->getMessage(), ['exception' => $e]);
            $this->errors->handle($e);
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('profile');
        }
    }
}