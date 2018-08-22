<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuthController extends Controller
{
    public function index()
    {
        if (!is_null($this->getUser()->getDayId())) {
            return $this->redirectToRoute('day',[]);
        }

        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }
}
