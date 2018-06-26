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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Form\UserType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserController extends Controller{

    function index(){

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('App\Entity\User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $user
        ));
    }


    /**
     * @Route("/register", name="user_registration")
     */
    function newUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
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
            'register.html.twig',
            array('formUser' => $form->createView())
        );

    }
}