<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use App\Service\RepositoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UsersController extends Controller
{

    public $manager;

    public function __construct(RepositoryManager $manager)
    {
        $this->manager = $manager;
    }
    /**
     * @Route("/", name="users_index", methods="GET")
     */
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('users/user_list.html.twig');
    }

    /**
     * @Route("/new/{role}", name="users_new", methods="GET|POST")
     */
    public function new($role, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, Request $request): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            switch ($role){
                case 'admin':
                    $user->setRoles('ROLE_ADMIN');
                    break;
                case 'moderator':
                    $user->setRoles('ROLE_MODERATOR');
                    break;
                default: return new Response("роль $role не существует");
            }

            $user->setInstitute($this->manager->getInstituteRepository()->find($request->get('institute')));
            $newPass = $this->generateRandomString();
            $user->setPassword($encoder->encodePassword($user,$newPass));
            $user->setHash(md5(uniqid()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $user->setPassword($newPass);

            $message = (new \Swift_Message('Добро пожаловать в MyStudy'))
                ->setFrom('vlad.malakeev@gmail.com')
                ->setTo($user->getUsername())
                ->setBody(
                    $this->renderView(
                        'users/user_email.html.twig',
                        array('user' => $user)
                    ),
                    'text/html'
                );
            $mailer->send($message);
            return $this->render('users/user_data.html.twig', ['user' => $user]);
        }

        return $this->render('users/user_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="users_edit", methods="GET|POST")
     */
    public function edit($id, UserPasswordEncoderInterface $encoder,Request $request): Response
    {
        $user = $this->manager->getUsersRepository()->find($id);
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if($newPass = $request->get('new_password') != ''){
                $user->setPassword($encoder->encodePassword($user, $newPass));
            }
            $this->addFlash('edit', 'Пользователь отредактирован!');
            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/user_form.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="users_delete", methods="DELETE")
     */
    public function delete($id, Request $request): Response
    {
        $user = $this->manager->getUsersRepository()->find($id);
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }
        $this->addFlash('delete', 'Пользователь удален!');
        return $this->redirectToRoute('users_index');
    }

    public function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
