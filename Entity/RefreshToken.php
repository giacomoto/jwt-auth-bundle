<?php

namespace Luckyseven\Bundle\LuckysevenJwtAuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;
use Luckyseven\Bundle\LuckysevenJwtAuthBundle\Repository\RefreshTokenRepository;

#[ORM\HasLifecycleCallbacks()]
#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
class RefreshToken extends AbstractRefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    protected $id;

    #[ORM\Column(type: 'string', length: 128, unique: true)]
    protected $refreshToken;

    #[ORM\Column(type: 'string', length: 255)]
    protected $username;

    #[ORM\Column(type: 'datetime')]
    protected $valid;

    #[ORM\Column(insertable: false, updatable: false, columnDefinition: 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP', generated: 'INSERT')]
    protected \DateTimeImmutable $createdAt;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
