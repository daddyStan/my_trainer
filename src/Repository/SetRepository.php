<?php

namespace App\Repository;

use App\Entity\Set;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Set::class);
    }

    public function saveSet($comment, $tries, $weight, $exerciseId, $user, $day)
    {
        try {
            $set = new Set();
            $set
                ->setComment($comment ?? '')
                ->setTries($tries)
                ->setWeight($weight)
                ->setExerciseId($exerciseId)
                ->setUserId($user)
                ->setDayId($day)
                ->setCreationDate(\DateTime::createFromFormat(
                    \DateTimeInterface::W3C,
                    date(\DateTimeInterface::W3C)
                ))
                ->setLastUpdateDate(\DateTime::createFromFormat(
                    \DateTimeInterface::W3C,
                    date(\DateTimeInterface::W3C)
                ))
                ->setDeleted(false)
            ;

            $this->getEntityManager()->persist($set);
            $this->getEntityManager()->flush();

            return 'Set succesfully saved';
        } catch (\Exception $e) {
            return "ERROR \n $e";
        }
    }
}
