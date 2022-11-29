<?php

namespace Luckyseven\Bundle\LuckysevenJwtAuthBundle\Repository;

use Luckyseven\Bundle\LuckysevenJwtAuthBundle\Entity\RefreshToken;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenRepositoryInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository as BaseRefreshTokenRepository;

/**
 * @extends ServiceEntityRepository<RefreshToken>
 * @implements RefreshTokenRepositoryInterface<RefreshToken>
 */
class RefreshTokenRepository extends BaseRefreshTokenRepository
{
}
