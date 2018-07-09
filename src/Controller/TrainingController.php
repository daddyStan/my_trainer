<?php

namespace App\Controller;

use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;

class TrainingController extends Controller
{
    /** @var EntityManager */
    private $em;

    /** @var TrainingRepository */
    private $trainingRepository;

    /**
     * @return TrainingRepository
     */
    public function getTrainingRepository(): TrainingRepository
    {
        return $this->trainingRepository;
    }

    /**
     * @param TrainingRepository $trainingRepository
     * @return TrainingController
     */
    public function setTrainingRepository(TrainingRepository $trainingRepository): TrainingController
    {
        $this->trainingRepository = $trainingRepository;
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEm(): EntityManager
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     * @return TrainingController
     */
    public function setEm(EntityManager $em): TrainingController
    {
        $this->em = $em;
        return $this;
    }

    public function __construct(EntityManager $entityManager)
    {
        $this->setEm($entityManager)
            ->setTrainingRepository($this->em->getRepository('App:Training'));

    }

    public function index()
    {
        $result = null;
        $formFactory = Forms::createFormFactoryBuilder()
            ->getFormFactory();

        $form = $formFactory->createBuilder(
            FormType::class,null, [
                'action' => '/trainings',
                'method' => 'POST',
            ]
        )
            ->add("training_name", TextType::class)
            ->add("description", TextType::class)
            ->getForm();

        $view = $form->createView();
        $form->handleRequest();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $result = $this
                ->getTrainingRepository()
                ->saveTraining(
                    $data['training_name'],
                    $data['description']
                );
        }

        $trainingsSearch = $this->getTrainingRepository()->findAll();

        return $this->render('training/index.html.twig', [
            'controller_name' => 'TrainingController',
            'form'            => $view,
            "trainingsCount"  => count($trainingsSearch),
            "message" => $this->getTrainingRepository()->count([]),
            "result" => $result,
            "grid"  => $this->renderGrid($trainingsSearch)
        ]);
    }

    private function renderGrid($array)
    {
        return $array;
    }
}
