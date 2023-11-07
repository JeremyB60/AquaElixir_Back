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

    //Gestion d'inscription
    public function registerUser(RegisterUserDTO $registerUserDTO, ValidatorInterface $validator): ServiceResult
    {
        // Validation the DTO
        $violations = $validator->validate($registerUserDTO, new Assert\Collection([
            'firstName' => new Assert\NotBlank(),
            'lastName' => new Assert\NotBlank(),
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
            'password' => new Assert\Length(['min' => 8]),
        ]));

        if (count($violations) > 0) {
            // Gère les erreurs de validation (par exemple, renvoie une réponse d'erreur)
            // Enregistre ou traite les erreurs de validation
            // Vous pouvez accéder aux violations et prendre les mesures appropriées.
            // Par exemple, vous pouvez parcourir $violations pour enregistrer ou répondre à des erreurs spécifiques.
            return ServiceResult::createError('Validation failed', 400, (string) $violations);
        } else {
            // Continuer avec la logique d'enregistrement
            //...
            // Cette partie ne s'exécutera que si le DTO est valide.
            $user = new User();
            $user->setFirstName($registerUserDTO->firstName);
            $user->setLastName($registerUserDTO->lastName);
            $user->setEmail($registerUserDTO->email);
            $user->setRoles(['ROLE_USER']);

            // Encoder et définir le mot de passe
            $password = $this->passwordHasher->hashPassword($user, $registerUserDTO->password);
            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return ServiceResult::createSuccess();
        }
    }

    // Evoi d'email de confirmation d'inscription
    public function sendConfirmationEmail(User $user,
     MailerInterface $mailer, UrlGeneratorInterface $urlGenerator): ServiceResult
    {
        // Générer un jeton de confirmation unique
        $confirmationToken = bin2hex(random_bytes(32));
        $user->setConfirmationToken($confirmationToken);

        // Vérifiez si le jeton a été défini
        if (!$user->getConfirmationToken()) {
            throw new EmailConfirmationException('Failed to generate the confirmation token');
        }

        // Générer l'URL de confirmation d'e-mail
        $confirmationUrl = $urlGenerator->generate('confirm_email',
            ['token' => $confirmationToken], UrlGeneratorInterface::ABSOLUTE_URL);

        // Envoyer un e-mail de confirmation à l'utilisateur
        $email = (new Email())
            ->from('aquaelixir@example.com')
            ->to($user->getEmail())
            ->subject('Confirmation d\'e-mail')
            ->html("Cliquez sur le lien suivant pour confirmer votre e-mail:
                <a href='{$confirmationUrl}'>Confirm Email</a");

        try {
            $mailer->send($email);
        } catch (\Exception $e) {
            throw new EmailSendException('Failed to send the confirmation email', $e);
        }

        return ServiceResult::createSuccess('E-mail de confirmation envoyé.');
    }

    // Gestion de confirmation d'émail pour l'inscription
    public function confirmEmail(string $token): ServiceResult
    {
        // Vérifiez si un utilisateur a ce jeton de confirmation d'e-mail
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if (!$user) {
            // Si aucun utilisateur n'a ce jeton, renvoyez une erreur ou une exception.
            // Vous pouvez utiliser une exception personnalisée ici.
            throw new EmailConfirmationException('Email confirmation failed. Token not found.');
        }

        // Marquez l'e-mail comme confirmé et supprimez le jeton
        $user->setIsEmailConfirmed(true);
        $user->setConfirmationToken(null);

        // Mettez à jour l'utilisateur dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return ServiceResult::createSuccess('Email confirmed.');
    }

    // Gestion d'association du jeton de confirmation à l'utilisateur
    public function associateConfirmationToken(User $user, string $confirmationToken): void
    {
        $user->setConfirmationToken($confirmationToken);
        // Vous pouvez ajouter d'autres traitements ici, si nécessaire.
    }

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
