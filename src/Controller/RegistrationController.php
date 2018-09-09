<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;

class RegistrationController extends Controller
{
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new User();
        $formFactory = Forms::createFormFactoryBuilder()
            ->getFormFactory();

        $firstRand = rand(1,10);
        $secondRand = rand(11,99);

        $translator = $this->get("translator");

        $form = $formFactory->createBuilder(
            FormType::class,null, [
                'action' => "/registration",
                'method' => 'POST'
            ]
        )
            ->add("login", TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add("password", PasswordType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add("answer", NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'color:blue;width:80%',
                    'placeholder' => "Answer, please"
                ]
            ])
            ->add("first", HiddenType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'value' => $firstRand
                ]
            ])
            ->add("second", HiddenType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'value' => $secondRand
                ]
            ])
            ->getForm();

        $form->handleRequest();
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $error = "";

            if (isset($data['answer']) && $data['answer'] == ( $data['first'] + $data['second'] ) ) {
                $password = $passwordEncoder->encodePassword($user, $data['password']);
                $user->setPassword($password);

                $user->setUserName($data['login']);
                $user->setIsActive(true);
                $user->setRegistrationDate(
                    \DateTime::createFromFormat(
                        \DateTimeInterface::W3C,
                        date(\DateTimeInterface::W3C)
                    )
                );
                $user->setLastVisitDate(
                    \DateTime::createFromFormat(
                        \DateTimeInterface::W3C,
                        date(\DateTimeInterface::W3C)
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute("login");
            } else {
                $error = "You entered an invalid summ or somthing went wrong";
            }

            return $this->render('registration/index.html.twig', [
                'controller_name' => 'RegistrationController',
                'form' => $form->createView(),
                'first' => $firstRand,
                'second' => $secondRand,
                'error' => $error
            ]);
        }

        return $this->render('registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
            'form' => $form->createView(),
            'first' => $firstRand,
            'second' => $secondRand,
            'form_errors' => $form->getErrors()
        ]);
    }
}
