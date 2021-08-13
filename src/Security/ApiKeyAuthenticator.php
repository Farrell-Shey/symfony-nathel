<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ApiKeyAuthenticator
 * @package App\Security
 * Authenticator via les données API OSU (prend uniquement en entrée le $user object transportée via la session
 */
class ApiKeyAuthenticator extends AbstractAuthenticator
{
    private $entityManager;
    private $urlGenerator;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->entityManager =  $entityManager;
        $this->urlGenerator = $urlGenerator;
    }


    public function supports(Request $request): ?bool
    {

        return $request->attributes->get('_route') === 'app_login'
            && $request->isMethod('GET');
    }

    public function authenticate(Request $request): \Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface
    {


        //$user = ConnexionController::login();
        $user = $_SESSION['user'];
        unset($_SESSION['user']);


        if (!$user) {
            throw new UserNotFoundException();
        }

        $user_badge = new UserBadge($user->getOsuId(),
            function ($userIdentifier) {
            return $this->entityManager->getRepository(User::class)->findOneBy(['osu_id' => $userIdentifier]);
        });

        $passport = new SelfValidatingPassport($user_badge);


        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, string $firewallName): ?\Symfony\Component\HttpFoundation\Response
    {
        //TODO: A coder le conditionnel des routes

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $exception): ?\Symfony\Component\HttpFoundation\Response
    {
        dd('Authentification failed ! Please contact an administrator WolfaH#4645 ');
    }

}