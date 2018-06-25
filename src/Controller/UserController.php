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


class UserController extends Controller{

    function index(){

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('App\Entity\User')->findAll();

        return $this->render('user/index.html.twig', array(
            'user' => $user
        ));
    }

    function newUser(Request $request){

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('lastname', TextType::class)
            ->add('firstname', TextType::class)
            ->add('sexe', TextType::class)
            ->add('age',TextType::class)
            ->add('create', SubmitType::class, array('label' => 'Create User'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();


            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/index.html.twig', array(
            'formUser' => $form->createView(),
        ));

    }
}