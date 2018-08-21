<?php

namespace App\Controller;

use App\Entity\Training;
use App\Repository\ExerciseRepository;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;

class TrainingController extends Controller
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
    public function setEm(EntityManagerInterface $em): TrainingController
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
                'action' => '/trainings',
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

        $trainingsSearch = $this->getTrainingRepository()->findby(
            ['user_id' => $this->getUser()]
        );

        return $this->render('training/index.html.twig', [
            'controller_name' => 'TrainingController',
            'form'            => $view,
            "trainingsCount"  => count($trainingsSearch),
            "message" => $this->getTrainingRepository()->count([]),
            "result" => $result,
            "grid"  => $trainingsSearch
        ]);
    }

    public function show($id)
    {
        $trainingEntity = $this->getTrainingRepository()->findOneBy(['training_id' => $id]);

        $result = null;
        $formFactory = Forms::createFormFactoryBuilder()
            ->getFormFactory();

        $form = $formFactory->createBuilder(
            FormType::class,null, [
                'action' => "/training/$id",
                'method' => 'POST'
            ]
        )
            ->add("exercise_name", TextType::class,[
                'attr' => ['class' => 'form-control'],
            ])
            ->add("description", TextareaType::class,[
                'attr' => ['class' => 'form-control'],
            ])
            ->getForm();

        $view = $form->createView();
        $form->handleRequest();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $result = $this
                ->getExerciseRepository()
                ->saveExercise(
                    $data['exercise_name'],
                    $data['description'],
                    $trainingEntity,
                    $this->getUser()
                );
        }

        $exercises = $this->getExerciseRepository()->findBy(
            [
                'training_id' => $id,
                'user_id' => $this->getUser()
            ]
        );

        return $this->render('training/show.html.twig', [
            'result' => $result,
            'form' => $view,
            'exercises' => $exercises,
            'entity' => $this->getTrainingRepository()->findOneBy(['training_id' => $id]) ?? "Entity not founded"
        ]);
    }

    public function delete($id)
    {
        try {
            /** @var Training $t */
            $training = $this->getTrainingRepository()->findOneBy(['training_id' => $id]);
            $this->getEm()->remove($training);
            $this->getEm()->flush();
            $result = "Successfully deleted";
        } catch (\Exception $e) {
            $result = "Something wrong \n $e";
        }
        return $this->render('deleted.html.twig', [
            'result' => $result
        ]);
    }
}
