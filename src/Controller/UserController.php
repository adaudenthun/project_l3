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
            ->add('firstname', TextareaType::class)
            ->add('sexe', FileType::class)
            ->add('age')
            ->add('create', SubmitType::class, array('label' => 'Create User'))
            ->getForm();

        $form->handleRequest($request);

    }
}