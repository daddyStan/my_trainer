<?php

namespace App\Controller;

use App\Entity\Day;
use App\Entity\User;
use App\Repository\DayRepository;
use App\Repository\ExerciseRepository;
use App\Repository\SetRepository;
use App\Repository\TrainingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Tarantool\Mapper\Repository;

class DayController extends Controller
{
    /** @var DayRepository */
    private $dayRepository;

    /** @var TrainingRepository */
    private $trainigRepository;

    /** @var EntityManager*/
    private $em;

    /** @var UserRepository */
    private $userRepository;

    /** @var ExerciseRepository */
    private $exerciseRepository;

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
     * @return self
     */
    public function setSetRepository(SetRepository $setRepository): self
    {
        $this->setRepository = $setRepository;
        return $this;
    }

    /**
     * @return ExerciseRepository
     */
    public function getExerciseRepository(): ExerciseRepository
    {
        return $this->exerciseRepository;
    }

    /**
     * @param ExerciseRepository $exerciseRepository
     * @return self
     */
    public function setExerciseRepository(ExerciseRepository $exerciseRepository): self
    {
        $this->exerciseRepository = $exerciseRepository;
        return $this;
    }
    /**
     * @return UserRepository
     */
    public function getUserRepository(): EntityRepository
    {
        return $this->userRepository;
    }

    /**
     * @param UserRepository $userRepository
     * @return self
     */
    public function setUserRepository(EntityRepository $userRepository): self
    {
        $this->userRepository = $userRepository;
        return $this;
    }

    /**
     * @return TrainingRepository
     */
    public function getTrainigRepository(): TrainingRepository
    {
        return $this->trainigRepository;
    }

    /**
     * @param TrainingRepository $trainigRepository
     * @return self
     */
    public function setTrainigRepository(TrainingRepository $trainigRepository): self
    {
        $this->trainigRepository = $trainigRepository;
        return $this;
    }

    /**
     * @return DayRepository
     */
    public function getDayRepository()
    {
        return $this->dayRepository;
    }

    /**
     * @param mixed $dayRepository
     * @return self
     */
    public function setDayRepository($dayRepository): self
    {
        $this->dayRepository = $dayRepository;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEm(): EntityManager
    {
        return $this->em;
    }

    /**
     * @param $em
     * @return self
     */
    public function setEm($em): self
    {
        $this->em = $em;
        return $this;
    }

    public function __construct(EntityManagerInterface $em)
    {
        $this->setEm($em)
            ->setDayRepository($this->em->getRepository('App:Day'))
            ->setTrainigRepository($this->em->getRepository('App:Training'))
            ->setUserRepository($this->em->getRepository('App:User'))
            ->setExerciseRepository($this->em->getRepository('App:Exercise'))
            ->setSetRepository($this->em->getRepository('App:Set'))
        ;
    }

    public function index()
    {
            $grid = $this->getTrainigRepository()->findBy([
                'user_id' => $this->getUser(),
                'deleted' => false
            ]);

            return $this->render('day/index.html.twig', [
                'controller_name' => 'DayController',
                'grid'            => $grid,
                'is_training' => $this->getUser()->getDayId()
            ]);
    }

    public function started($training_id)
    {
        if (is_null($this->getUser()->getDayId())) {
            $training = $this->getTrainigRepository()->findOneBy(['training_id' => $training_id]);
            $this->updateUsersDay($this->getUser(), $training);
        }

        /** @var Day $day */
        $day = $this->getDayRepository()->findOneBy([
            'day_id' => $this->getUser()->getDayId()
        ]);

        $grid = $this->getExerciseRepository()->findBy([
            'user_id'       => $this->getUser(),
            'training_id'   => $this->getTrainigRepository()->findOneBy([
                'training_id' => $day->getTrainingId()
            ]),
            'deleted' => false
        ]);

        return $this->render('day/day.html.twig', [
            'controller_name' => 'DayController',
            'grid' => $grid,
            'day' => $day,
            'is_training' => $this->getUser()->getDayId()
        ]);
    }

    /** Создаём  и возвращаем id тренировочного дня */
    public function updateUsersDay($user, $training)
    {
        try {
            $day = new Day();
            $day->setUserId($user)
                ->setCreationDate(
                    \DateTime::createFromFormat(
                        \DateTimeInterface::W3C,
                        date(\DateTimeInterface::W3C)
                    )
                )
                ->setTrainingId($training)
                ->setDeleted(false)
            ;
            $this->getEm()->persist($day);
            $this->getEm()->flush();

            /** @var User $user */
            $user = $this->getUser();
            $user->setDayId($day);

            $this->getEm()->persist($user);
            $this->getEm()->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function exercise($day_id, $exrcise_id, $training_id, Request $request)
    {
        $exerciseEntity = $this->getExerciseRepository()->findOneBy(['exercise_id' => $exrcise_id]);
        $result = null;
        $formFactory = Forms::createFormFactoryBuilder()
            ->getFormFactory();

        $form = $formFactory->createBuilder(
            FormType::class,null, [
                'action' => "/day_exercise/$day_id/$training_id/$exrcise_id/",
                'method' => 'POST'
            ]
        )
            ->add("tries", TextType::class, [
                'attr' => [
                    'value' => (integer)$request->get("form")["tries"] ?? 0,
                    'inputmode' => 'numeric',
                    'pattern'   => '[0-9]*',
                    'class' => 'form-control'
                ]
            ])
            ->add("weight", TextType::class, [
                'attr' => [
                    'value' => (integer)$request->get("form")["weight"] ?? 0,
                    'inputmode' => 'numeric',
                    'pattern'   => '[0-9]*',
                    'class' => 'form-control'
                ]
            ])
            ->add("comment", TextareaType::class, [
                'required' =>false,
                'attr' => ['class' => 'form-control']
            ])
            ->getForm();

        $day = $this->getDayRepository()->findOneBy(['day_id' => $day_id]);

        $view = $form->createView();
        $form->handleRequest();

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $result = $this
                ->getSetRepository()
                ->saveSet(
                    $data['comment'],
                    $data['tries'],
                    $data['weight'],
                    $exerciseEntity,
                    $this->getUser(),
                    $day
                );
        }

        $grid = $this->getSetRepository()->findBy([
            'user_id'       => $this->getUser()->getUserId(),
            'exercise_id'   => $exrcise_id,
            'day_id'        => $day,
            'deleted'       => false
        ]);

        /** @var Day $lastTraining */
        $lastTraining = $this->getEm()->getConnection()->createQueryBuilder()
            ->select("d.day_id")
            ->from("day d")
            ->where("d.user_id=:user_id")
            ->andWhere("d.training_id=:training_id")
            ->setParameters(["user_id" => $this->getUser()->getUserId(), "training_id" => $training_id])
            ->orderBy("d.day_id","DESC")
            ->setMaxResults(2)
            ->execute()
            ->fetchAll()
        ;

        $sets = [];

        if (isset($lastTraining[1])) {
            $sets = $this->getSetRepository()->findBy([
               "day_id"       => $lastTraining[1],
                "exercise_id" => $exrcise_id,
                "user_id"     => $this->getUser()->getUserId()
            ], ["set_id" => "ASC"]);
        }

        return $this->render('day/day_sets.html.twig', [
            'controller_name' => 'DayController',
            'grid'            => $grid,
            'form'            => $view,
            'result'          => $result,
            'sets'   => $sets,
            'is_training' => $this->getUser()->getDayId(),
            'training' => $training_id
        ]);
    }

    public function trainingFinish($day_id)
    {
        $tarantool = $this->get("tarantool.wrap");

        /** @var Day $day */
        $day = $this->getDayRepository()->findOneBy(['day_id' => $day_id]);

        $message = $this->getDayRepository()->updateAndCloseDay($day, $this->getUser());

        /** @var Repository $repository */
        $repository = $tarantool->getMapper()->getRepository('user_last_training_day');


        $user_last_training_day = $repository->findOne($this->getUser()->getUserId());

        if (!is_null($user_last_training_day)) {
            $user_last_training_day->day_id = $day_id;
            $user_last_training_day->creation_date = $message[1]->getCreationDate()->format('m-d');
            $user_last_training_day->main_time = $message[1]->getMainTime();
            $tarantool->getMapper()->save($user_last_training_day);
        } else {
            $tarantool->getClient()->getSpace('user_last_training_day')->insert([
                $this->getUser()->getUserId(),
                $day_id,
                $message[1]->getCreationDate()->format('Y-m-d'),
                $message[1]->getMainTime()
            ]);
        }

        return $this->render('day/finish.html.twig', [
            'controller_name' => 'DayController',
            'message'         => $message[0],
            'is_training' => $this->getUser()->getDayId()
        ]);

    }
}
