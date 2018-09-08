<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'error' => $error,
            'lastUserName' => $lastUsername));
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){
        throw new \Exception('This should never be reached!');
    }


    /**
     * @Route("/", name="home")
     */
    public function homepage(){
        return $this->redirectToRoute('news_index');
    }

    /**
     * @Route("/activate/{hash}", name="activate")
     */
    public function activate($hash, UsersRepository $repository){
        $user = $repository->findOneBy(['hash'=>$hash]);
        if($user != null){
            $user->setStatus(true);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Ваш аккаунт активирован успешно!');
            return $this->redirectToRoute('security_login');
        }
        $this->addFlash('error', 'Ошибка активации!');
        return $this->redirectToRoute('security_login');
    }

}
