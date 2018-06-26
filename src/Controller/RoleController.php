<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{

    public function index()
    {
        return $this->render('user/index.html.twig');
    }


    public function admin()
    {
        return $this->render('admin/index.html.twig');
    }

}