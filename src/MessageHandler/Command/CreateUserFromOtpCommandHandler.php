<?php

namespace App\MessageHandler\Command;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Manager\UserManager;
use App\Repository\ProfileRepository;
use App\Message\Command\CommandHandlerInterface;
use App\Message\Command\CreateUserFromOtpCommand;
use App\Exception\UnavailableDataException;

class CreateUserFromOtpCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserManager $userManager,
        private ProfileRepository $profileRepository,
        private ?LoggerInterface $logger = null
    ) {
    }

    /**
     * @param CreateUserFromOtpCommand $command
     * @return User
     * @throws UnavailableDataException
     */
    public function __invoke(CreateUserFromOtpCommand $command): User
    {
        try {
            
            $profile = $this->profileRepository->findOneBy(['personType' => $command->personType]);
                
            if (null === $profile) {
                throw new UnavailableDataException('Invalid profile: Person type mismatch between user and profile');
            }
            
            return $this->userManager->createUserFromOtp($command->phone, $profile);

        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error('Error creating user from OTP: ' . $e->getMessage());
            }
            throw new UnavailableDataException('Error creating new user: ' . $e->getMessage());
        }
    }
}