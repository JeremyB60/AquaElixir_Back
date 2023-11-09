<?php

namespace App\Controller;

use App\Service\UserService;
use Psr\Log\LoggerInterface;
use App\DTO\Request\LoginUserDTO;
use Symfony\Component\Mime\Email;
use App\DTO\Request\RegisterUserDTO;
use App\DTO\Request\DeleteAccountDTO;
use App\DTO\Request\ResetPasswordDTO;
use App\DTO\Request\ModifyAccountDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

//....................................REGISTER................................................................

    /**
 * @Route('/api/register', name: 'register', methods: ['POST'])
 */
    public function register(Request $request, UserService $userService, ValidatorInterface $validator): JsonResponse
    {
        $registerUserDTO = RegisterUserDTO::createFromRequest($request);
        $violations = $validator->validate($registerUserDTO);

        if (count($violations) > 0) {
            // Si la validation échoue, renvoyez un message d'erreur avec les violations.
            return new JsonResponse(['message' => 'Validation failed', 'errors' => $violations], 400);
        }

        // Si la validation réussit, on appelle le UserService pour créer le compte.
        $result = $userService->registerUser($registerUserDTO, $validator);

        if ($result->isSuccess()) {
            return new JsonResponse(['message' => 'Registration successful. A confirmation email has been sent.'], 200);
        } else {
            return new JsonResponse(['message' => 'Registration failed'], 400);
        }
    }


     /**
     * @Route("/api/confirm-email/{token}", name="confirm_email", methods={"GET"})
     */
    public function confirmEmail(string $token, UserService $userService, LoggerInterface $logger): JsonResponse
    {
        $result = $userService->confirmEmail($token, $logger);

        if ($result->isSuccess()) {
            // Add a flash message for the user
            $this->addFlash('success', 'E-mail confirmé. Inscription réussi. Vous pouvez maintenant vous connecter.');
    
            // Redirect to the login page
          //  return $this->redirectToRoute('login');
        //} else {
            return new JsonResponse(['message' => 'Email confirmation failed'], 400);
        }
    }
    
//....................................LOGIN............................................................

   /**
    * @Route('/api/login', name: 'login', methods: ['POST'])
     */
    public function login(Request $request, UserService $userService): JsonResponse
    {
        // Pour validez et gérez la connexion de l'utilisateur à l'aide de LoginUserDTO.
        $loginUserDTO = LoginUserDTO::createFromRequest($request);
        $result = $userService->loginUser($loginUserDTO);

        if ($result->isSuccess()) {
            return new JsonResponse(['message' => 'Login successful'], 200);
        } else {
            return new JsonResponse(['message' => 'Login failed'], 401);
        }
    }

//....................................RESET PASSEWORD...................................................

    /**
     * @Route('/api/reset-password', name: 'reset_password', methods: ['GET', 'POST'])
     */
    public function resetPassword(Request $request,
     UserService $userService, MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator, LoggerInterface $logger): JsonResponse
    {
        $resetPasswordDTO = ResetPasswordDTO::createFromRequest($request);
            
        $result = $userService->resetPassword($resetPasswordDTO, $mailer, $urlGenerator);
    
        if ($result->isSuccess()) {
            // Utilisez le journal pour enregistrer un message en cas de succès
            $logger->info('E-mail de réinitialisation du mot de passe envoyé avec succès.', [
                'email' => $resetPasswordDTO->email,
            ]);
    
            return new JsonResponse(['message' => 'Password reset successful']);
        } else {
            // Utilisez le journal pour enregistrer un message en cas d'échec
            $logger->error('Échec de l\'envoi de l\'e-mail de réinitialisation du mot de passe.', [
                'email' => $resetPasswordDTO->email,
            ]);
    
            return new JsonResponse(['message' => 'Password reset failed'], 400);
        }
    }
    
    /**
     * @Route('/api/reset-password/{token}', name: 'reset_password_from_link', methods: [GET, POST] )
     */
    public function resetPasswordFromLink(string $token, UserService $userService,
     LoggerInterface $logger): JsonResponse
    {
        // Log the matched route and token for debugging
        $this->logger->info('Matched route "reset_password_from_link" with token: ' . $token);

        // Recherchez l'utilisateur par le jeton de réinitialisation
        $user = $userService->findUserByResetToken($token);

        if ($user) {
            // Réinitialisez le mot de passe de l'utilisateur
            $userService->resetUserPassword($user);

            return new JsonResponse(['message' => 'Password reset successfully']);
        }

        return new JsonResponse(['message' => 'Password reset failed'], 400);
    }

//....................................LOGOUT...................................................

    /**
     * @Route('/api/logout', name: 'logout', methods: ['GET'])
     */
    public function logout(Request $request, AuthenticationUtils $authenticationUtils): void
    {
        // Cette action sert uniquement à déclencher la déconnexion,
        // pas besoin d'implémenter une logique personnalisée.
    }

//....................................DELETE ACCOUNT...................................................

    /**
     * @Route("/api/delete-account", name:"delete_account", methods:{"DELETE"})
     */
    public function deleteAccount(DeleteAccountDTO $deleteAccountDTO,
     EntityManagerInterface $entityManager, Security $security): JsonResponse
    {
        // Get the currently authenticated user
        $user = $security->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Check if the provided data matches user data (e.g., password confirmation)
        if (!$deleteAccountDTO->isDataValid($user)) {
            return new JsonResponse(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // Implement the logic for deleting the user's account
        $entityManager->remove($user);
        $entityManager->flush();

        // Invalidate the user's authentication token if needed

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
    
}
