<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25/06/18
 * Time: 14:37
 */

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends Controller
{

    function index()
    {

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_security_login');
        }

        if (!$this->isGranted('ROLE_ADMIN')) {

            return $this->redirectToRoute('app_index');

        }

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('App\Entity\User')->findAll();

        return $this->render('admin/index.html.twig', array(
            'users' => $user
        ));
    }


    function newUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_index');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getMdp());
            $user->setMdp($password);

            // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
            $user->setRoles(['ROLE_USER']);

            // On enregistre l'utilisateur dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_security_login');
        }

        return $this->render(
            'security/register.html.twig',
            array('formUser' => $form->createView())
        );

    }

    function deleteUser($idUser)
    {

        $em = $this->getDoctrine()->getManager();
        $usrRepo = $em->getRepository(User::class);

        $user = $usrRepo->find($idUser);
        $em->remove($user);
        $em->flush();


        return $this->redirectToRoute('app_user_index');


    }


    //Lack of time to develop this function

    function mySpace(Request $request){

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user)
            ->add('nom', TextType::class, $user->getNom())
            ->add('username', TextType::class, $user->getUsername())
            ->add('mail', EmailType::class, $user->getMail())
            ->add('mdp', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Confirmation'),
            ));

        $form->handleRequest($request);


    }
}