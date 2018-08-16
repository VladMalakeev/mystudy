<?php

namespace App\Controller;

use App\Entity\Users;
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
     * @Route("/admin", name="admin")
     */
    public function logined(){
        return new Response("you success logined");
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/password/{request}", name="pass")
     */
    public function pass(UserPasswordEncoderInterface $encoder, $request){
        $user = new Users();
        $encodedPassword = $encoder->encodePassword($user, $request);

        $em = $this->getDoctrine()->getManager();
        $user->setUsername("sofa2");
        $user->setPassword($encodedPassword);
        $user->setFName("faff2");
        $user->setSName("hahah2");
        $user->setRoles("ROLE_SUPER_ADMIN");
        $user->setStatus(1);
        $em->persist($user);
        $em->flush();
            return new Response("success, password: $encodedPassword");

    }

    /**
     * @Route("/getpass/{id}", name="get_pass")
     */
    public function getPass($id){
        $user = $this->getDoctrine()
            ->getRepository(Users::class)
            ->find($id);
        if($pass = $user->getPassword()){
            return new Response("success: $pass");
        }
        else{
            return new Response("error");
        }
        return new Response("without changes");
    }

    /**
     * @Route("/", name="home")
     */
    public function homepage(){
        return $this->render('admin/main.html.twig');
    }

}
