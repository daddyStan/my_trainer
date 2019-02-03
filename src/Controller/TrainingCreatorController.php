<?php

namespace App\Controller;

use App\Repository\ExerciseRepository;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;

class TrainingCreatorController extends Controller
{
    /** @var EntityManager */
    private $em;

    /** @var TrainingRepository */
    private $trainingRepository;

    /** @var ExerciseRepository */
    private $exerciseRepository;

    /**
     * @return ExerciseRepository
     */
    public function getExerciseRepository(): ExerciseRepository
    {
        return $this->exerciseRepository;
    }

    /**
     * @param ExerciseRepository $exerciseRepository
     * @return TrainingController
     */
    public function setExerciseRepository(ExerciseRepository $exerciseRepository): self
    {
        $this->exerciseRepository = $exerciseRepository;
        return $this;
    }

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
    public function setTrainingRepository(TrainingRepository $trainingRepository): self
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
     * @param EntityManagerInterface $em
     * @return TrainingController
     */
    public function setEm(EntityManagerInterface $em): TrainingCreatorController
    {
        $this->em = $em;
        return $this;
    }

    /**
     * TrainingController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->setEm($em)
            ->setTrainingRepository($this->em->getRepository('App:Training'))
            ->setExerciseRepository($this->em->getRepository('App:Exercise'));
    }


    public function index()
    {
        $result = null;
        $formFactory = Forms::createFormFactoryBuilder()
            ->getFormFactory();

        $form = $formFactory->createBuilder(
            FormType::class,null, [
                'action' => '/training/create',
                'method' => 'POST',
            ]
        )
            ->add("training_name", TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add("description", TextareaType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->getForm();

        $view = $form->createView();
        $form->handleRequest();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $result = $this
                ->getTrainingRepository()
                ->saveTraining(
                    $data['training_name'],
                    $data['description'],
                    $this->getUser()
                );
        }

        $trainingsSearch = $this->getTrainingRepository()->findby([
            'user_id' => $this->getUser(),
            'deleted' => false
        ]);

        return $this->render('training_creator/index.html.twig', [
            'is_training' => $this->getUser()->getDayId(),
            'form'            => $view,
            "trainingsCount"  => count($trainingsSearch),
            "message" => $this->getTrainingRepository()->count([]),
            "result" => $result,
            "grid"  => $trainingsSearch,

        ]);
    }
}
