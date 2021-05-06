<?php
// src/Controller/LoginController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\ResetPassType;

class LoginController extends AbstractController
{
    /**
     * Page de connexion
     *
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            ]
        );
    }

    /**
     * Page d'oubli de mot de passe
     *
     * @Route("/login/forgot", name="security_forgot")
     */
    public function forgot(Request $request, UserRepository $user, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        $form = $this->createForm(ResetPassType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();

            $this_user = $user->findOneBy(['mail' => $donnees['email']]);

            if ($this_user === null) {
                $this->addFlash('error', 'Cette adresse e-mail est inconnue');

                return $this->redirectToRoute('security_login');
            }

            $token = $tokenGenerator->generateToken();

            try{
                $this_user->setResetToken($token);
                $em = $this->getDoctrine()->getManager();
                $em->persist($this_user);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('security_login');
            }

            $url = $this->generateUrl('security_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('contact.snowtricks@gmail.com')
                ->setTo($this_user->getMail())
                ->setBody(
                    "Bonjour,<br><br>Vous venez d'effectuer une demande de réinitialisation du mot de passe sur le site Snowtricks, veuillez cliquer sur le lien suivant: ".$url,'text/html'
                )
            ;

            $mailer->send($message);

            $this->addFlash('success', "Un email vient d'être envoyé à votre adresse mail");

            return $this->redirectToRoute('security_login');

        }

        return $this->render('login/forgot.html.twig', [
            'emailForm' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/reset_pass/{token}", name="security_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);


        if ($user === null) {

            $this->addFlash('error', 'Token Inconnu');
            return $this->redirectToRoute('security_login');
        }

        if ($request->isMethod('POST')) {

            $user->setResetToken(null);

            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Mot de passe mis à jour');

            return $this->redirectToRoute('security_login');
        }else {
            return $this->render('login/reset_password.html.twig', ['token' => $token]);
        }

    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}