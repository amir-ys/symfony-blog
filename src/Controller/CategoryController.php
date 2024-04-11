<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'panel.categories.index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', compact('categories'));
    }

    #[Route('/categories/new', name: 'panel.categories.new')]
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($category->getName());
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('panel.categories.index');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
