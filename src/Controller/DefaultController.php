<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller{

    function index(){
        return $this->render('index.html.twig');
    }
}
