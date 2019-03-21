<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SetController extends Controller
{
    public function index()
    {
        return $this->render('set/index.html.twig', [
            'controller_name' => 'SetController',
        ]);
    }
}
