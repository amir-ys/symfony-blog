<?php

namespace App\Form;

use App\Entity\Category;
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

class CategoryFormType extends AbstractType
{
    public function __construct(protected CategoryRepository $categoryRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('parent_id'))
            ->andWhere(Criteria::expr()->neq('id', $options['data']->getId()));

        $categories = $this->categoryRepository->matching($criteria)->getValues();
        $choices = ['بدون دسته پدر' => null];
        foreach ($categories as $category) {
            $choices[$category->getName()] = $category->getId();
        }

        $builder
            ->add('name', TextType::class, [
                'label' => 'نام',
            ])
            ->add('status', EnumType::class, [
                'class' => CategoryStatusEnum::class,
                'label' => 'انتخاب وضعیت',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('parent_id', ChoiceType::class, [
                'label' => 'انتخاب دسته پدر',
                'choices' => $choices,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'توضیحات',
                'required' => false
            ])
            ->add('logo', FileType::class, [
                'label' => 'لوگو',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'لطفا یک عکس معتبر وارد کنید.',
                    ])
                ],
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
