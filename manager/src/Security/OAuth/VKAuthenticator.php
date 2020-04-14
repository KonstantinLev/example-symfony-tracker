<?php

namespace App\Security\OAuth;

use App\Model\User\UseCase\Network\Auth\Command;
use App\Model\User\UseCase\Network\Auth\Handler;
use KnpU\OAuth2ClientBundle\Client\Provider\VKontakteClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class VKAuthenticator extends SocialAuthenticator
{
    private $urlGenerator;
    private $clients;
    private $handler;
    public function __construct(UrlGeneratorInterface $urlGenerator, ClientRegistry $clients, Handler $handler)
    {
        $this->urlGenerator = $urlGenerator;
        $this->clients = $clients;
        $this->handler = $handler;
    }
    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'oauth.vk.check';
    }
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getVKClient());
    }
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $vkUser = $this->getVKClient()->fetchUserFromToken($credentials);
        $network = 'vk';
        $id = $vkUser->getId();
        $username = $network . ':' . $id;

        $command = new Command($network, $id);
        $command->firstName = $vkUser->getFirstName();
        $command->lastName = $vkUser->getLastName();

        try {
            return $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            $this->handler->handle($command);
            return $userProvider->loadUserByUsername($username);
        }
    }

    /**
     * @return VKontakteClient
     */
    private function getVKClient(): VKontakteClient
    {
        return $this->clients->getClient('vk_main');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}