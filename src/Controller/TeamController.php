<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\UserTeam;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class TeamController extends Controller{

    function index(){

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_security_login');
        }

        $em = $this->getDoctrine()->getManager();

        $idUser = ($this->getUser()->getId());

        $teams = $em->getRepository('App\Entity\Team')->findAll();

        return $this->render('team/index.html.twig', array(
            'teams' => $teams,
            'userId' => $idUser
        ));
    }

    function newTeam(Request $request){

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_security_login');
        }

        $team = new Team();

        $form = $this->createFormBuilder($team)
            ->add('nom', TextType::class)
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $code = $this->Genere_Password(6);

            $team->setCode($code);

            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('app_team_index');
        }

        return $this->render('team/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    function Genere_Password($size)
    {

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_security_login');
        }

        // Initialisation des caractères utilisables
        $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        $password = '';
        for($i=0;$i<$size;$i++)
        {
            $password .= ($i%2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
        }

        return $password;
    }

    function rejoindreTeam($idUser, $idTeam){

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_security_login');
        }

        $em = $this->getDoctrine()->getManager();

        $team_user = $em->getRepository('App\Entity\UserTeam')->findOneBy(array(
            'user' => $idUser,
            'team' => $idTeam
        ));

        if($team_user){
            return $this->redirectToRoute('app_team_index');
        }else {

            $user_team = new UserTeam();

            $user_team->setTeam($idTeam);
            $user_team->setUser($idUser);

            $em->persist($user_team);
            $em->flush();

            return $this->redirectToRoute('app_team_index');
        }
    }

    function listingUsers($idTeam){

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_security_login');
        }

        $em = $this->getDoctrine()->getManager();

        $user_team = $em->getRepository('App\Entity\UserTeam')->findBy(array(
           'team' => $idTeam
        ));

        $listeUsers = array();
        $i = 0;

        foreach ($user_team as $ligne){
            $idUser = $ligne->getUser();

            $user = $em->getRepository('App\Entity\User')->findOneBy(array(
               'id' => $idUser
            ));

            $listeUsers[$i] = $user;
            $i++;
        }

        return $this->render('team/listingUsers.html.twig', array(
            'listeUsers' => $listeUsers
        ));
    }
}