<?php

namespace App\Controller;

use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExerciseController extends Controller
{
    /** @var ExerciseRepository */
    private $exerciseRepository;

    /** @var EntityManager */
    private $em;

    /**
     * @return mixed
     */
    public function getExerciseRepository()
    {
        return $this->exerciseRepository;
    }

    /**
     * @param $exerciseRepository
     * @return ExerciseController
     */
    public function setExerciseRepository($exerciseRepository): self
    {
        $this->exerciseRepository = $exerciseRepository;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param $em
     * @return ExerciseController
     */
    public function setEm($em): self
    {
        $this->em = $em;
        return $this;
    }

    public function __construct(EntityManagerInterface $em)
    {
        $this->setEm($em)
             ->setExerciseRepository($this->em->getRepository('App:Exercise'));
    }

    public function index($id)
    {
        return $this->render('exercise/index.html.twig', [
            'controller_name' => 'ExerciseController',
            'id'              => $id
        ]);
    }
}
