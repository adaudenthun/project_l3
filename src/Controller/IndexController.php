<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller{

    function index(){

        return $this->render('index.html.twig');
    }
}
