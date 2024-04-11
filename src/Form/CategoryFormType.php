<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'نام',
            ])
            ->add('parent_id', ChoiceType::class, [
                'label' => 'انتخاب دسته پدر',
                'choices' => [
                    'example_data_1' => 1,
                    'example_data_2' => 2,
                    'example_data_3' => 3,
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('description', TextareaType::class , [
                'label' => 'توضیحات'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'ثبت',
                'attr' => [
                    'class' => 'btn btn-primary btn-lg btn-block btn-uppercase mb-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class
        ]);
    }
}
