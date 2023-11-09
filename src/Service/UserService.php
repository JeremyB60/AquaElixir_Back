<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Service\PasswordGenerator;
use App\DTO\Request\LoginUserDTO;
use App\DTO\Request\RegisterUserDTO;
use App\DTO\Request\ResetPasswordDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Exception\EmailConfirmationException;
use App\Exception\UserResetPasswordException;
use App\Exception\EmailSendException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserService
{
    private $passwordHasher;
    private $entityManager;
    private $mailer;
    private $urlGenerator;
    private $logger;
    private $passwordGenerator;


    public function __construct(UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $entityManager,
    MailerInterface $mailer, UrlGeneratorInterface $urlGenerator, LoggerInterface $logger,
     PasswordGenerator $passwordGenerator)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
        $this->logger = $logger;
        $this->passwordGenerator = $passwordGenerator;
    }

//....................................REGISTER...................................................

    //Gestion d'inscription
    public function registerUser(RegisterUserDTO $registerUserDTO, ValidatorInterface $validator): ServiceResult
    {
        try {
            $this->logger->info('Received DTO data:', ['data' => [
                'firstName' => $registerUserDTO->firstName,
                'lastName' => $registerUserDTO->lastName,
                'email' => $registerUserDTO->email,
                'password' => $registerUserDTO->password,
            ]]);
    
            // Validation the DTO
        $validator->validate($registerUserDTO, new Assert\Collection([
            'firstName' => new Assert\NotBlank(),
            'lastName' => new Assert\NotBlank(),
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
            'password' => new Assert\Length(['min' => 8]),
        ]));
    
        // On continue avec la logique d'inscription
        $user = new User();
        $user->setFirstName($registerUserDTO->firstName);
        $user->setLastName($registerUserDTO->lastName);
        $user->setEmail($registerUserDTO->email);
        $user->setRoles(['ROLE_USER']);

        // Hasher et définir le mot de passe
        $password = $this->passwordHasher->hashPassword($user, $registerUserDTO->password);
        $user->setPassword($password);

        // On défini le jeton de confirmation
        $confirmationToken = bin2hex(random_bytes(32));
        $user->setConfirmationToken($confirmationToken);

        // Persister l'utilisateur dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Envoi d'e-mail de confirmation
        $this->sendConfirmationEmail($user, $this->mailer, $this->urlGenerator, $this->logger);

        return ServiceResult::createSuccess();
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'inscription: ' . $e->getMessage());
            // Renvoie une réponse d'erreur ou lève une exception si nécessaire
            return ServiceResult::createError('Échec de l\'enregistrement', 500);
        }
    }


    public function sendConfirmationEmail(User $user, MailerInterface $mailer,
     UrlGeneratorInterface $urlGenerator, LoggerInterface $logger): ServiceResult
    {
        $this->logger->info('Tentative d\'envoi d\'un e-mail de confirmation à l\'utilisateur: ' . $user->getEmail());

        // On s'assure que l'utilisateur dispose d'un jeton de confirmation
        $confirmationToken = $user->getConfirmationToken();

        if (!$confirmationToken) {
            throw new EmailConfirmationException('L\'utilisateur n\'a pas de jeton de confirmation.', 500);
        }

        // Génération de l'URL de confirmation à l'aide du jeton existant
        $confirmationUrl = $urlGenerator->generate('confirm_email',
        ['token' => $confirmationToken], UrlGeneratorInterface::ABSOLUTE_URL);

        // Envoi d'e-mail de confirmation
        $email = (new Email())
            ->from('aquaelixir@example.com')
            ->to($user->getEmail())
            ->subject('Confirmation d\'e-mail')
            ->html("Cliquez sur le lien suivant pour confirmer votre e-mail: <a href=
            '{$confirmationUrl}'>Confirmer votre e-mail</a>");
            
        try {
            $mailer->send($email);
        } catch (\Exception $e) {
            $logger->error('Failed to send the confirmation email.', ['exception' => $e]);

            // On passe l'exception comme troisième paramètre
            throw new EmailConfirmationException('Échec de l\'envoi de l\'e-mail de confirmation', 0, $e);
        }

        return ServiceResult::createSuccess('E-mail de confirmation envoyé.');
    }


    public function confirmEmail(string $token, LoggerInterface $logger): ServiceResult
    {
        // Vérifiez if a user has this email confirmation token
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if (!$user) {
            // Si aucun utilisateur ne possède ce jeton, renvoie une erreur ou une exception.
            // On peux utiliser une exception personnalisée ici.
            $logger->error('Email confirmation failed. Token not found.');
            throw new EmailConfirmationException('Email confirmation failed. Token not found.');
        }

        // On coche l'e-mail comme confirmé et on supprime le jeton
        // On active le compte client
            $user->setIsEmailConfirmed(true);
            $user->setConfirmationToken(null);
            $user->setAccountStatus('active');

            // Mise à jour d'utilisateur dans la base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();

        return ServiceResult::createSuccess('E-mail confirmé.');
    }




//....................................LOGIN...................................................

    // Gestion de Login
    public function loginUser(LoginUserDTO $loginUserDTO): ServiceResult
    {
        // Charger l'utilisateur par email
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $loginUserDTO->email]);

        if (!$user) {
            return ServiceResult::createError('User not found', 404);
        }

        // Pour vérifiez si le mot de passe est valide
        if (!$this->passwordHasher->isPasswordValid($user, $loginUserDTO->password)) {
            return ServiceResult::createError('Invalid credentials', 401);
        }

        // Implémentation de la logique d'authentification ici.

        return ServiceResult::createSuccess();
    }

//....................................RESET PASSEWORD...................................................

    // Gestion d'envoi de reinitialisation de mot de passe
    public function resetPassword(ResetPasswordDTO $resetPasswordDTO,
     MailerInterface $mailer, UrlGeneratorInterface $urlGenerator): ServiceResult
    {
        $email = $resetPasswordDTO->email;

        $this->logger->info('Attempting to reset password for email: ' . $email);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $this->logger->error('User with email ' . $email . ' not found.');
            throw new UserResetPasswordException('User not found');
        }

        // Générer un jeton de réinitialisation unique
        $resetToken = bin2hex(random_bytes(32));
        $user->setResetToken($resetToken);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        //Vérifiez si le jeton a été défini
        if (!$user->getResetToken()) {
            throw new UserResetPasswordException('Failed to generate the reset token');
        }
        // Générer l'URL de réinitialisation du mot de passe
        $resetUrl = $urlGenerator->generate('reset_password_from_link',
         ['token' => $resetToken], UrlGeneratorInterface::ABSOLUTE_URL);

        // Envoyer un e-mail à l'utilisateur avec l'URL de réinitialisation du mot de passe
        $email = (new Email())
            ->from('aquaelixir@example.com')
            ->to($user->getEmail())
            ->subject('Réinitialisation du mot de passe')
            ->html("Cliquez sur le lien suivant pour réinitialiser votre mot de passe:
                <a href='{$resetUrl}'>Reset Password</a");

                try {
                    $mailer->send($email);
                } catch (\Exception $e) {
                    throw new EmailSendException('Failed to send the reset email', $e);
                }
                
        return ServiceResult::createSuccess('E-mail de réinitialisation du mot de passe envoyé.');
    }

    // Gestion de reinitialisation de mot de passe
    public function findUserByResetToken(string $resetToken): ?User
    {
        // Utilisez Doctrine pour rechercher l'utilisateur par le jeton de réinitialisation
        return $this->entityManager->getRepository(User::class)->findOneBy(['resetToken' => $resetToken]);
    }

    public function resetUserPassword(User $user,): void
    {
        // Générez un nouveau mot de passe sécurisé en utilisant PasswordGenerator
        $newPassword = $this->passwordGenerator->generateRandomPassword();

        $newPasswordHash = $this->passwordHasher->hashPassword($user, $newPassword);

        // Mettez à jour l'utilisateur dans la base de données avec le nouveau mot de passe
        $user->setPassword($newPasswordHash);
        $user->setResetToken(null); // Supprimez le jeton de réinitialisation
        $this->entityManager->flush();

        // Envoyer un e-mail au nouvel utilisateur
        $email = (new Email())
            ->from('aquaelixir@example.com')
            ->to($user->getEmail())
            ->subject('Nouveau mot de passe')
            ->text('Votre nouveau mot de passe est : ' . $newPassword);

        $this->mailer->send($email);

    }
}
