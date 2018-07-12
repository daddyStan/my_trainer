<?php

namespace App\Controller;

use App\Repository\ExerciseRepository;
use App\Repository\SetRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
        $exerciseEntity = $this->getExerciseRepository()->findOneBy(['exercise_id' => $id]);
        $result = null;
        $formFactory = Forms::createFormFactoryBuilder()
            ->getFormFactory();

        $form = $formFactory->createBuilder(
            FormType::class,null, [
                'action' => "/exercise/$id",
                'method' => 'POST'
            ]
        )
            ->add("tries", TextType::class, [
                'attr' => [
                    'value' => 0,
                    'inputmode' => 'numeric',
                    'pattern'   => '[0-9]*'
                ]
            ])
            ->add("weight", TextType::class, [
                'attr' => [
                    'value' => 0,
                    'inputmode' => 'numeric',
                    'pattern'   => '[0-9]*'
                ]
            ])
            ->add("comment", TextareaType::class)
            ->getForm();

        $view = $form->createView();
        $form->handleRequest();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            if ( $form->getErrors() ) {
            $result = $this
                ->getSetRepository()
                ->saveSet(
                    $data['comment'],
                    $data['tries'],
                    $data['weight'],
                    $exerciseEntity
                );
            }
        }


        return $this->render('exercise/index.html.twig', [
            'id'              => $id,
            'exercise'        => $this->getExerciseRepository()->findOneBy(['exercise_id' => $id]),
            'form'            => $view,
            'result'          => $result
        ]);
    }
}
