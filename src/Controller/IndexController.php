<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller{

    function index(){

        if ($this->isGranted('ROLE_ADMIN')) {

            return $this->redirectToRoute('app_user_index');

        }

        return $this->render('index.html.twig');
    }
}
