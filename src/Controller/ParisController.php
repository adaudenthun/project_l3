<?php

namespace App\Controller;

use App\Entity\Paris;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ParisController extends Controller{

    function index(){

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_security_login');
        }

        $em = $this->getDoctrine()->getManager();

        $idUser = $this->getUser()->getId();

        $user = $em->getRepository('App\Entity\User')->findOneBy(array(
            'id' => $idUser
        ));

        $user = $user->getUsername();

        $paris = $em->getRepository('App\Entity\Paris')->findBy(array(
            'user' => $user
        ));

        return $this->render('paris/index.html.twig', array(
            'paris' => $paris
        ));
    }

    function indexMatch(){

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_security_login');
        }

        $client = new \GuzzleHttp\Client();
        $json = $client->request('GET', 'http://daudenthun.fr/api/listing');

        $resultats = json_decode($json->getBody(), true);

        $matchs = array();
        $compteur = 0;

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


        return $this->render('paris/listingMatchs.html.twig', array(
            'matchs' => $matchs
        ));
    }

    function parierMatch(Request $request, $equipe1, $equipe2){

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_security_login');
        }

        $em = $this->getDoctrine()->getManager();

        $idUser = $this->getUser()->getId();

        $pari = new Paris();

        $form = $this->createFormBuilder($pari)
            ->add('score_equipe1', IntegerType::class)
            ->add('score_equipe2', IntegerType::class)
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $pari = $form->getData();


            $score1 = $form->get('score_equipe1')->getData();
            $score2 = $form->get('score_equipe2')->getData();

            $pari->setEquipe1($equipe1);
            $pari->setEquipe2($equipe2);

            $pari->setDate(new \DateTime());

            $user = $em->getRepository('App\Entity\User')->findOneBy(array(
                'id' => $idUser
            ));

            $user = $user->getUsername();

            $pari->setUser($user);

            $pari->setScoreEquipe1($score1);
            $pari->setScoreEquipe2($score2);

            $em->persist($pari);
            $em->flush();

            return $this->redirectToRoute('app_paris_index');
        }

        return $this->render('paris/new.html.twig', array(
            'equipe1' => $equipe1,
            'equipe2' => $equipe2,
            'form' => $form->createView()
        ));



    }
}
