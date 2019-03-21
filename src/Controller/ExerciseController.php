<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Repository\ExerciseRepository;
use App\Repository\SetRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;

class ExerciseController extends Controller
{
    /** @var ExerciseRepository */
    private $exerciseRepository;

    /** @var EntityManager */
    private $em;

    /** @var SetRepository */
    private $setRepository;

    /**
     * @return SetRepository
     */
    public function getSetRepository(): SetRepository
    {
        return $this->setRepository;
    }

    /**
     * @param SetRepository $setRepository
     * @return ExerciseController
     */
    public function setSetRepository(SetRepository $setRepository): self
    {
        $this->setRepository = $setRepository;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExerciseRepository(): ExerciseRepository
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
             ->setExerciseRepository($this->em->getRepository('App:Exercise'))
             ->setSetRepository($this->em->getRepository('App:Set'));
    }

    public function index($id)
    {
        return $this->render('exercise/index.html.twig', [
            'id'              => $id,
            'exercise'        => $this->getExerciseRepository()->findOneBy(['exercise_id' => $id, 'deleted' => false]),
            'is_training' => $this->getUser()->getDayId()
        ]);
    }

    public function delete($id)
    {
        try {
            /** @var Exercise $training */
            $exercise = $this->getExerciseRepository()->findOneBy(['exercise_id' => $id]);
            $exercise->setDeleted(true);

            $this->getEm()->persist($exercise);
            $this->getEm()->flush();

            $result = "Successfully deleted";
        } catch (\Exception $e) {
            $result = "Something wrong";
        }

        return $this->render('deleted.html.twig', [
            'result' => $result,
            'is_training' => $this->getUser()->getDayId()
        ]);
    }

    public function ExerciseList()
    {
        return $this->render('exercise/list.html.twig', [
            'grid'     => $this->getExerciseRepository()->findBy(['deleted' => 0,'user_id' => $this->getUser()]),
            'is_training' => $this->getUser()->getDayId()
        ]);
    }

    public function ExerciseCreate()
    {
        $result = null;
        $formFactory = Forms::createFormFactoryBuilder()
            ->getFormFactory();

        $form = $formFactory->createBuilder(
            FormType::class,null, [
                'action' => "/exercise/create",
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
                    $this->getUser()
                );
        }

        return $this->render('exercise/create.html.twig', [
            'is_training' => $this->getUser()->getDayId(),
            'form' => $view,
            'message' => $result ?? ""
        ]);
    }
}
