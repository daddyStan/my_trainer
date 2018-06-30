<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SetController extends Controller
{
    /**
     * @Route("/set", name="set")
     */
    public function index()
    {
        return $this->render('set/index.html.twig', [
            'controller_name' => 'SetController',
        ]);
    }
}
