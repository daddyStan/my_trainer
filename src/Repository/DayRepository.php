<?php

namespace App\Repository;

use App\Entity\Day;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DayRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Day::class);
    }

    public function saveDay($exercise_name, $description, $training_id, $user)
    {

    }

    public function updateAndCloseDay(Day $day, $user)
    {
        $day->setFinishDate(
            \DateTime::createFromFormat(
                \DateTimeInterface::W3C,
                date(\DateTimeInterface::W3C)
            )
        );

        $diff = $day->getCreationDate()->diff($day->getFinishDate(),true);
        $str = "Hours: " . $diff->h . ", minutes: " . $diff->i;
        $day->setMainTime($str);

        $user->setDayId(null);

        $this->getEntityManager()->persist($day);
        $this->getEntityManager()->flush();

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return [$str, $day];
    }
}
