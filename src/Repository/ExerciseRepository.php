<?php

namespace App\Repository;

use App\Entity\Exercise;
use App\Entity\Training;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Exercise::class);
    }


    public function saveExercise($exercise_name, $description, $user, $training_id = null )
    {
        try {
            $exercise = new Exercise();
            $exercise
                ->setExerciseName($exercise_name)
                ->setDescription($description)
                ->setCreationDate(\DateTime::createFromFormat(
                    \DateTimeInterface::W3C,
                    date(\DateTimeInterface::W3C)
                ))
                ->setLastUpdateDate(\DateTime::createFromFormat(
                    \DateTimeInterface::W3C,
                    date(\DateTimeInterface::W3C)
                ))
                ->setUserId($user)
                ->setDeleted(false)
            ;
            $this->getEntityManager()->persist($exercise);
            $this->getEntityManager()->flush();
            return "Exercise succesfully saved";
        } catch (\Exception $e) {
            return "ERROR \n $e";
        }
    }

    public function updateExercise($exerciseId, $trainingId)
    {
        try {
            /** @var Exercise $exercise */
            $exercise = $this->find($exerciseId);

            /** @var Training $training */
            $training = $this->getEntityManager()->getRepository('App:Training')->find($trainingId);

            $exercise
                ->setLastUpdateDate(\DateTime::createFromFormat(
                    \DateTimeInterface::W3C,
                    date(\DateTimeInterface::W3C)
                ))
                ->addTraining($training)
                ->setDeleted(false)
            ;

            $this->getEntityManager()->persist($exercise);
            $this->getEntityManager()->flush();
            return "Exercise succesfully saved";
        } catch (\Exception $e) {
            return "ERROR \n $e";
        }
    }

    public function findExercisesListBy(Training $training, User $user, bool $deleted = false): array
    {
        $exercises  = $this->getEntityManager()->getConnection()
            ->createQueryBuilder()
            ->select('e.*, t.training_id as training')
            ->from('exercise', 'e')
            ->leftJoin('e','trainings', 't', 't.exercise_id = e.exercise_id')
            ->where('e.user_id=:user')
            ->andWhere('t.training_id=:training')
            ->andWhere('e.deleted = false')
            ->setParameter('user' , $user->getUserId())
            ->setParameter('training', $training->getTrainingId())
            ->execute()
            ->fetchAll()
        ;

        return $exercises;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
