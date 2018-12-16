<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TrainingStatController extends Controller
{
    /**
     * @Route("/training/stat", name="training_stat")
     */
    public function index()
    {
        return $this->render('training_stat/index.html.twig', [
            'controller_name' => 'TrainingStatController',
            'is_training' => $this->getUser()->getDayId()
        ]);
    }
}
