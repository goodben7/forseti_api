<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Model\RessourceInterface;
use App\Repository\AuthSessionRepository;

#[ORM\Entity(repositoryClass: AuthSessionRepository::class)]
#[ORM\Table(name: 'auth_session', schema: 'admin')]
class AuthSession implements RessourceInterface
{
    public const string EVENT_OTP_SENT = "otp_sent";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'AS_ID')]
    private ?int $id = null;

    #[ORM\Column(length: 15, name: 'AS_PHONE')]
    private ?string $phone = null;

    #[ORM\Column(length: 4, name: 'AS_OTP_CODE')]
    private ?string $otpCode = null;

    #[ORM\Column(name: 'AS_CREATED_AT')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'AS_EXPIRES_AT')]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\Column(name: 'AS_IS_VALIDATED')]
    private ?bool $isValidated = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getOtpCode(): ?string
    {
        return $this->otpCode;
    }

    public function setOtpCode(string $otpCode): static
    {
        $this->otpCode = $otpCode;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): static
    {
        $this->isValidated = $isValidated;

        return $this;
    }
}
