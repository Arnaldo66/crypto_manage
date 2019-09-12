<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
         $error = $authenticationUtils->getLastAuthenticationError();
         $lastUsername = $authenticationUtils->getLastUsername();

         return $this->render(':Security:login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
         ]);
    }

    /**
     * @Route("/resetting/request", name="request_reset")
     */
    public function sendEmailResetPassword()
    {
        return $this->render(':Security:reset_password.html.twig', ['message' => '']);
    }

    /**
     * @Route("/resetting/done", name="reseting_done")
     */
    public function messageAfterEmail()
    {
        return $this->render(':Security:reseted_password.html.twig', [
            'message' => "Un e-mail a été envoyé.<br/>
            Il contient un lien sur lequel il vous faudra cliquer
            pour réinitialiser votre mot de passe.<br/><br/>
            Si vous ne recevez pas un email,
            vérifiez votre dossier spam ou essayez à nouveau."
        ]);
    }
    /**
     * @Route("/resetting/send-email", name="send_email_password")
     */
    public function sendingEmail(Request $request)
    {
        $data = $request->request->all();
        if (!isset($data['_email'])) {
            throw new \Exception("L'email n'est pas renseigné");
        }

        $email = $data['_email'];
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository('AppBundle\Entity\User')->findOneByEmail($email);
        if ($user === null) {
            return $this->render(':Security:reset_password.html.twig', ['message' => "Cet email n'existe pas."]);
        }

        $user->setTokenResetPassword($this->generatToken());
        $user->setTokenResetPasswordSentAt(new \DateTime());
        $entityManager->flush();

        return $this->redirectToRoute('reseting_done');
    }

    /**
     * @Route("/resetting/request/{token}", name="show_reset_password")
     */
    public function showResetPassword($token)
    {
        $user = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle\Entity\User')
            ->findOneByTokenResetPassword($token);
        if ($user === null) {
            throw $this->createNotFoundException("Ce token n'existe n'est pas valide");
        }
        return $this->render(':Security:password_to_reset.html.twig', ['message' => "", 'token' => $token]);
    }

    /**
     * @Route("/resetting/reset/{token}", name="do_reset_password")
     */
    public function doResetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, $token)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager
            ->getRepository('AppBundle\Entity\User')
            ->findOneByTokenResetPassword($token);
        if ($user === null) {
            throw $this->createNotFoundException("Ce token n'existe n'est pas valide");
        }

        $data = $request->request->all();
        if (!isset($data['password1']) || !isset($data['password2'])) {
            throw new \Exception("L'email n'est pas renseigné");
        }

        $password1 = $data['password1'];
        $password2 = $data['password2'];

        if ($password1 !== $password2) {
            return $this->render(':Security:password_to_reset.html.twig', [
                'message' => "Les mots de passe ne sont pas identiques",
                'token' => $token
            ]);
        }

        if (iconv_strlen($password1) < 8) {
            return $this->render(':Security:password_to_reset.html.twig', [
                'message' => "Le mot de passe doit faire au moins 8 caractères",
                'token' => $token
            ]);
        }

        $user->setPassword($passwordEncoder->encodePassword($user, $password1));
        $user->setTokenResetPassword(null);
        $user->setTokenResetPasswordSentAt(null);

        $entityManager->flush();

        return $this->render(':Security:password_changed.html.twig', [
            'message' => "Votre password a été modifié<br/>
            Veuillez vous connecter"
        ]);
    }


    public function generatToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
