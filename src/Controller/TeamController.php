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

            $user_team = new UserTeam();

            $user_team->setUser($this->getUser()->getId());
            $user_team->setTeam($team->getId());

            $em->persist($user_team);
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

        //récupération des ids des utilisateurs de la team en question
        $user_team = $em->getRepository('App\Entity\UserTeam')->findBy(array(
           'team' => $idTeam
        ));

        //Création d'un client Guzzle afin de récupérer l'enesemble des matchs
        $client = new \GuzzleHttp\Client();
        $json = $client->request('GET', 'http://daudenthun.fr/api/listing');

        $resultats = json_decode($json->getBody(), true);

        $matchs = array();//Variable qui va contenir l'ensemble des matchs
        $compteur = 0;//Compteur pour la boucle et le tableau

        //Boucle qui va permettre retravailler la liste des matchs reçus
        foreach ($resultats as $match){

            $nomEquipe = key($match);

            $equipe1 = str_replace('é', 'e', $nomEquipe);

            $equipe1 = str_replace(' ', '', $equipe1);

            $matchs[$compteur]['equipe1'] = $equipe1;

            foreach ($match as $ligne){

                $nomEquipe2 = $ligne['vs'];

                $equipe2 = str_replace('é', 'e', $nomEquipe2);

                $equipe2 = str_replace(' ', '', $equipe2);


                $matchs[$compteur]['equipe2'] = $equipe2;
                $matchs[$compteur]['date'] = $ligne['date'];
                $matchs[$compteur]['score'] = $ligne['score'];
                $matchs[$compteur]['live'] = $ligne['live'];
            }
            $compteur++;
        }


        $listeUsers = array();//Liste des utilisateurs à envoyer à la vue
        $i = 0;//Compteur pour la boucle

        foreach ($user_team as $ligne){

            $nbPoints = 0;//Représente le nombre de points de l'utilisateur en fonction des ses paris

            $idUser = $ligne->getUser();//Récupération de l'utilisateur

            //Récupération de l'entité utilisateur
            $user = $em->getRepository('App\Entity\User')->findOneBy(array(
               'id' => $idUser
            ));

            //Récupération des paris de l'utilisateur
            $paris = $em->getRepository('App\Entity\Paris')->findBy(array(
                'user' => $user->getUsername(),
            ));

            //Comparaison des différents matchs et attribution des points
            foreach ($paris as $pari){
                foreach ($matchs as $matchh){
                    if($pari->getEquipe1() == $matchh['equipe1'] && $pari->getEquipe2() == $matchh['equipe2']){
                        if($pari->getScoreEquipe1() == $matchh['score'][0] && $pari->getScoreEquipe2() == $matchh['score'][1]){
                            $nbPoints += 20;
                        }elseif (($pari->getScoreEquipe1() < $pari->getScoreEquipe2() && $matchh['score'][0] < $matchh['score'][1]) || ($pari->getScoreEquipe1() > $pari->getScoreEquipe2() && $matchh['score'][0] > $matchh['score'][1])){
                            $nbPoints += 5;
                        }
                    }
                }
            }

            $user->setPoints($nbPoints);

            //Ajout de l'utilisateur au tableau envoyé à la vue
            $listeUsers[$i] = $user;
            $i++;
        }

        return $this->render('team/listingUsers.html.twig', array(
            'listeUsers' => $listeUsers
        ));
    }
}