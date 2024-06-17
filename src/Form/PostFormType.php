<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Enum\CategoryStatusEnum;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PostFormType extends AbstractType
{
    public function __construct(protected CategoryRepository $categoryRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'موضوع',
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'خلاصه',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'محتوا',
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
            'data_class' => Post::class
        ]);
    }
}
