<?php

namespace App\Controller;

use App\Entity\Paris;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ParisController extends Controller{

    function index(){

        $em = $this->getDoctrine()->getManager();

        $paris = $em->getRepository('App\Entity\Paris')->findAll();

        return $this->render('paris/index.html.twig', array(
            'paris' => $paris
        ));
    }
}
