<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Service\FileUploader;
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
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader)
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                $logoFileName = $fileUploader->upload($logoFile);
                $category->setLogo($logoFileName);
            }

            $category->setSlug($category->getName());
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'دسته بندی با موفقیت ایجاد شد.');
            return $this->redirectToRoute('panel.categories.index');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/categories/{id}/edit', name: 'panel.categories.edit')]
    public function edit(
        Category               $category,
        Request                $request,
        EntityManagerInterface $entityManager,
        FileUploader           $fileUploader
    ): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                $fileUploader->removePreviousUpload($category->getLogo());
                $logoFileName = $fileUploader->upload($logoFile);
                $category->setLogo($logoFileName);
                $category->setLogo($logoFileName);
            }

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'دسته بندی با موفقیت بروزرسانی شد.');
            return $this->redirectToRoute('panel.categories.index');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    #[Route('/categories/{id}', name: 'panel.categories.delete')]
    public function delete(Category $category, EntityManagerInterface $entityManager): Response
    {
        foreach ($category->getChildren() as $child) {
            $entityManager->remove($child);
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('panel.categories.index');
    }
}
