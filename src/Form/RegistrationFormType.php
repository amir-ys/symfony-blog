<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'نام',
            ])
            ->add('last_name', TextType::class, [
                'label' => 'نام خانوادگی',
            ])
            ->add('email', EmailType::class, [
                'label' => 'ایمیل',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'کلمه عبور',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'ایجاد حساب',
                'attr' => [
                    'class' => 'btn btn-primary btn-lg btn-block btn-uppercase mb-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email',
                    'message' => "این ایمیل از قبل ثبت شده است."
                ]),
            ],
        ]);
    }
}
