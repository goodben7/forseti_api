<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\AuthSession;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuthSessionRepository;
use App\Exception\UnavailableDataException;
use App\Message\Command\CommandBusInterface;
use App\Message\Command\CreateUserFromOtpCommand;
use App\Model\UserProxyIntertace;

class AuthService
{
    public function __construct(
        private EntityManagerInterface $em,
        private AuthSessionRepository $authSessionRepo,
        private UserRepository $userRepository,
        private CommandBusInterface $bus, 
    ) {
    }

    public function sendOtp(string $phone): void
    {
        $otp = (string) random_int(1000, 9999);
        $session = new AuthSession();
        $session->setPhone($phone);
        $session->setOtpCode($otp);
        $session->setCreatedAt(new \DateTimeImmutable());
        $session->setExpiresAt((new \DateTimeImmutable())->modify('+5 minutes'));
        $session->setIsValidated(false);

        $this->em->persist($session);
        $this->em->flush();
        
        // Dispatch event after OTP is sent
    }

    /**
     * @param string $phone 
     * @param string $code 
     * @return User|null 
     * @throws UnavailableDataException 
     * @throws \App\Exception\UserAuthenticationException 
     */
    public function verifyOtp(string $phone, string $code): ?User
    {
        try {

            $session = $this->authSessionRepo->findValidSession($phone, $code);
            if (!$session) {
                return null;
            }
            
            $session->setIsValidated(true);
            
            /** @var User */
            $user = $this->userRepository->findOneBy(['phone' => $phone]);
            
            if (!$user) {
                $user = $this->createUserFromOtp($phone, UserProxyIntertace::PERSON_ADMIN);
            } elseif ($user->isDeleted()) {
                $this->em->flush();
                throw new \App\Exception\UserAuthenticationException('This user is not active. Please contact support.');
            }
            
            $this->em->flush();
            return $user;
            
        } catch (UnavailableDataException | \App\Exception\UserAuthenticationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new UnavailableDataException('Error during OTP verification: ' . $e->getMessage());
        }
    }
    
    /**
     * @param string $phone 
     * @return User 
     * @throws UnavailableDataException 
     */
    private function createUserFromOtp(string $phone, string $personType): User
    {
        try {
            $command = new CreateUserFromOtpCommand($phone, $personType);
            return $this->bus->dispatch($command);
        } catch (\Exception $e) {
            throw new UnavailableDataException('Error creating new user: ' . $e->getMessage());
        }
    }
}
