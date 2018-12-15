<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tarantool\Mapper\Repository;

class AuthController extends Controller
{
    public function index()
    {
        $tarantool = $this->get("tarantool.wrap");

        try {
            /** @var Repository $repository */
            $repository = $tarantool->getMapper()->getRepository('user_last_training_day');
        } catch (\Exception $e) {
            $tarantool->setUserLastTrainingSpace();
            $repository = $tarantool->getMapper()->getRepository('user_last_training_day');
        }

        $user_last_training_day = $repository->find($this->getUser()->getUserId());

        return $this->render('auth/index.html.twig', [
            'controller_name'        => 'AuthController',
            'user_last_training_day' => $user_last_training_day[0] ?? null,
            'is_training'             => $this->getUser()->getDayId()
        ]);
    }
}

