<?php

namespace Luckyseven\Bundle\LuckysevenJwtAuthBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Gesdinet\JWTRefreshTokenBundle\EventListener\AttachRefreshTokenOnSuccessListener;

// https://github.com/markitosgv/JWTRefreshTokenBundle/issues/49
class JwtAuthService
{
    protected RefreshTokenManager $refreshTokenManager;
    protected ContainerBagInterface $params;
    protected JWTTokenManagerInterface $JWTManager;
    protected RefreshTokenGeneratorInterface $refreshTokenGenerator;
    protected AttachRefreshTokenOnSuccessListener $attachRefreshTokenOnSuccessListener;

    function __construct(
        RefreshTokenManager                 $refreshTokenManager,
        ContainerBagInterface               $params,
        JWTTokenManagerInterface            $JWTManager,
        RefreshTokenGeneratorInterface      $refreshTokenGenerator,
        AttachRefreshTokenOnSuccessListener $attachRefreshTokenOnSuccessListener,
    )
    {
        $this->params = $params;
        $this->JWTManager = $JWTManager;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->refreshTokenGenerator = $refreshTokenGenerator;
        $this->attachRefreshTokenOnSuccessListener = $attachRefreshTokenOnSuccessListener;
    }

    public function create(UserInterface $user): array
    {
        $refreshToken = $this->getRefreshTokenForUser($user);

        $refreshTokenName = $this->params->get('gesdinet_jwt_refresh_token.token_parameter_name');

        return [
            "accessToken" => $this->JWTManager->create($user),
            $refreshTokenName => $refreshToken,
        ];
    }

    /**
     * @param UserInterface $user
     * @return string
     */
    protected function getRefreshTokenForUser(UserInterface $user): string
    {
        $response = new Response();
        $jwtSuccessEvent = new AuthenticationSuccessEvent(array(), $user, $response);

        $this->attachRefreshTokenOnSuccessListener->attachRefreshToken($jwtSuccessEvent);
        $refreshToken = $this->refreshTokenManager->getLastFromUsername($user->getUserIdentifier());

        return $refreshToken->getRefreshToken();
    }
}
