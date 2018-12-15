<?php

namespace App\Repository;

use App\Entity\Exercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Exercise::class);
    }
    public function save(Exercise $exercise)
    {
        try{
            $exercise->setCreationDate(\DateTime::createFromFormat(
                \DateTimeInterface::W3C,
                date(\DateTimeInterface::W3C)
            ))
            ->setLastUpdateDate(\DateTime::createFromFormat(
                \DateTimeInterface::W3C,
                date(\DateTimeInterface::W3C)
            ));
            $this->getEntityManager()->persist($exercise);
            $this->getEntityManager()->flush();

            return $exercise->getExerciseId();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function saveExercise($exercise_name, $description, $training_id, $user)
    {
        try {
            $training = new Exercise();
            $training
                ->setExerciseName($exercise_name)
                ->setTrainingId($training_id)
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

            $this->getEntityManager()->persist($training);
            $this->getEntityManager()->flush();

            return "Exercise succesfully saved";
        } catch (\Exception $e) {
            return "ERROR \n $e";
        }
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
